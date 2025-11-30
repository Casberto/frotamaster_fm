{{-- 5.2 - Calendário de Visualização --}}
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Próximas Reservas (30 dias)</h3>
        <div class="text-sm text-gray-500">
            <span class="font-medium">{{ count($reservationsData['calendario'] ?? []) }}</span> reservas agendadas
        </div>
    </div>

    @if(count($reservationsData['calendario'] ?? []) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($reservationsData['calendario'] as $reserva)
                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer
                    {{ $reserva->res_status === 'pendente' ? 'border-yellow-300 bg-yellow-50' : '' }}
                    {{ $reserva->res_status === 'aprovada' ? 'border-blue-300 bg-blue-50' : '' }}
                    {{ $reserva->res_status === 'em_uso' ? 'border-green-300 bg-green-50' : '' }}"
                    @click="$dispatch('open-reservation-modal', { id: {{ $reserva->res_id }} })"
                >
                    <div class="flex justify-between items-start mb-2">
                        <div class="text-sm font-semibold text-gray-900">
                            #{{ $reserva->res_codigo }}
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $reserva->res_status === 'pendente' ? 'bg-yellow-200 text-yellow-800' : '' }}
                            {{ $reserva->res_status === 'aprovada' ? 'bg-blue-200 text-blue-800' : '' }}
                            {{ $reserva->res_status === 'em_uso' ? 'bg-green-200 text-green-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $reserva->res_status)) }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-700 mb-1">
                        <svg class="inline w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                        <strong>{{ $reserva->veiculo->vei_placa ?? 'N/A' }}</strong>
                    </div>

                    @if($reserva->motorista)
                        <div class="text-sm text-gray-600 mb-1">
                            <svg class="inline w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            {{ $reserva->motorista->mot_nome }}
                        </div>
                    @endif

                    <div class="text-xs text-gray-500 mt-2 pt-2 border-t">
                        <div>{{ \Carbon\Carbon::parse($reserva->res_data_inicio)->format('d/m/Y H:i') }}</div>
                        <div>{{ \Carbon\Carbon::parse($reserva->res_data_fim)->format('d/m/Y H:i') }}</div>
                    </div>

                    @if($reserva->res_tipo)
                        <div class="text-xs text-gray-500 mt-1">
                            Tipo: <span class="font-medium">{{ ucfirst($reserva->res_tipo) }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center text-gray-500 py-8">
            <svg class="mx-auto w-12 h-12 text-gray-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
            </svg>
            <p>Nenhuma reserva agendada para os próximos 30 dias</p>
        </div>
    @endif
</div>
