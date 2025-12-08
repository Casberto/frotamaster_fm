<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Apólice: {{ $apolice->seg_numero }}
            </h2>
            <div class="flex space-x-2">
                <form action="{{ route('seguros.renew', $apolice->seg_id) }}" method="POST" onsubmit="return confirm('Deseja gerar uma renovação desta apólice?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition text-sm">
                        Renovar
                    </button>
                </form>
                <a href="{{ route('seguros.edit', $apolice->seg_id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm">
                    Editar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6" x-data="{ tab: 'geral' }">
        
        {{-- Resumo Topo --}}
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $apolice->seg_status == 'Ativo' ? 'bg-green-100 text-green-800' : 
                           ($apolice->seg_status == 'Vencida' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ $apolice->seg_status }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Veículo</p>
                    <p class="font-medium text-gray-900">{{ $apolice->veiculo->vei_placa ?? '-' }}</p>
                    <p class="text-xs text-gray-500">{{ $apolice->veiculo->vei_modelo ?? '' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Seguradora</p>
                    <p class="font-medium text-gray-900">{{ $apolice->fornecedor->for_nome_fantasia ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Vigência</p>
                    <p class="font-medium text-gray-900">
                        {{ $apolice->seg_inicio ? $apolice->seg_inicio->format('d/m/Y') : '' }} até 
                        {{ $apolice->seg_fim ? $apolice->seg_fim->format('d/m/Y') : '' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Abas Navegação --}}
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="tab = 'geral'" 
                    :class="tab === 'geral' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Dados Gerais
                </button>

                <button @click="tab = 'coberturas'" 
                    :class="tab === 'coberturas' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Coberturas ({{ $apolice->coberturas->count() }})
                </button>

                <button @click="tab = 'sinistros'" 
                    :class="tab === 'sinistros' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Sinistros ({{ $apolice->sinistros->count() }})
                </button>
            </nav>
        </div>

        {{-- Conteúdo das Abas --}}
        <div x-show="tab === 'geral'" class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Detalhes da Apólice</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Número da Apólice</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $apolice->seg_numero }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Tipo de Seguro</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $apolice->seg_tipo ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Valor Total</dt>
                        <dd class="mt-1 text-sm text-gray-900">R$ {{ number_format($apolice->seg_valor_total, 2, ',', '.') }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Parcelas</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $apolice->seg_parcelas ?? '1' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Franquia</dt>
                        <dd class="mt-1 text-sm text-gray-900">R$ {{ number_format($apolice->seg_franquia, 2, ',', '.') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Observações</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $apolice->seg_obs ?? 'Nenhuma observação.' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div x-show="tab === 'coberturas'" style="display: none;"> {{-- x-show handles display, style none prevents flicker --}}
            @include('seguros.partials.coberturas')
        </div>

        <div x-show="tab === 'sinistros'" style="display: none;">
            @include('seguros.partials.sinistros')
        </div>

    </div>
</x-app-layout>
