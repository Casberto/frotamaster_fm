<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title text-xl">Novo Perfil</h2>
    </x-slot>
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <form action="{{ route('perfis.store') }}" method="POST">
            @csrf
            @include('perfis._form', ['perfil' => new \App\Models\Perfil()])
        </form>
    </div>
</x-app-layout>
