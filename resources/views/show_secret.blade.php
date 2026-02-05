@extends('layout')

@section('title', 'Segredo Revelado')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-2xl border border-slate-700 overflow-hidden">
    
    <div class="bg-slate-950/50 p-4 border-b border-slate-700 flex justify-between items-center">
        <div>
            <h2 class="font-semibold text-slate-200 leading-tight">
                {{ isset($type) && $type === 'file' ? 'Arquivo Seguro' : 'Conteúdo' }}
            </h2>
            @if(isset($sender) && $sender)
                <p class="text-xs text-slate-400 mt-1">
                    Enviado por: <span class="text-blue-400 font-bold">{{ $sender }}</span>
                </p>
            @endif
        </div>
        
        <div id="status-badge">
            @if($is_deleted)
                <span class="bg-red-500/20 text-red-400 text-xs px-2 py-1 rounded border border-red-500/30">Deletado</span>
            @else
                <span class="bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded border border-green-500/30">Ativo</span>
            @endif
        </div>
    </div>

    <div class="p-8 text-center">
        
        @if(isset($type) && $type === 'file')
            @if(isset($message) && $message)
                <div class="mb-6 bg-slate-900/50 border border-slate-600 rounded-lg p-4 text-left relative group shadow-inner">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Nota do remetente:</p>
                        <button onclick="copyToClipboard('{{ $message }}', this)" class="text-[10px] bg-slate-700 hover:bg-slate-600 text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Copiar Nota</button>
                    </div>
                    <pre class="text-slate-200 font-mono text-sm whitespace-pre-wrap break-all">{{ $message }}</pre>
                </div>
            @endif

            <div class="mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-blue-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                
                <h3 class="text-xl text-white font-bold mb-2 break-all">{{ $fileName }}</h3>
                
                @php $remaining = $secret->max_views - $secret->current_views; @endphp
                
                @if($remaining > 1)
                    <p class="text-blue-400 text-sm font-medium bg-blue-900/20 p-2 rounded border border-blue-900/50 inline-block">
                        Downloads restantes: <strong>{{ $remaining }}</strong>
                    </p>
                @else
                    <p class="text-amber-500 text-sm font-medium bg-amber-900/20 p-2 rounded border border-amber-900/50 inline-block">
                        ⚠️ Último download permitido
                    </p>
                @endif
            </div>

            @if(!$is_deleted)
                <div id="download-area">
                    <a href="{{ route('secret.download', $secret->hash) }}" 
                       id="btn-download"
                       onclick="startDownload(this, {{ $secret->current_views }}, {{ $secret->max_views }})"
                       class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-green-500/20 transition transform hover:-translate-y-1 cursor-pointer">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        <span>Baixar Arquivo</span>
                    </a>
                </div>

                <div id="success-feedback" class="hidden mt-4 animate-fade-in text-green-400">
                    <div class="flex flex-col items-center">
                        <div class="rounded-full bg-green-500/20 p-2 mb-2">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <h3 class="font-bold">Download Iniciado!</h3>
                        <p class="text-slate-500 text-xs">Verifique sua pasta de downloads.</p>
                        
                        <div id="destruction-msg" class="hidden mt-3 text-red-400 text-xs font-bold bg-red-900/20 p-2 rounded border border-red-900/50">
                            Arquivo excluído do servidor permanentemente.
                        </div>
                    </div>
                </div>
            @endif

        @else
            <div class="relative group text-left">
                <pre class="bg-black p-6 rounded-lg text-green-400 font-mono text-lg break-all border border-slate-700 whitespace-pre-wrap">{{ $message }}</pre>
                
                <button onclick="copyToClipboard('{{ $message }}', this)" class="absolute top-2 right-2 bg-slate-700 hover:bg-slate-600 text-xs text-white px-3 py-1 rounded transition opacity-0 group-hover:opacity-100 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    Copiar
                </button>
            </div>
        @endif


        @if($is_deleted)
            <div class="mt-6 flex items-start gap-3 p-4 bg-red-900/20 border border-red-900/50 rounded-lg text-left animate-fade-in">
                <div class="text-red-500 text-xl">🔥</div>
                <div>
                    <h3 class="text-red-400 font-bold text-sm">Este segredo foi autodestruído</h3>
                    <p class="text-red-300/70 text-xs mt-1">Esta foi a última visualização permitida ou o tempo expirou.</p>
                </div>
            </div>
        @else
            <div id="stats-footer" class="mt-8 grid grid-cols-2 gap-4 border-t border-slate-700/50 pt-6 transition-all duration-500">
                <div class="bg-slate-700/30 p-3 rounded-lg text-center border border-slate-700">
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Restam</p>
                    <p class="text-2xl font-bold text-white">
                        {{ $secret->max_views - $secret->current_views }} 
                        <span class="text-sm font-normal text-slate-500">
                            {{ isset($type) && $type === 'file' ? 'downloads' : 'views' }}
                        </span>
                    </p>
                </div>
                <div class="bg-slate-700/30 p-3 rounded-lg text-center border border-slate-700">
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Expira em</p>
                    <p class="text-sm font-bold text-white mt-2">
                        {{ $secret->expires_at ? \Carbon\Carbon::parse($secret->expires_at)->diffForHumans() : '∞' }}
                    </p>
                </div>
            </div>
        @endif

    </div>

    <div class="p-4 bg-slate-950/30 border-t border-slate-700 text-center">
        <a href="{{ route('secret.create') }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium transition">← Criar novo segredo</a>
    </div>
</div>

<script>
    function startDownload(btn, currentViews, maxViews) {
        // 1. Feedback visual imediato: Botão desabilita
        btn.classList.add('opacity-50', 'pointer-events-none');
        btn.querySelector('span').innerText = 'Baixando...';

        // 2. Calcula se será o último download (Current + este clique >= Max)
        const isLastDownload = (currentViews + 1) >= maxViews;

        // 3. Aguarda 1.5s para trocar a interface
        setTimeout(() => {
            // Esconde botão, mostra sucesso
            document.getElementById('download-area').classList.add('hidden');
            document.getElementById('success-feedback').classList.remove('hidden');

            // SE for o último, atualiza a tela para parecer "Deletado"
            if (isLastDownload) {
                // Troca badge
                const badge = document.getElementById('status-badge');
                if(badge) badge.innerHTML = '<span class="bg-red-500/20 text-red-400 text-xs px-2 py-1 rounded border border-red-500/30 animate-pulse">Deletado</span>';
                
                // Remove estatísticas
                const footer = document.getElementById('stats-footer');
                if(footer) footer.style.display = 'none';

                // Mostra mensagem de destruição
                document.getElementById('destruction-msg').classList.remove('hidden');
            }
        }, 1500);
    }
</script>
@endsection