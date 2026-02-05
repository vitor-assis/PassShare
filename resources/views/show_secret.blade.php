@extends('layout')

@section('title', 'Segredo Revelado')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-2xl border border-slate-700 overflow-hidden">
    
    <div class="bg-slate-950/50 p-4 border-b border-slate-700 flex justify-between items-start">
        <div>
            <h2 class="font-semibold text-slate-200 leading-tight">
                {{ isset($type) && $type === 'file' ? 'Arquivo Seguro' : 'Conteúdo' }}
            </h2>
            @if(isset($sender) && $sender)
                <div class="flex items-center gap-2 mt-1">
                    <div class="w-5 h-5 rounded-full bg-blue-600/20 flex items-center justify-center text-[10px] text-blue-400 font-bold">
                        {{ substr($sender, 0, 1) }}
                    </div>
                    <p class="text-xs text-slate-400">
                        Enviado por <span class="text-slate-200 font-medium">{{ $sender }}</span>
                    </p>
                </div>
            @endif
        </div>
        
        <div id="status-badge">
            @if($is_deleted)
                <span class="bg-red-500/10 text-red-400 text-[10px] font-bold px-2 py-1 rounded border border-red-500/20 uppercase tracking-wide">Deletado</span>
            @else
                <span class="bg-green-500/10 text-green-400 text-[10px] font-bold px-2 py-1 rounded border border-green-500/20 uppercase tracking-wide">Ativo</span>
            @endif
        </div>
    </div>

    <div class="p-6 md:p-8 text-center">
        
        @if(isset($type) && $type === 'file')
            @if(isset($message) && $message)
                <div class="mb-6 bg-slate-700/30 border-l-4 border-blue-500 rounded-r-lg p-4 text-left relative group">
                    <div class="flex justify-between items-start mb-1">
                        <p class="text-[10px] text-blue-400 font-bold uppercase tracking-wider">Nota do remetente</p>
                        <button onclick="copyToClipboard('{{ $message }}', this)" class="text-[10px] text-slate-500 hover:text-white transition opacity-50 group-hover:opacity-100 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" /><path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" /></svg> Copiar
                        </button>
                    </div>
                    <p class="text-slate-300 text-sm whitespace-pre-wrap leading-relaxed">{{ $message }}</p>
                </div>
            @endif

            <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-6 relative overflow-hidden">
                <svg class="absolute -right-6 -bottom-6 w-32 h-32 text-slate-800/50 transform rotate-12 pointer-events-none" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9l-7-7z" /></svg>

                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-16 h-16 bg-slate-800 rounded-lg flex items-center justify-center border border-slate-700 shadow-lg mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    
                    <h3 class="text-lg text-white font-semibold mb-1 break-all px-4">{{ $fileName }}</h3>
                    
                    @php $remaining = $secret->max_views - $secret->current_views; @endphp
                    <div id="limit-info" class="mb-6 h-6"> @if($remaining > 1)
                            <span class="text-xs text-slate-400 bg-slate-800 px-2 py-1 rounded-full border border-slate-700">
                                Restam <strong>{{ $remaining }}</strong> downloads
                            </span>
                        @else
                            <span class="text-xs text-amber-500 bg-amber-900/10 px-2 py-1 rounded-full border border-amber-500/20 font-medium animate-pulse">
                                ⚠️ Último download permitido
                            </span>
                        @endif
                    </div>

                    @if(!$is_deleted)
                        <div id="download-area" class="w-full max-w-xs">
                            <a href="{{ route('secret.download', $secret->hash) }}" 
                               id="btn-download"
                               onclick="startDownload(this, {{ $secret->current_views }}, {{ $secret->max_views }})"
                               class="group w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-blue-500/25 transition-all duration-200 transform hover:-translate-y-0.5 cursor-pointer">
                                <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                <span>Baixar Arquivo</span>
                            </a>
                        </div>

                        <div id="success-feedback" class="hidden w-full max-w-xs bg-green-500/10 border border-green-500/20 rounded-lg p-4 animate-fade-in">
                            <div class="flex items-center justify-center gap-3 text-green-400">
                                <svg class="h-6 w-6 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <div class="text-left">
                                    <h3 class="font-bold text-sm">Download Iniciado</h3>
                                    <p class="text-[10px] text-green-400/70">Verifique sua pasta de downloads</p>
                                </div>
                            </div>
                            <div id="destruction-msg" class="hidden mt-3 pt-3 border-t border-green-500/20 text-center">
                                <p class="text-xs text-red-400 font-bold bg-red-950/30 py-1 px-2 rounded">
                                    Arquivo excluído do servidor.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        @else
            <div class="relative group text-left">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-slate-950 p-6 rounded-lg border border-slate-700">
                    <pre class="text-green-400 font-mono text-lg break-all whitespace-pre-wrap">{{ $message }}</pre>
                    <button onclick="copyToClipboard('{{ $message }}', this)" class="absolute top-2 right-2 bg-slate-800 hover:bg-slate-700 text-xs text-slate-300 hover:text-white px-3 py-1 rounded border border-slate-700 transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        Copiar
                    </button>
                </div>
            </div>
        @endif


        @if($is_deleted)
            <div class="mt-6 flex items-start gap-3 p-4 bg-red-950/30 border border-red-500/20 rounded-lg text-left animate-fade-in">
                <div class="text-red-500 text-xl">🔥</div>
                <div>
                    <h3 class="text-red-400 font-bold text-sm">Autodestruído</h3>
                    <p class="text-red-400/60 text-xs mt-1">Este link não é mais válido.</p>
                </div>
            </div>
        @else
            <div id="stats-footer" class="mt-8 grid grid-cols-2 gap-4 border-t border-slate-700/50 pt-6 transition-all duration-500">
                <div class="bg-slate-800/50 p-3 rounded-lg text-center border border-slate-700/50">
                    <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Restam</p>
                    <p class="text-xl font-bold text-slate-200">
                        {{ $secret->max_views - $secret->current_views }} 
                        <span class="text-xs font-normal text-slate-500">
                            {{ isset($type) && $type === 'file' ? 'downloads' : 'views' }}
                        </span>
                    </p>
                </div>
                <div class="bg-slate-800/50 p-3 rounded-lg text-center border border-slate-700/50">
                    <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Expira em</p>
                    <p class="text-sm font-bold text-slate-200 mt-1">
                        {{ $secret->expires_at ? \Carbon\Carbon::parse($secret->expires_at)->diffForHumans() : 'Nunca' }}
                    </p>
                </div>
            </div>
        @endif

    </div>

    <div class="p-4 bg-slate-950/30 border-t border-slate-700 text-center">
        <a href="{{ route('secret.create') }}" class="text-blue-500 hover:text-blue-400 text-sm font-medium transition hover:underline">Criar novo segredo</a>
    </div>
