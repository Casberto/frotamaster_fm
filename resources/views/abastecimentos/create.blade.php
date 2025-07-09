<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Registrar Novo Abastecimento</h2>
        </div>
    </x-slot>
    <form action="{{ route('abastecimentos.store') }}" method="POST">
        @csrf
        @include('abastecimentos._form')
    </form>
</x-app-layout>
