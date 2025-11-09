<!-- NOVO: Sumário de Viagem/Revisão -->
<div class="mt-8 pt-6 border-t">
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        {{-- Card de Início --}}
        @if (in_array($reserva->res_status, ['em_uso', 'em_revisao', 'encerrada', 'pendente_ajuste']))
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h5 class="text-md font-semibold text-gray-700 mb-3">Registro de Saída</h5>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Hora da Saída</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $reserva->res_hora_saida ? $reserva->res_hora_saida->format('d/m/Y H:i') : 'N/D' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">KM Inicial</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ number_format($reserva->res_km_inicio, 0, ',', '.') ?? 'N/D' }} km</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Combustível Inicial</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ ucfirst($reserva->res_comb_inicio) ?? 'N/D' }}</dd>
                    </div>
                </dl>
            </div>
        @endif

        {{-- Card de Fim --}}
        @if (in_array($reserva->res_status, ['em_revisao', 'encerrada', 'pendente_ajuste']))
             <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h5 class="text-md font-semibold text-gray-700 mb-3">Registro de Chegada</h5>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Hora da Chegada</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $reserva->res_hora_chegada ? $reserva->res_hora_chegada->format('d/m/Y H:i') : 'N/D' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">KM Final</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ number_format($reserva->res_km_fim, 0, ',', '.') ?? 'N/D' }} km</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Combustível Final</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ ucfirst($reserva->res_comb_fim) ?? 'N/D' }}</dd>
                    </div>
                     <div class="pt-2 border-t mt-2">
                        <dt class="text-xs font-medium text-gray-500">Obs. Finais (Motorista)</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $reserva->res_obs_finais ?? '(Nenhuma)' }}</dd>
                    </div>
                </dl>
            </div>
        @endif

        {{-- Card de Revisão --}}
        @if (in_array($reserva->res_status, ['encerrada', 'pendente_ajuste']))
             <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                <h5 class="text-md font-semibold text-indigo-800 mb-3">Dados da Revisão</h5>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Revisado Por</dt>
                        <dd class="text-sm font-semibold text-indigo-700">{{ $reserva->revisor->name ?? 'N/D' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Data da Revisão</dt>
                        <dd class="text-sm font-semibold text-indigo-700">{{ $reserva->res_data_revisao ? $reserva->res_data_revisao->format('d/m/Y H:i') : 'N/D' }}</dd>
                    </div>
                    <div class="pt-2 border-t mt-2 border-indigo-100">
                        <dt class="text-xs font-medium text-gray-500">Observações do Revisor</dt>
                        <dd class="mt-1 text-sm text-indigo-700">{{ $reserva->res_obs_revisor ?? '(Nenhuma)' }}</dd>
                    </div>
                </dl>
            </div>
        @endif

    </div>
</div>