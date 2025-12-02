{{-- 5.4 - Lista de Reservas --}}
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                Lista de Reservas
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
                <span class="text-xs text-gray-500">{{ ($reservationsData['lista'] ?? collect())->count() }} registros</span>
            </div>

            <div x-show="showFilters" x-collapse>
                <form id="reservationsFilterForm" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-4">
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
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            <option value="pendente">Pendente</option>
                            <option value="aprovada">Aprovada</option>
                            <option value="em_uso">Em Uso</option>
                            <option value="encerrada">Encerrada</option>
                            <option value="cancelada">Cancelada</option>
                            <option value="rejeitada">Rejeitada</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Veículo</label>
                        <select name="veiculo" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            @foreach(($reservationsData['lista'] ?? collect())->unique('res_vei_id')->sortBy('veiculo.vei_placa') as $item)
                                @if($item->veiculo)
                                    <option value="{{ $item->veiculo->vei_id }}">{{ $item->veiculo->vei_placa }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Motorista</label>
                        <select name="motorista" class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            @foreach(($reservationsData['lista'] ?? collect())->unique('res_mot_id')->sortBy('motorista.mot_nome') as $item)
                                @if($item->motorista)
                                    <option value="{{ $item->motorista->mot_id }}">{{ $item->motorista->mot_nome }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="button" onclick="applyReservationsFilters()" class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Aplicar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabela de Reservas --}}
        <div class="overflow-x-auto w-full">
            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Veículo</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motorista</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="reservationsTableBody">
                    @forelse(($reservationsData['lista'] ?? []) as $reserva)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $reserva->res_codigo }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $reserva->veiculo->vei_placa ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $reserva->motorista->mot_nome ?? 'A definir' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <div>{{ $reserva->res_data_inicio->format('d/m/Y H:i') }}</div>
                                <div class="text-xs text-gray-500">{{ $reserva->res_data_fim->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm">
                                @if($reserva->res_tipo)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($reserva->res_tipo) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $reserva->res_status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $reserva->res_status === 'aprovada' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $reserva->res_status === 'em_uso' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $reserva->res_status === 'encerrada' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ in_array($reserva->res_status, ['cancelada', 'rejeitada']) ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $reserva->res_status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                                <button onclick="showReservationDetails({{ $reserva->res_id }})" 
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
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                Nenhuma reserva encontrada
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
function applyReservationsFilters() {
    // Implementação de filtros via JavaScript
    console.log('Filtros de reservas aplicados - implementação futura');
}

function showReservationDetails(id) {
    // Buscar detalhes da reserva via AJAX
    fetch(`/dashboard/reservations/${id}/details`)
        .then(response => response.json())
        .then(data => {
            // Popular modal com dados
            document.getElementById('modalReservationDetailsContent').innerHTML = renderReservationDetails(data);
            // Abrir modal
            document.getElementById('modalReservationDetails').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        })
        .catch(error => {
            console.error('Erro ao carregar detalhes:', error);
            alert('Erro ao carregar detalhes da reserva');
        });
}

function renderReservationDetails(data) {
    const status = {
        'pendente': { text: 'Pendente', color: 'yellow' },
        'aprovada': { text: 'Aprovada', color: 'blue' },
        'em_uso': { text: 'Em Uso', color: 'green' },
        'encerrada': { text: 'Encerrada', color: 'gray' },
        'cancelada': { text: 'Cancelada', color: 'red' },
        'rejeitada': { text: 'Rejeitada', color: 'red' }
    }[data.res_status] || { text: 'Desconhecido', color: 'gray' };

    const kmPrevisto = data.res_km_fim && data.res_km_inicio ? data.res_km_fim - data.res_km_inicio : 0;

    return `
    <div class="space-y-6">
        <div class="grid grid-cols-2 gap-4 pb-4 border-b">
            <div>
                <p class="text-sm font-medium text-gray-500">Código</p>
                <p class="mt-1 text-lg font-bold text-gray-900">#${data.res_codigo}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Status</p>
                <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-${status.color}-100 text-${status.color}-800">
                    ${status.text}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Veículo</p>
                <p class="mt-1 text-sm text-gray-900">${data.veiculo?.vei_placa || 'N/A'}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Motorista</p>
                <p class="mt-1 text-sm text-gray-900">${data.motorista?.mot_nome || 'A definir'}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Solicitante</p>
                <p class="mt-1 text-sm text-gray-900">${data.solicitante?.name || 'N/A'}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Tipo</p>
                <p class="mt-1 text-sm text-gray-900">${data.res_tipo ? data.res_tipo.charAt(0).toUpperCase() + data.res_tipo.slice(1) : '-'}</p>
            </div>
        </div>

        <div class="pt-4 border-t">
            <p class="text-sm font-medium text-gray-500 mb-2">Período</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Início</p>
                    <p class="text-sm text-gray-900">${new Date(data.res_data_inicio).toLocaleString('pt-BR')}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Fim</p>
                    <p class="text-sm text-gray-900">${new Date(data.res_data_fim).toLocaleString('pt-BR')}</p>
                </div>
            </div>
        </div>

        ${data.res_just ? `
        <div class="pt-4 border-t">
            <p class="text-sm font-medium text-gray-500 mb-2">Justificativa</p>
            <p class="text-sm text-gray-700">${data.res_just}</p>
        </div>
        ` : ''}

        ${kmPrevisto > 0 ? `
        <div class="pt-4 border-t">
            <p class="text-sm font-medium text-gray-500 mb-3">KM Previsto vs Real</p>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-xs text-gray-500">KM Inicial</p>
                    <p class="text-lg font-semibold text-gray-900">${parseInt(data.res_km_inicio || 0).toLocaleString('pt-BR')}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">KM Final</p>
                    <p class="text-lg font-semibold text-gray-900">${parseInt(data.res_km_fim || 0).toLocaleString('pt-BR')}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Total Rodado</p>
                    <p class="text-lg font-semibold text-blue-600">${parseInt(kmPrevisto).toLocaleString('pt-BR')} km</p>
                </div>
            </div>
        </div>
        ` : ''}

        ${data.audit_logs && data.audit_logs.length > 0 ? `
        <div class="pt-4 border-t">
            <p class="text-sm font-medium text-gray-500 mb-3">Histórico</p>
            <div class="flow-root">
                <ul class="-mb-8">
                    ${data.audit_logs.map((log, index) => `
                    <li>
                        <div class="relative pb-8">
                            ${index < data.audit_logs.length - 1 ? '<span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>' : ''}
                            <div class="relative flex space-x-3">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center ring-8 ring-white">
                                    <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-900">${log.ral_acao}</p>
                                        <p class="text-xs text-gray-500">${log.user?.name || 'Sistema'} - ${new Date(log.created_at).toLocaleString('pt-BR')}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    `).join('')}
                </ul>
            </div>
        </div>
        ` : ''}
    </div>
    `;
}
</script>
@endpush
