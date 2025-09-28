<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cadastrar Novo Veículo
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="text-gray-900">
            <form method="POST" action="{{ route('veiculos.store') }}">
                @csrf
                {{-- Adicionamos a variável $veiculo como nula para a view de criação --}}
                @include('veiculos._form', ['veiculo' => null])
            </form>
        </div>
    </div>
</x-app-layout>

