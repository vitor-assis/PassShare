<?php

namespace App\Http\Controllers;

use App\Models\Secret;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SecretController extends Controller
{
    public function create()
    {
        return view('create_secret');
    }

    public function store(Request $request)
    {
        // Validação: Garante que tem OU mensagem OU arquivo
        $request->validate([
            'sender' => 'nullable|string|max:100', // Validação do nome
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
            'views' => 'required|integer',
            'duration' => 'required|integer',
        ]);

        if (!$request->message && !$request->hasFile('file')) {
            return back()->withErrors(['message' => 'Você precisa enviar um texto ou um arquivo.']);
        }

        $expiresAt = null;
        if ((int) $request->duration > 0) {
            $expiresAt = now()->addMinutes((int) $request->duration);
        }

        $hash = Str::uuid();
        $type = 'text';
        $filePath = null;
        $fileName = null;
        // O conteúdo será a mensagem (seja da aba texto ou da nota do arquivo)
        $content = $request->message ? Crypt::encryptString($request->message) : null;

        if ($request->hasFile('file')) {
            $type = 'file'; // Se tem arquivo, o tipo principal é file
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('secrets');
        }

        Secret::create([
            'hash' => $hash,
            'type' => $type,
            'content' => $content, // Agora salva o texto mesmo se for arquivo
            'file_path' => $filePath,
            'file_name' => $fileName,
            'sender' => $request->sender, // Salva o nome
            'max_views' => $request->views,
            'current_views' => 0,
            'expires_at' => $expiresAt,
        ]);

        return redirect()->route('secret.create')
            ->with('link_gerado', url("/secret/{$hash}"));
    }

    // O "Porteiro"
    public function confirm($hash)
    {
        // Tenta achar. Se não achar, vai para a página de erro customizada
        $secret = Secret::where('hash', $hash)->first();

        // Verificação 1: O registro existe?
        if (!$secret) {
            return view('errors.expired');
        }

        // Verificação 2: O tempo expirou?
        if ($secret->expires_at && now()->greaterThan($secret->expires_at)) {
            $secret->delete(); // Limpa sujeira velha
            return view('errors.expired');
        }

        // Verificação 3: Atingiu o limite de views?
        if ($secret->current_views >= $secret->max_views) {
            $secret->delete();
            return view('errors.expired');
        }

        // Se passou, mostra a tela de confirmação com os detalhes
        return view('confirm_secret', compact('secret'));
    }

    // Ação de Revelar
    public function reveal(Request $request)
    {
        // ... (código de validação de expiração igual ao anterior) ...
        $secret = Secret::where('hash', $request->hash)->first();

        if (!$secret || ($secret->expires_at && now()->greaterThan($secret->expires_at))) {
            return view('expired');
        }

        if ($secret->type === 'text') {
            $secret->increment('current_views');
            // ... lógica de deletar igual ao anterior ...
            $decrypted = $secret->content ? Crypt::decryptString($secret->content) : '';

            // Verificação de delete igual ao anterior
            $shouldDelete = false;
            if ($secret->current_views >= $secret->max_views) {
                $shouldDelete = true;
                $secret->delete();
            }

            return view('show_secret', [
                'type' => 'text',
                'message' => $decrypted,
                'secret' => $shouldDelete ? null : $secret,
                'sender' => $secret->sender, // Passando o sender
                'is_deleted' => $shouldDelete
            ]);
        } else {
            // Se for ARQUIVO, também tentamos descriptografar a mensagem (se houver)
            $message = null;
            if ($secret->content) {
                try {
                    $message = Crypt::decryptString($secret->content);
                } catch (\Exception $e) {
                }
            }

            return view('show_secret', [
                'type' => 'file',
                'fileName' => $secret->file_name,
                'message' => $message, // Passamos a mensagem junto com o arquivo
                'secret' => $secret,
                'sender' => $secret->sender, // Passando o sender
                'is_deleted' => false
            ]);
        }
    }

    public function downloadSecret($hash)
    {
        $secret = Secret::where('hash', $hash)->firstOrFail();

        // Incrementa view
        $secret->increment('current_views');

        // Verifica expiração por tempo antes de baixar
        if ($secret->expires_at && now()->greaterThan($secret->expires_at)) {
            // Opcional: Se quiser deletar se expirou antes de baixar
            return view('errors.expired');
        }

        // 1. Pega o caminho físico completo do arquivo (ex: C:\laragon\www\storage\app\secrets\...)
        if (!Storage::exists($secret->file_path)) {
            abort(404, 'Arquivo físico não encontrado.');
        }
        $fullPath = Storage::path($secret->file_path);

        // Verifica se deve deletar (Burn after reading)
        if ($secret->current_views >= $secret->max_views) {

            // Remove o registro do banco de dados
            $secret->delete();

            // 2. CORREÇÃO: Usa response()->download para ter acesso ao deleteFileAfterSend
            return response()->download($fullPath, $secret->file_name)->deleteFileAfterSend(true);
        }

        // Se ainda tem views sobrando, apenas baixa sem deletar o arquivo físico
        return response()->download($fullPath, $secret->file_name);
    }
}