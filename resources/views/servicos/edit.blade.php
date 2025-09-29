<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title text-xl">Editar Serviço</h2>
    </x-slot>
    <form action="{{ route('servicos.update', $servico) }}" method="POST">
        @csrf
        @method('PUT')
        @include('servicos._form')
    </form>
</x-app-layout>
