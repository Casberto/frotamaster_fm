<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title text-xl">Editar Fornecedor: {{ $fornecedor->for_nome_fantasia }}</h2>
    </x-slot>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <form action="{{ route('fornecedores.update', $fornecedor) }}" method="POST">
                @csrf
                @method('PUT')
                @include('fornecedores._form')
            </form>
        </div>
    </div>
</x-app-layout>
