<x-guest-layout>
    <!-- Container Principal: 
         - Mobile: Fundo branco (bg-white), aparência de App.
         - Desktop: Fundo escuro (sm:bg-slate-900) para combinar com a sidebar e destacar o cartão.
    -->
    <div class="min-h-screen flex flex-col justify-center bg-white sm:bg-slate-900" x-data="{ showPassword: false, loading: false }">
        
        <!-- Cabeçalho (Logo + Título) -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
            <a href="/" class="flex justify-center mb-6">
                <!-- 
                    AJUSTE DE COR DO LOGO:
                    - 'invert': No mobile (fundo branco), inverte as cores da imagem (branco vira preto).
                    - 'sm:invert-0': No desktop (fundo escuro), remove a inversão para manter o logo original (branco).
                -->
                <img class="h-14 w-auto invert sm:invert-0 transition-all duration-300" src="{{ asset('img/logo.png') }}" alt="Frotamaster">
            </a>
            
            <!-- Texto adaptativo: Escuro no Mobile (fundo branco), Branco no Desktop (fundo escuro) -->
            <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-slate-900 sm:text-white">
                Bem-vindo de volta
            </h2>
            <p class="mt-2 text-center text-sm leading-6 text-slate-500 sm:text-slate-400">
                Entre para gerir a sua frota
            </p>
        </div>

        <!-- Área do Cartão / Formulário -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-[480px]">
            <!-- 
                Desktop: Cartão branco flutuando no fundo escuro.
                Mobile: Transparente (fundo branco da página).
            -->
            <div class="bg-white px-6 py-12 sm:rounded-2xl sm:px-12 sm:shadow-2xl sm:shadow-black/20 sm:border sm:border-slate-100/10">
                
                <!-- Feedback de Erros -->
                @if ($errors->any())
                    <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-100">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Credenciais inválidas</h3>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('login') }}" method="POST" @submit="loading = true">
                    @csrf

                    <!-- Input E-mail -->
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-slate-900">E-mail</label>
                        <div class="mt-2 relative">
                            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" 
                                class="block w-full rounded-lg border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all bg-slate-50/50 focus:bg-white"
                                placeholder="ex: joao@empresa.com">
                        </div>
                    </div>

                    <!-- Input Senha -->
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium leading-6 text-slate-900">Senha</label>
                            @if (Route::has('password.request'))
                                <div class="text-sm">
                                    <a href="{{ route('password.request') }}" class="font-semibold text-blue-600 hover:text-blue-500 transition-colors">
                                        Esqueceu a senha?
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="mt-2 relative">
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" autocomplete="current-password" required
                                class="block w-full rounded-lg border-0 py-3 pr-10 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all bg-slate-50/50 focus:bg-white"
                                placeholder="••••••••">
                            
                            <!-- Toggle Eye Icon -->
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none">
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Lembrar-me -->
                    <div class="flex items-center">
                        <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600 cursor-pointer">
                        <label for="remember-me" class="ml-3 block text-sm leading-6 text-slate-700 cursor-pointer">Lembrar-me neste dispositivo</label>
                    </div>

                    <!-- Botão Submit -->
                    <div>
                        <button type="submit" :disabled="loading" class="flex w-full justify-center rounded-lg bg-blue-600 px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed">
                            <svg x-show="loading" x-cloak class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="loading ? 'Validando...' : 'Entrar na Plataforma'"></span>
                        </button>
                    </div>
                </form>

                <!-- Divisor / Rodapé -->
                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm font-medium leading-6">
                            <span class="bg-white px-6 text-slate-500 sm:bg-white bg-white">Novo no Frotamaster?</span>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('company.register') }}" class="font-semibold text-blue-600 hover:text-blue-500 transition-colors">
                            Crie a sua conta empresarial &rarr;
                        </a>
                    </div>
                </div>
                
            </div>
            
            <!-- Copyright Discreto -->
            <p class="mt-8 text-center text-xs leading-5 text-slate-400">
                &copy; {{ date('Y') }} Frotamaster. Todos os direitos reservados.
            </p>
        </div>
    </div>
</x-guest-layout>