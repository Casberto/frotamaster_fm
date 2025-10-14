<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title text-xl">Nova Permissão</h2>
    </x-slot>
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <form action="{{ route('admin.permissoes.store') }}" method="POST">
            @csrf
            @include('admin.permissoes._form', ['permissao' => new \App\Models\Permissao()])
        </form>
    </div>
</x-app-layout>
