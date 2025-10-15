<x-guest-layout>
    <div class="w-full sm:max-w-md bg-white shadow-2xl rounded-2xl overflow-hidden">
        <!-- Cabeçalho Azul -->
        <div class="bg-blue-600 px-6 py-8 text-center">
            <a href="/" class="inline-block mb-4">
                <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="h-16 w-auto mx-auto">
            </a>
            <h1 class="text-2xl font-bold text-white mt-4">Recuperar Senha</h1>
            <p class="text-blue-200 mt-1 text-sm px-4">Informe seu e-mail e enviaremos um link para criar uma nova senha.</p>
        </div>

        <!-- Formulário -->
        <div class="p-6 sm:p-8">
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- E-mail -->
                <div>
                    <x-input-label for="email" value="E-mail" class="font-semibold text-gray-700"/>
                     <div class="relative mt-2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                           <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                           </svg>
                        </span>
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="seu@email.com" class="w-full pl-10 py-3 text-base"/>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-primary-button class="w-full justify-center text-base py-3">
                        Enviar Link de Recuperação
                    </x-primary-button>
                </div>
            </form>

            <div class="text-center mt-6">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    Lembrou a senha? Voltar para o Login
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>

