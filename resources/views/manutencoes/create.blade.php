<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Registrar Nova Manutenção</h2>
        </div>
    </x-slot>
    <form action="{{ route('manutencoes.store') }}" method="POST">
        @csrf
        @include('manutencoes._form')
    </form>
</x-app-layout>