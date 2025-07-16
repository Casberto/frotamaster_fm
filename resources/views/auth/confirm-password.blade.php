<x-guest-layout>
     <div class="w-full min-h-screen flex flex-col justify-center sm:min-h-0 sm:h-auto sm:max-w-md px-6 py-8 bg-gray-800 sm:shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex flex-col items-center mb-6">
            <a href="/">
                <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="w-50 h-20">
            </a>
        </div>
        
        <div class="mb-4 text-sm text-gray-400">
            Esta é uma área segura da aplicação. Por favor, confirme a sua senha antes de continuar.
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div>
                <x-input-label for="password" value="Senha" class="text-white"/>
                <x-text-input id="password" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-4">
                <x-primary-button>
                    Confirmar
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>