<x-guest-layout>
    <div class="w-full min-h-screen flex flex-col justify-center sm:min-h-0 sm:h-auto sm:max-w-md px-6 py-8 bg-gray-800 sm:shadow-md overflow-hidden sm:rounded-lg">
        
        <!-- Logo -->
        <div class="flex flex-col items-center">
            <a href="/">
                {{-- Logo carregado a partir da pasta public/img --}}
                <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="w-50 h-20">
            </a>
            <h1 class="text-white text-3xl font-bold mt-2">Frotamaster</h1>
            <p class="text-gray-400 mt-1">Por favor, insira seu e-mail e senha.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-6">
            @csrf
            <div>
                <x-input-label for="email" value="Email" class="text-white" />
                <x-text-input id="email" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="password" value="Senha" class="text-white" />
                <x-text-input id="password" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-500 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ml-2 text-sm text-gray-400">{{ __('Lembrar-me') }}</span>
                </label>
            </div>
            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-400 hover:text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        Esqueceu sua senha?
                    </a>
                @endif
            </div>
            <div class="flex items-center justify-center mt-6">
                <x-primary-button class="w-full justify-center text-lg">
                    Entrar
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>