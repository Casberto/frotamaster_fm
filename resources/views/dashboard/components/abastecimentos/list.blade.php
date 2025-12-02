{{-- 4.2 - Lista de Abastecimentos --}}
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                Lista de Abastecimentos
            </h3>
        </div>

        {{-- Filtros --}}
        <div class="mb-6 p-4 bg-gray-50 rounded-lg" x-data="{ showFilters: false }">
            <div class="flex items-center justify-between mb-2">
                <button @click="showFilters = !showFilters" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                    Filtros
                </button>
                <span class="text-xs text-gray-500">{{ ($fuelingData['lista'] ?? collect())->count() }} registros</span>
            </div>

            <div x-show="showFilters" x-collapse>
                <form id="fuelingFilterForm" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Período</label>
                        <select name="periodo" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            <option value="hoje">Hoje</option>
                            <option value="semana">Esta Semana</option>
                            <option value="mes" selected>Este Mês</option>
                            <option value="trimestre">Último Trimestre</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Veículo</label>
                        <select name="veiculo" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            @foreach(($fuelingData['lista'] ?? collect())->unique('aba_vei_id')->sortBy('veiculo.vei_placa') as $item)
                                @if($item->veiculo)
                                    <option value="{{ $item->veiculo->vei_id }}">{{ $item->veiculo->vei_placa }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Posto</label>
                        <select name="posto" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            @foreach(($fuelingData['lista'] ?? collect())->unique('aba_for_id')->sortBy('fornecedor.for_nome_fantasia') as $item)
                                @if($item->fornecedor)
                                    <option value="{{ $item->fornecedor->for_id }}">{{ $item->fornecedor->for_nome_fantasia }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Combustível</label>
                        <select name="combustivel" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            <option value="1">Gasolina</option>
                            <option value="2">Etanol</option>
                            <option value="3">Diesel</option>
                            <option value="4">GNV</option>
                            <option value="5">Elétrico</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="button" onclick="applyFuelingFilters()" class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Aplicar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabela de Abastecimentos --}}
        <div class="overflow-x-auto w-full">
            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Veículo</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posto</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Combustível</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">KM</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motorista</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="fuelingTableBody">
                    @forelse(($fuelingData['lista'] ?? []) as $abastecimento)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $abastecimento->aba_data->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $abastecimento->veiculo->vei_placa ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $abastecimento->fornecedor->for_nome_fantasia ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ match($abastecimento->aba_combustivel) {
                                        1 => 'bg-red-100 text-red-800',
                                        2 => 'bg-green-100 text-green-800',
                                        3 => 'bg-yellow-100 text-yellow-800',
                                        4 => 'bg-blue-100 text-blue-800',
                                        5 => 'bg-purple-100 text-purple-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    } }}">
                                    {{ match($abastecimento->aba_combustivel) {
                                        1 => 'Gasolina',
                                        2 => 'Etanol',
                                        3 => 'Diesel',
                                        4 => 'GNV',
                                        5 => 'Elétrico',
                                        default => 'Outro'
                                    } }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900">
                                {{ number_format($abastecimento->aba_qtd, 2, ',', '.') }} L
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900">
                                R$ {{ number_format($abastecimento->aba_vlr_tot, 2, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-700">
                                {{ number_format($abastecimento->aba_km, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $abastecimento->reservas->first()->motorista->mot_nome ?? '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                                <button onclick="showFuelingDetails({{ $abastecimento->aba_id }})" 
                                    class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-sm text-gray-500">
                                Nenhum abastecimento encontrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function applyFuelingFilters() {
    // Implementação de filtros via JavaScript
    // Por enquanto apenas recarrega a página com parâmetros de filtro
    console.log('Filtros aplicados - implementação futura');
}

function showFuelingDetails(id) {
    // Buscar detalhes do abastecimento via AJAX
    fetch(`/abastecimentos/${id}/detalhes`)
        .then(response => response.json())
        .then(data => {
            // Popular modal com dados
            document.getElementById('modalFuelingDetailsContent').innerHTML = renderFuelingDetails(data);
            // Abrir modal
            document.getElementById('modalFuelingDetails').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        })
        .catch(error => {
            console.error('Erro ao carregar detalhes:', error);
            alert('Erro ao carregar detalhes do abastecimento');
        });
}

function renderFuelingDetails(data) {
    const combustivel = {
        1: 'Gasolina',
        2: 'Etanol',
        3: 'Diesel',
        4: 'GNV',
        5: 'Elétrico'
    }[data.aba_combustivel] || 'Outro';

    return `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Data</p>
                    <p class="mt-1 text-sm text-gray-900">${new Date(data.aba_data).toLocaleDateString('pt-BR')}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Veículo</p>
                    <p class="mt-1 text-sm text-gray-900">${data.veiculo?.vei_placa || 'N/A'}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Posto</p>
                    <p class="mt-1 text-sm text-gray-900">${data.fornecedor?.for_nome_fantasia || 'N/A'}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Combustível</p>
                    <p class="mt-1 text-sm text-gray-900">${combustivel}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Quantidade</p>
                    <p class="mt-1 text-sm text-gray-900">${parseFloat(data.aba_qtd).toFixed(2)} L</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Valor Total</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">R$ ${parseFloat(data.aba_vlr_tot).toFixed(2).replace('.', ',')}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Valor Unitário</p>
                    <p class="mt-1 text-sm text-gray-900">R$ ${parseFloat(data.aba_vlr_und).toFixed(3).replace('.', ',')}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">KM</p>
                    <p class="mt-1 text-sm text-gray-900">${parseInt(data.aba_km).toLocaleString('pt-BR')}</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <p class="text-sm font-medium text-gray-500 mb-2">Checklist</p>
                <div class="grid grid-cols-2 gap-2">
                    <div class="flex items-center">
                        <span class="${data.aba_tanque_cheio ? 'text-green-600' : 'text-gray-400'}">
                            ${data.aba_tanque_cheio ? '✓' : '✗'}
                        </span>
                        <span class="ml-2 text-sm text-gray-700">Tanque Cheio</span>
                    </div>
                    <div class="flex items-center">
                        <span class="${data.aba_pneus_calibrados ? 'text-green-600' : 'text-gray-400'}">
                            ${data.aba_pneus_calibrados ? '✓' : '✗'}
                        </span>
                        <span class="ml-2 text-sm text-gray-700">Pneus Calibrados</span>
                    </div>
                    <div class="flex items-center">
                        <span class="${data.aba_agua_verificada ? 'text-green-600' : 'text-gray-400'}">
                            ${data.aba_agua_verificada ? '✓' : '✗'}
                        </span>
                        <span class="ml-2 text-sm text-gray-700">Água Verificada</span>
                    </div>
                    <div class="flex items-center">
                        <span class="${data.aba_oleo_verificado ? 'text-green-600' : 'text-gray-400'}">
                            ${data.aba_oleo_verificado ? '✓' : '✗'}
                        </span>
                        <span class="ml-2 text-sm text-gray-700">Óleo Verificado</span>
                    </div>
                </div>
            </div>

            ${data.aba_obs ? `
            <div class="pt-4 border-t border-gray-200">
                <p class="text-sm font-medium text-gray-500 mb-2">Observações</p>
                <p class="text-sm text-gray-700">${data.aba_obs}</p>
            </div>
            ` : ''}
        </div>
    `;
}
</script>
@endpush
