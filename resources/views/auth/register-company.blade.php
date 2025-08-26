<x-guest-layout>
    <div class="w-full min-h-screen flex flex-col justify-center sm:min-h-0 sm:h-auto sm:max-w-2xl px-6 py-8 bg-gray-800 sm:shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex flex-col items-center">
            <a href="/">
                <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="w-50 h-20">
            </a>
            <h1 class="text-white text-3xl font-bold mt-2">Cadastre sua Empresa</h1>
            <p class="text-gray-400 mt-1">Comece a gerenciar sua frota hoje mesmo.</p>
        </div>

        @if (session('error'))
            <div class="mt-4 bg-red-500 text-white p-4 rounded-md">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('company.store') }}" class="mt-6">
            @csrf
            
            {{-- Incluindo o formulário de empresa --}}
            @php $empresa = new \App\Models\Empresa(); @endphp
            @include('admin.empresas._form', ['empresa' => $empresa])

            <div class="text-center mt-6">
                <a class="underline text-sm text-gray-400 hover:text-gray-200" href="{{ route('login') }}">
                    Já possui uma conta? Acesse aqui
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>