<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Veículo: {{ $veiculo->placa_modelo }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="text-gray-900">
            <form method="POST" action="{{ route('veiculos.update', $veiculo->vei_id) }}">
                @csrf
                @method('PUT')
                @include('veiculos._form', ['veiculo' => $veiculo])
            </form>
        </div>
    </div>
</x-app-layout>

