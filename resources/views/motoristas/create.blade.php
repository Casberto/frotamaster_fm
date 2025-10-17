<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title text-xl">Cadastrar Novo Motorista</h2>
    </x-slot>

    <form method="POST" action="{{ route('motoristas.store') }}">
        @csrf
        @include('motoristas._form', ['motorista' => new \App\Models\Motorista()])
    </form>
</x-app-layout>
