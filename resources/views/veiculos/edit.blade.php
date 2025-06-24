<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Editar Veículo: {{ $veiculo->placa }}</h2>
        </div>
    </x-slot>
    <form action="{{ route('veiculos.update', $veiculo) }}" method="POST">
        @csrf @method('PUT')
        @include('veiculos._form')
    </form>
</x-app-layout>