<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Configuração Padrão') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.configuracoes-padrao.update', $configuracao) }}" method="POST">
                @method('PUT')
                @include('admin.configuracoes._form')
            </form>
        </div>
    </div>
</x-app-layout>

