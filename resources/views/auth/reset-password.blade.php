<x-guest-layout>
    <div class="w-full min-h-screen flex flex-col justify-center sm:min-h-0 sm:h-auto sm:max-w-md px-6 py-8 bg-gray-800 sm:shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex flex-col items-center">
            <a href="/">
                <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="w-50 h-20">
            </a>
            <h1 class="text-white text-3xl font-bold mt-2">Redefinir Senha</h1>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="mt-6">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            
            <div>
                <x-input-label for="email" value="Email" class="text-white" />
                <x-text-input id="email" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="password" value="Nova Senha" class="text-white" />
                <x-text-input id="password" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="password_confirmation" value="Confirme a Nova Senha" class="text-white" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            <div class="flex items-center justify-center mt-6">
                <x-primary-button class="w-full justify-center text-lg">
                    Redefinir Senha
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>