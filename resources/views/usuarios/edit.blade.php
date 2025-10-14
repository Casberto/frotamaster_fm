<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title text-xl">Editar Usuário</h2>
    </x-slot>
    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')
        @include('usuarios._form')
    </form>
</x-app-layout>

