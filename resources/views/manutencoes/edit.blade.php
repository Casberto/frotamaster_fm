<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Editar Manutenção</h2>
        </div>
    </x-slot>
    <form action="{{ route('manutencoes.update', $manutencao) }}" method="POST">
        @csrf
        @method('PUT')
        @include('manutencoes._form')
    </form>
</x-app-layout>