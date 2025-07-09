<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Editar Registro de Abastecimento</h2>
        </div>
    </x-slot>
    <form action="{{ route('abastecimentos.update', $abastecimento) }}" method="POST">
        @csrf
        @method('PUT')
        @include('abastecimentos._form')
    </form>
</x-app-layout>