<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cadastrar Novo Fornecedor') }}
        </h2>
    </x-slot>

    <style>
        @media (max-width: 640px) {
            .mobile-stacked-force {
                display: block !important;
            }
        }
    </style>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4 sm:py-6" 
    x-data="{ 
        tab: '{{ $errors->hasAny(['for_nome_fantasia', 'for_razao_social', 'for_cnpj_cpf', 'for_tipo', 'for_status']) ? 'geral' : ($errors->hasAny(['for_contato_telefone', 'for_contato_email', 'for_endereco']) ? 'contato' : ($errors->hasAny(['for_observacoes']) ? 'obs' : 'geral')) }}', 
        mobile: window.matchMedia('(max-width: 640px)').matches 
    }" 
    x-init="window.matchMedia('(max-width: 640px)').addEventListener('change', e => mobile = e.matches);"
    @invalid.capture.window="
        const target = $event.target;
        if (document.getElementById('tab-geral-content') && document.getElementById('tab-geral-content').contains(target)) {
            tab = 'geral';
        } else if (document.getElementById('tab-contato-content') && document.getElementById('tab-contato-content').contains(target)) {
            tab = 'contato';
        } else if (document.getElementById('tab-obs-content') && document.getElementById('tab-obs-content').contains(target)) {
            tab = 'obs';
        }
    ">
        
        {{-- Tab Navigation --}}
        <div class="mb-6 border-b border-gray-200 overflow-x-auto overflow-y-hidden no-scrollbar hidden sm:block">
            <nav class="-mb-px flex space-x-8 min-w-max px-4 sm:px-0" aria-label="Tabs">
                <button @click="tab = 'geral'" 
                    :class="tab === 'geral' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Dados Gerais
                </button>

                <button @click="tab = 'contato'" 
                    :class="tab === 'contato' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Contato e Endereço
                </button>

                <button @click="tab = 'obs'" 
                    :class="tab === 'obs' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Observações
                </button>
            </nav>
        </div>

        <form action="{{ route('fornecedores.store') }}" method="POST">
            @csrf
            @include('fornecedores._form')
        </form>
    </div>
</x-app-layout>
