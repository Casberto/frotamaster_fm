<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title text-xl">Editar Perfil</h2>
    </x-slot>
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <form action="{{ route('perfis.update', $perfi) }}" method="POST">
            @csrf
            @method('PUT')
            @include('perfis._form', ['perfil' => $perfi])
        </form>
    </div>
</x-app-layout>
