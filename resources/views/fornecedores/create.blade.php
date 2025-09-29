<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title text-xl">Cadastrar Novo Fornecedor</h2>
    </x-slot>
    <form action="{{ route('fornecedores.store') }}" method="POST">
        @csrf
        @include('fornecedores._form')
    </form>
</x-app-layout>
