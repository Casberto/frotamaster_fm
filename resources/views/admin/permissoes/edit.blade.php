<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title text-xl">Editar Permissão</h2>
    </x-slot>
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <form action="{{ route('admin.permissoes.update', $permissao) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.permissoes._form', ['permissao' => $permissao])
        </form>
    </div>
</x-app-layout>
