<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center bg-white sm:bg-slate-900">
        
        <!-- Cabeçalho -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0 pt-8 sm:pt-0">
            <a href="/" class="flex justify-center mb-6">
                <img class="h-12 w-auto transition-all duration-300" src="{{ asset('img/logo.svg') }}" alt="Frotamaster">
            </a>
            <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-slate-900 sm:text-white">
                Área Segura
            </h2>
            <p class="mt-2 text-center text-sm leading-6 text-slate-500 sm:text-slate-400">
                Por favor, confirme sua senha antes de continuar.
            </p>
        </div>

        <!-- Área do Cartão -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-[480px] pb-12 sm:pb-0">
            <div class="bg-white px-6 py-10 sm:rounded-2xl sm:px-10 sm:shadow-2xl sm:shadow-black/20 sm:border sm:border-slate-100/10">

                <!-- Feedback de Erros -->
                @if ($errors->any())
                    <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-100">
                        <div class="flex">
                            <div class="flex-shrink-0 text-red-400">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Senha incorreta.</h3>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf

                    <!-- Senha -->
                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-slate-900">Senha</label>
                        <div class="mt-2 relative">
                            <input id="password" name="password" type="password" required autocomplete="current-password" autofocus
                                class="block w-full rounded-lg border-0 py-3 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 bg-slate-50/50 focus:bg-white transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Botão Confirmar -->
                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-lg bg-blue-600 px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200">
                            Confirmar
                        </button>
                    </div>
                </form>
            </div>
            
             <!-- Copyright -->
             <p class="mt-8 text-center text-xs leading-5 text-slate-400">
                &copy; {{ date('Y') }} Frotamaster.
            </p>
        </div>
    </div>
</x-guest-layout>