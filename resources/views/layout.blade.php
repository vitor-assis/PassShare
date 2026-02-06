<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PassShare - @yield('title', __('Compartilhe com Segurança'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-900 text-slate-200 flex flex-col min-h-screen">

    <header class="w-full p-6 text-center border-b border-slate-800">
        <a href="{{ route('secret.create') }}" class="text-2xl font-bold tracking-tight hover:text-white transition">
            Pass<span class="text-blue-500">Share</span> 🔒
        </a>
    </header>

    <main class="flex-grow flex items-center justify-center p-4">
        <div class="w-full max-w-lg">
            @yield('content')
        </div>
    </main>

    <footer class="text-center p-6 text-slate-500 text-sm border-t border-slate-800 flex flex-col gap-4">
        <div>
            &copy; {{ date('Y') }} PassShare. {{ __('Criptografado e descartável') }}
        </div>
        <div class="flex justify-center gap-4 text-xs font-bold uppercase tracking-wider">
            <a href="/lang/pt"
                class="{{ app()->getLocale() == 'pt' ? 'text-blue-400' : 'text-slate-600 hover:text-slate-400' }}">PT</a>
            <span class="text-slate-700">|</span>
            <a href="/lang/en"
                class="{{ app()->getLocale() == 'en' ? 'text-blue-400' : 'text-slate-600 hover:text-slate-400' }}">EN</a>
            <span class="text-slate-700">|</span>
            <a href="/lang/es"
                class="{{ app()->getLocale() == 'es' ? 'text-blue-400' : 'text-slate-600 hover:text-slate-400' }}">ES</a>
        </div>
    </footer>

    <script>
        function copyToClipboard(text, btnElement) {
            navigator.clipboard.writeText(text);
            const originalText = btnElement.innerText;
            btnElement.innerText = "{{ __('Copiado!') }} ✓";
            btnElement.classList.add('bg-green-600', 'text-white');
            setTimeout(() => {
                btnElement.innerText = originalText;
                btnElement.classList.remove('bg-green-600', 'text-white');
            }, 2000);
        }
    </script>
</body>

</html>