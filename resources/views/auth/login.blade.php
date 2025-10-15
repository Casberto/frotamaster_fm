<x-guest-layout>
    <div class="w-full sm:max-w-md bg-white shadow-2xl rounded-2xl overflow-hidden">
        <!-- Cabeçalho Azul -->
        <div class="bg-blue-600 px-6 py-8 text-center">
            <a href="/" class="inline-block mb-4">
                <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="h-16 w-auto mx-auto">
            </a>
            <h1 class="text-2xl font-bold text-white mt-4">Bem-vindo de volta</h1>
            <p class="text-blue-200 mt-1 text-sm">Acesse sua conta Frotamaster</p>
        </div>

        <!-- Formulário -->
        <div class="p-6 sm:p-8">
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- E-mail -->
                <div>
                    <x-input-label for="email" value="E-mail" class="font-semibold text-gray-700" />
                    <div class="relative mt-2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                           <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                           </svg>
                        </span>
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="seu@email.com" class="w-full pl-10 py-3 text-base"/>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Senha -->
                <div>
                    <x-input-label for="password" value="Senha" class="font-semibold text-gray-700" />
                     <div class="relative mt-2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                           <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                           </svg>
                        </span>
                        <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••••" class="w-full pl-10 py-3 text-base"/>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Opções -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600 group-hover:text-black">{{ __('Lembrar-me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-blue-600 hover:text-blue-800" href="{{ route('password.request') }}">
                            Esqueceu sua senha?
                        </a>
                    @endif
                </div>

                <!-- Botão Entrar -->
                <div>
                    <x-primary-button class="w-full justify-center text-base py-3 group">
                        <span>Entrar na Plataforma</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </x-primary-button>
                </div>

                <!-- Divisor -->
                <div class="flex items-center justify-center">
                    <span class="text-xs text-gray-400">Primeira vez aqui?</span>
                </div>

                <!-- Botão Criar Conta -->
                <a href="{{ route('company.register') }}" class="w-full flex justify-center items-center text-sm py-3 px-4 border border-gray-300 rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition-colors group">
                    <span>Criar nova conta</span>
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </form>
        </div>
        
        <!-- Footer do Card -->
        <div class="px-6 py-4 bg-gray-50 border-t text-center text-xs text-gray-500">
            Ao entrar, você concorda com nossos <a href="#" class="underline hover:text-blue-600">Termos de Serviço</a> e <a href="#" class="underline hover:text-blue-600">Política de Privacidade</a>.
        </div>
    </div>
</x-guest-layout>

