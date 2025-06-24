<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Cadastrar Novo Veículo</h2>
        </div>
    </x-slot>
    <form action="{{ route('veiculos.store') }}" method="POST">
        @csrf
        @include('veiculos._form')
    </form>
</x-app-layout>