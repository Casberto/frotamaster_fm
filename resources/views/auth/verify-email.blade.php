<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center bg-white sm:bg-slate-900">
        
        <!-- Cabeçalho -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0 pt-8 sm:pt-0">
            <a href="/" class="flex justify-center mb-6">
                <img class="h-12 w-auto transition-all duration-300" src="{{ asset('img/logo.svg') }}" alt="Frotamaster">
            </a>
            <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-slate-900 sm:text-white">
                Verifique seu e-mail
            </h2>
        </div>

        <!-- Área do Cartão -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-[480px] pb-12 sm:pb-0">
            <div class="bg-white px-6 py-10 sm:rounded-2xl sm:px-10 sm:shadow-2xl sm:shadow-black/20 sm:border sm:border-slate-100/10">
                
                <div class="mb-6 text-sm text-slate-600 leading-relaxed">
                    Obrigado por se inscrever! Antes de começar, precisamos que você verifique seu endereço de e-mail clicando no link que acabamos de enviar. Se não recebeu, podemos enviar outro.
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-100 text-sm font-medium text-green-700">
                        Um novo link de verificação foi enviado para o e-mail informado no cadastro.
                    </div>
                @endif

                <div class="flex flex-col space-y-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="flex w-full justify-center rounded-lg bg-blue-600 px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200">
                            Reenviar E-mail de Verificação
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full justify-center rounded-lg bg-white px-3 py-3 text-sm font-semibold leading-6 text-slate-700 border border-slate-300 shadow-sm hover:bg-slate-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-600 transition-all duration-200">
                            Sair
                        </button>
                    </form>
                </div>
            </div>

             <!-- Copyright -->
             <p class="mt-8 text-center text-xs leading-5 text-slate-400">
                &copy; {{ date('Y') }} Frotamaster.
            </p>
        </div>
    </div>
</x-guest-layout>