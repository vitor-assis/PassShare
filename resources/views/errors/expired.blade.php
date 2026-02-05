@extends('layout')

@section('title', 'Link Expirado')

@section('content')
<div class="bg-slate-800 p-8 rounded-xl shadow-2xl border border-slate-700 text-center animate-fade-in">
    
    <div class="w-20 h-20 bg-slate-700/50 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-600">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
        </svg>
    </div>

    <h1 class="text-2xl font-bold text-white mb-3">Link Indisponível</h1>
    
    <div class="space-y-2 text-slate-400 mb-8">
        <p>Este segredo não existe mais no nosso banco de dados.</p>
        <p class="text-sm bg-slate-900/50 p-3 rounded border border-slate-700/50 inline-block">
            Motivo: O limite de visualizações foi atingido ou o tempo de vida expirou.
        </p>
    </div>

    <a href="{{ route('secret.create') }}" class="inline-block w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-lg hover:shadow-blue-500/20">
        Criar novo segredo
    </a>
</div>
@endsection