<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title text-xl">Editar Motorista: {{ $motorista->mot_nome }}</h2>
    </x-slot>

    <form method="POST" action="{{ route('motoristas.update', $motorista) }}">
        @csrf
        @method('PUT')
        @include('motoristas._form', ['motorista' => $motorista])
    </form>
</x-app-layout>
