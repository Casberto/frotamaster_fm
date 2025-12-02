<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cadastrar Novo Motorista') }}
        </h2>
    </x-slot>

    <style>
        @media (max-width: 640px) {
            .mobile-stacked-force {
                display: block !important;
            }
        }
    </style>

    @php
        $showTabDocs = false;
        $docsFields = ['usar_cpf', 'usar_rg', 'usar_orgao_emissor_rg', 'usar_data_emissao_rg', 'usar_pis', 'usar_ctps_numero', 'usar_ctps_serie', 'usar_titulo_eleitor', 'usar_zona_eleitoral', 'usar_secao_eleitoral', 'exige_cnh'];
        foreach ($docsFields as $field) {
            if ($configuracoes[$field] ?? false) {
                $showTabDocs = true;
                break;
            }
        }

        $showTabProfissional = false;
        $profFields = ['usar_data_admissao', 'usar_data_demissao', 'usar_tipo_contrato', 'usar_categoria_profissional', 'usar_matricula_interna', 'usar_banco', 'usar_observacoes'];
        foreach ($profFields as $field) {
            if ($configuracoes[$field] ?? false) {
                $showTabProfissional = true;
                break;
            }
        }
    @endphp

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4 sm:py-6" 
    x-data="{ 
        tab: '{{ $errors->hasAny(['mot_nome', 'mot_status', 'mot_user_id', 'mot_apelido', 'mot_data_nascimento', 'mot_genero', 'mot_nacionalidade', 'mot_estado_civil', 'mot_nome_mae', 'mot_nome_pai', 'mot_email', 'mot_telefone1', 'mot_telefone2', 'mot_cep', 'mot_endereco', 'mot_numero', 'mot_complemento', 'mot_bairro', 'mot_cidade', 'mot_estado']) ? 'geral' : ($errors->hasAny(['mot_cpf', 'mot_rg', 'mot_orgao_emissor_rg', 'mot_data_emissao_rg', 'mot_pis', 'mot_ctps_numero', 'mot_ctps_serie', 'mot_titulo_eleitor', 'mot_zona_eleitoral', 'mot_secao_eleitoral', 'mot_cnh_numero', 'mot_cnh_categoria', 'mot_cnh_data_emissao', 'mot_cnh_data_validade', 'mot_cnh_primeira_habilitacao', 'mot_cnh_uf', 'mot_cnh_observacoes']) ? 'docs' : ($errors->hasAny(['mot_data_admissao', 'mot_data_demissao', 'mot_tipo_contrato', 'mot_categoria_profissional', 'mot_matricula_interna', 'mot_banco', 'mot_agencia', 'mot_conta', 'mot_tipo_conta', 'mot_chave_pix', 'mot_observacoes']) ? 'profissional' : 'geral')) }}', 
        mobile: window.matchMedia('(max-width: 640px)').matches 
    }" 
    x-init="window.matchMedia('(max-width: 640px)').addEventListener('change', e => mobile = e.matches);"
    @invalid.capture.window="
        const target = $event.target;
        if (document.getElementById('tab-geral-content') && document.getElementById('tab-geral-content').contains(target)) {
            tab = 'geral';
        } else if (document.getElementById('tab-docs-content') && document.getElementById('tab-docs-content').contains(target)) {
            tab = 'docs';
        } else if (document.getElementById('tab-profissional-content') && document.getElementById('tab-profissional-content').contains(target)) {
            tab = 'profissional';
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

                @if($showTabDocs)
                <button @click="tab = 'docs'" 
                    :class="tab === 'docs' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Documentação
                </button>
                @endif

                @if($showTabProfissional)
                <button @click="tab = 'profissional'" 
                    :class="tab === 'profissional' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Profissional & Outros
                </button>
                @endif
            </nav>
        </div>

        <form method="POST" action="{{ route('motoristas.store') }}">
            @csrf
            @include('motoristas._form', ['motorista' => new \App\Models\Motorista()])
        </form>
    </div>
</x-app-layout>
