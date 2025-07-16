<x-guest-layout>
    <div class="w-full min-h-screen flex flex-col justify-center sm:min-h-0 sm:h-auto sm:max-w-md px-6 py-8 bg-gray-800 sm:shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex flex-col items-center mb-6">
            <a href="/">
                <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="w-50 h-20">
            </a>
        </div>

        <div class="mb-4 text-sm text-gray-400">
            Obrigado por se inscrever! Antes de começar, poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar para você? Se não recebeu o e-mail, teremos o prazer de lhe enviar outro.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-400">
                Um novo link de verificação foi enviado para o endereço de e-mail que você forneceu durante o registro.
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between w-full">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-primary-button>
                    Reenviar E-mail de Verificação
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="underline text-sm text-gray-400 hover:text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Sair
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>