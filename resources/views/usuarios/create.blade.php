<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title text-xl">Cadastrar Novo Usuário</h2>
    </x-slot>
    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf
        @include('usuarios._form', ['usuario' => null])
    </form>
</x-app-layout>

