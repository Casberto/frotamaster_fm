<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title text-xl">Cadastrar Novo Serviço</h2>
    </x-slot>
    <form action="{{ route('servicos.store') }}" method="POST">
        @csrf
        @include('servicos._form')
    </form>
</x-app-layout>
