@extends('layout')

@section('title', 'Criar Senha')

@section('content')

    @if(session('link_gerado'))
        <div class="bg-slate-800 p-8 rounded-xl shadow-2xl border border-green-500/30 text-center animate-fade-in">
            <div class="text-green-400 text-5xl mb-4">✓</div>
            <h2 class="text-2xl font-bold text-white mb-2">Link Pronto!</h2>
            <p class="text-slate-400 mb-6">Este link dá acesso direto ao segredo. Envie com cuidado.</p>

            <div class="relative mb-6">
                <input type="text" value="{{ session('link_gerado') }}" 
                    class="w-full p-4 bg-slate-950 border border-slate-700 rounded-lg text-green-400 font-mono text-center focus:outline-none focus:border-green-500 transition" 
                    readonly onclick="this.select(); navigator.clipboard.writeText(this.value);">
            </div>

            <a href="{{ route('secret.create') }}" class="inline-block bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-8 rounded-lg transition duration-200">
                Criar outro segredo
            </a>
        </div>

    @else

        <div class="bg-slate-800 rounded-xl shadow-2xl border border-slate-700 overflow-hidden max-w-2xl w-full mx-auto">
            
            <div class="flex border-b border-slate-700 bg-slate-900/50">
                <button type="button" onclick="switchTab('text')" id="tab-text" class="px-6 py-3 text-sm font-semibold text-blue-400 border-b-2 border-blue-500 bg-slate-800 focus:outline-none transition-all">
                    Senha / Texto
                </button>
                <button type="button" onclick="switchTab('file')" id="tab-file" class="px-6 py-3 text-sm font-semibold text-slate-500 hover:text-slate-300 focus:outline-none transition-all">
                    Arquivo
                </button>
            </div>

            <div class="p-6 md:p-8">
                <form action="{{ route('secret.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-6 relative">
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1 ml-1">Seu Nome / Identificação (Opcional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="sender" class="w-full pl-10 p-3 bg-slate-950 border border-slate-700 rounded-lg text-white placeholder-slate-600 focus:ring-2 focus:ring-blue-500 transition" placeholder="Ex: João da TI">
                        </div>
                    </div>

                    <div id="area-text">
                        <div class="mb-1">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Digite a senha ou texto que vai compartilhar</label>
                            <textarea id="mainMessage" name="message" rows="4" 
                                class="w-full p-4 bg-slate-950 border border-slate-700 rounded-lg text-white placeholder-slate-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none font-mono text-lg" 
                                placeholder="Sua senha ou texto secreto..." oninput="updateCounter(this)"></textarea>
                        </div>
                        <div class="text-right text-xs text-slate-500 mb-6">
                            Caracteres restantes: <span id="charCount">4096</span>
                        </div>

                        <div class="mb-8 border border-slate-600 rounded-lg p-4 bg-slate-900/30">
                            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                                <div class="text-center md:text-left">
                                    <h3 class="text-sm font-bold text-slate-200">Precisa de ajuda para gerar uma senha segura?</h3>
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button type="button" onclick="generatePass(true)" class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold py-2 px-4 rounded transition">Gerar senha</button>
                                    <button type="button" onclick="toggleOptions()" class="bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-bold py-2 px-4 rounded transition border border-slate-600">Opções</button>
                                </div>
                            </div>
                            <div id="generatorOptions" class="hidden mt-4 pt-4 border-t border-slate-700/50 animate-fade-in">
                                <div class="flex flex-wrap gap-4 items-center justify-center md:justify-start text-xs text-slate-300">
                                    <label class="flex items-center gap-2 cursor-pointer bg-slate-800 px-3 py-1.5 rounded border border-slate-700">
                                        <input type="checkbox" id="chkUps" checked class="accent-blue-500"> Maiúsculas
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer bg-slate-800 px-3 py-1.5 rounded border border-slate-700">
                                        <input type="checkbox" id="chkNum" checked class="accent-blue-500"> Números
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer bg-slate-800 px-3 py-1.5 rounded border border-slate-700">
                                        <input type="checkbox" id="chkSym" checked class="accent-blue-500"> Símbolos
                                    </label>
                                    <div class="flex items-center gap-2 bg-slate-800 px-3 py-1 rounded border border-slate-700">
                                        <span>Tamanho:</span>
                                        <input type="number" id="length" value="16" class="w-12 bg-transparent text-center focus:outline-none font-bold text-blue-400" min="4" max="50">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="area-file" class="hidden mb-8">
                        <label class="block text-sm font-medium text-slate-300 mb-2">1. Selecione o arquivo (Max 10MB)</label>
                        
                        <div class="border-2 border-dashed border-slate-600 rounded-lg p-8 text-center hover:bg-slate-700/30 transition bg-slate-900/50 relative group mb-4">
                            <input type="file" name="file" id="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="updateFileName(this)">
                            
                            <div class="pointer-events-none relative z-0">
                                <svg class="w-12 h-12 text-slate-400 mx-auto mb-3 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p id="fileNameDisplay" class="text-slate-400 text-sm font-medium">Clique ou arraste um arquivo aqui</p>
                                <p class="text-xs text-slate-500 mt-2">Documentos, Imagens, ZIP, etc.</p>
                            </div>
                        </div>

                        <div class="mb-1">
                            <label class="block text-sm font-medium text-slate-300 mb-2">2. Adicionar nota ou senha (Opcional)</label>
                            <textarea id="fileMessage" name="message" rows="2" disabled
                                class="w-full p-3 bg-slate-950 border border-slate-700 rounded-lg text-slate-300 placeholder-slate-600 focus:ring-2 focus:ring-blue-500 transition text-sm font-mono" 
                                placeholder="Ex: A senha para descompactar é 123..."></textarea>
                        </div>
                    </div>

                    <div class="bg-slate-900/50 rounded-lg p-5 border border-slate-700/50 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <div class="flex justify-between mb-2">
                                    <label class="text-xs font-bold text-slate-400 uppercase">Expira em:</label>
                                    <span id="labelDuration" class="text-sm font-bold text-blue-400">7 Dias</span>
                                </div>
                                <input type="range" min="0" max="3" value="2" step="1" 
                                    class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-blue-500"
                                    oninput="updateDuration(this.value)">
                                <input type="hidden" name="duration" id="inputDuration" value="10080">
                                <div class="flex justify-between text-[10px] text-slate-600 mt-1 px-1">
                                    <span>1h</span><span>1d</span><span>7d</span><span>∞</span>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-2">
                                    <label class="text-xs font-bold text-slate-400 uppercase">Ou após:</label>
                                    <span id="labelViews" class="text-sm font-bold text-amber-500">1 Visualização</span>
                                </div>
                                <input type="range" min="0" max="3" value="0" step="1" 
                                    class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-amber-600"
                                    oninput="updateViews(this.value)">
                                <input type="hidden" name="views" id="inputViews" value="1">
                                <div class="flex justify-between text-[10px] text-slate-600 mt-1 px-1">
                                    <span>1</span><span>5</span><span>10</span><span>50</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-center gap-4">
                        <button type="submit" class="bg-amber-600 hover:bg-amber-500 text-white font-bold py-3 px-12 rounded shadow-lg hover:shadow-amber-500/20 transition duration-200 w-full md:w-auto transform hover:-translate-y-0.5">
                            Gerar Link Seguro
                        </button>
                        <p class="text-[10px] text-slate-500 text-center max-w-md mt-2 leading-relaxed">
                            Criptografia ponta-a-ponta. Os dados são apagados permanentemente após expirarem.
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // --- Lógica das Abas ---
            function switchTab(tab) {
                const activeClass = ['text-blue-400', 'border-b-2', 'border-blue-500', 'bg-slate-800'];
                const inactiveClass = ['text-slate-500'];
                
                const tabText = document.getElementById('tab-text');
                const tabFile = document.getElementById('tab-file');
                const areaText = document.getElementById('area-text');
                const areaFile = document.getElementById('area-file');
                
                // Inputs de mensagem
                const mainMessage = document.getElementById('mainMessage');
                const fileMessage = document.getElementById('fileMessage');

                if (tab === 'text') {
                    areaText.classList.remove('hidden');
                    areaFile.classList.add('hidden');
                    
                    // Estilização
                    tabText.classList.add(...activeClass);
                    tabText.classList.remove(...inactiveClass);
                    tabFile.classList.remove(...activeClass);
                    tabFile.classList.add(...inactiveClass);
                    
                    // Habilita o texto principal e desabilita o texto do arquivo
                    // (Isso garante que o backend receba o valor correto na variável 'message')
                    mainMessage.disabled = false;
                    fileMessage.disabled = true;
                    
                    // Limpa input de arquivo
                    document.getElementById('fileInput').value = '';
                    document.getElementById('fileNameDisplay').innerText = "Clique ou arraste um arquivo aqui";
                    document.getElementById('fileNameDisplay').className = "text-slate-400 text-sm font-medium";
                } else {
                    areaText.classList.add('hidden');
                    areaFile.classList.remove('hidden');
                    
                    // Estilização
                    tabFile.classList.add(...activeClass);
                    tabFile.classList.remove(...inactiveClass);
                    tabText.classList.remove(...activeClass);
                    tabText.classList.add(...inactiveClass);
                    
                    // Desabilita texto principal, habilita nota do arquivo
                    mainMessage.disabled = true;
                    fileMessage.disabled = false;
                }
            }

            // --- Lógica de Upload ---
            function updateFileName(input) {
                if(input.files && input.files[0]) {
                    const display = document.getElementById('fileNameDisplay');
                    display.innerText = "Selecionado: " + input.files[0].name;
                    display.className = "text-blue-400 font-bold text-sm";
                }
            }

            // --- Sliders ---
            const durationMap = { 0: { label: '1 Hora', value: 60 }, 1: { label: '1 Dia', value: 1440 }, 2: { label: '7 Dias', value: 10080 }, 3: { label: '-', value: 0 } };
            const viewsMap = { 0: { label: '1 Visualização', value: 1 }, 1: { label: '5 Visualizações', value: 5 }, 2: { label: '10 Visualizações', value: 10 }, 3: { label: '50 Visualizações', value: 50 } };

            function updateDuration(index) {
                document.getElementById('labelDuration').innerText = durationMap[index].label;
                document.getElementById('inputDuration').value = durationMap[index].value;
            }
            function updateViews(index) {
                document.getElementById('labelViews').innerText = viewsMap[index].label;
                document.getElementById('inputViews').value = viewsMap[index].value;
            }

            // --- Gerador ---
            function updateCounter(el) { document.getElementById('charCount').innerText = 4096 - el.value.length; }
            function toggleOptions() { document.getElementById('generatorOptions').classList.toggle('hidden'); }
            
            function generatePass(fillImmediately = false) {
                const length = document.getElementById('length').value;
                const charset = { upper: "ABCDEFGHIJKLMNOPQRSTUVWXYZ", lower: "abcdefghijklmnopqrstuvwxyz", number: "0123456789", symbol: "!@#$%^&*()_+~`|}{[]:;?><,./-=" };
                let chars = charset.lower;
                if(document.getElementById('chkUps').checked) chars += charset.upper;
                if(document.getElementById('chkNum').checked) chars += charset.number;
                if(document.getElementById('chkSym').checked) chars += charset.symbol;
                
                let result = "";
                for (let i = 0; i < length; i++) result += chars.charAt(Math.floor(Math.random() * chars.length));
                
                if(fillImmediately) {
                    const input = document.getElementById('mainMessage');
                    // Se estivermos na aba de arquivo, não preenchemos automaticamente o campo principal
                    if(!input.disabled) {
                        input.value = result;
                        updateCounter(input);
                        input.classList.add('bg-blue-900/20');
                        setTimeout(() => input.classList.remove('bg-blue-900/20'), 300);
                    }
                }
            }
        </script>
    @endif
@endsection