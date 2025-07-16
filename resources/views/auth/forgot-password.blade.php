<x-guest-layout>
    <div class="w-full min-h-screen flex flex-col justify-center sm:min-h-0 sm:h-auto sm:max-w-md px-6 py-8 bg-gray-800 sm:shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex flex-col items-center">
            <a href="/">
                <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="w-50 h-20">
            </a>
            <h1 class="text-white text-3xl font-bold mt-2">Recuperar Senha</h1>
        </div>

        <div class="mb-4 text-sm text-gray-400 text-center mt-4">
            Esqueceu sua senha? Sem problemas. Informe seu e-mail e enviaremos um link para você criar uma nova.
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="mt-6">
            @csrf
            <div>
                <x-input-label for="email" value="Email" class="text-white" />
                <x-text-input id="email" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="flex items-center justify-center mt-6">
                <x-primary-button class="w-full justify-center text-lg">
                    Enviar Link de Recuperação
                </x-primary-button>
            </div>
        </form>

        <div class="text-center mt-6">
            <a class="underline text-sm text-gray-400 hover:text-gray-200" href="{{ route('login') }}">
                Lembrou a senha? Voltar para o Login
            </a>
        </div>
    </div>
</x-guest-layout>
