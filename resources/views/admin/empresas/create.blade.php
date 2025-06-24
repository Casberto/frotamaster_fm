<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Cadastrar Nova Empresa</h2>
        </div>
    </x-slot>
    <form action="{{ route('admin.empresas.store') }}" method="POST">
        @csrf
        @include('admin.empresas._form')
    </form>
</x-app-layout>
