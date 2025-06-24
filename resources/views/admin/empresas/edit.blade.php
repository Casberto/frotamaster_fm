<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Editar Empresa: {{ $empresa->nome_fantasia }}</h2>
        </div>
    </x-slot>
    <form action="{{ route('admin.empresas.update', $empresa) }}" method="POST">
        @csrf @method('PUT')
        @include('admin.empresas._form')
    </form>
</x-app-layout>