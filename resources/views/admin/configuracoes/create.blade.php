<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Configuração Padrão') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.configuracoes-padrao.store') }}" method="POST">
                @include('admin.configuracoes._form', ['configuracao' => new \App\Models\ConfiguracaoPadrao()])
            </form>
        </div>
    </div>
</x-app-layout>

