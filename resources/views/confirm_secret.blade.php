@extends('layout')

@section('title', __('Confirmar Acesso'))

@section('content')
    <div class="bg-slate-800 p-8 rounded-xl shadow-2xl border border-slate-700 text-center">

        <div class="w-16 h-16 bg-yellow-500/20 text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-white mb-4">{{ __('Você recebeu um segredo') }}</h2>

        <p class="text-slate-400 mb-8 leading-relaxed">
            {{ __('Este link contém uma informação segura.') }} <br>
            {{ __('Ao visualizar, você consumirá') }} <strong>1 {{ __('acesso') }}</strong> {{ __('do limite total.') }}
        </p>

        <form action="{{ route('secret.reveal') }}" method="POST">
            @csrf
            <input type="hidden" name="hash" value="{{ $secret->hash }}">

            <button type="submit"
                class="w-full bg-yellow-600 hover:bg-yellow-500 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-lg hover:shadow-yellow-500/20">
                {{ __('Exibir conteúdo') }}
            </button>
        </form>
    </div>
@endsection