</div>

<script>
    function startDownload(btn, currentViews, maxViews) {
        // 1. Feedback visual imediato
        btn.classList.add('opacity-50', 'pointer-events-none');
        btn.querySelector('span').innerText = 'Baixando...';

        // 2. Calcula se será o último download
        const isLastDownload = (currentViews + 1) >= maxViews;

        // 3. Aguarda delay para simular inicio e troca interface
        setTimeout(() => {
            document.getElementById('download-area').classList.add('hidden');
            document.getElementById('success-feedback').classList.remove('hidden');

            // SE for o último download
            if (isLastDownload) {
                // Muda Status Topo
                const badge = document.getElementById('status-badge');
                if(badge) badge.innerHTML = '<span class="bg-red-500/10 text-red-400 text-[10px] font-bold px-2 py-1 rounded border border-red-500/20 uppercase tracking-wide animate-pulse">Deletado</span>';
                
                // Remove Footer de Estatísticas
                const footer = document.getElementById('stats-footer');
                if(footer) footer.style.display = 'none';

                // *** IMPORTANTE: Remove o aviso de "Último download" dentro do card ***
                const limitInfo = document.getElementById('limit-info');
                if(limitInfo) limitInfo.style.display = 'none';

                // Mostra mensagem de destruição dentro do feedback
                document.getElementById('destruction-msg').classList.remove('hidden');
            }
        }, 1500);
    }
</script>
@endsection