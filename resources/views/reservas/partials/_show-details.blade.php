<!-- Detalhes Principais -->
<dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-8">
   <div class="sm:col-span-1">
        <dt class="text-sm font-medium text-gray-500">Tipo</dt>
        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($reserva->res_tipo) }}</dd>
    </div>
    <div class="sm:col-span-1">
        <dt class="text-sm font-medium text-gray-500">Período</dt>
        <dd class="mt-1 text-sm text-gray-900">
            {{ \Carbon\Carbon::parse($reserva->res_data_inicio)->format('d/m/Y H:i') }}
            <span class="text-xs">até</span>
            {{ \Carbon\Carbon::parse($reserva->res_data_fim)->format('d/m/Y H:i') }}
            @if($reserva->res_dia_todo)
                <span class="text-xs text-blue-600">(Dia todo)</span>
            @endif
        </dd>
    </div>

    <!-- Detalhes de Viagem -->
    @if ($reserva->res_tipo == 'viagem')
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Motorista</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $reserva->motorista->mot_nome ?? '(Não definido)' }}</dd>
        </div>
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Origem</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $reserva->res_origem ?? '(Não definida)' }}</dd>
        </div>
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Destino</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $reserva->res_destino ?? '(Não definido)' }}</dd>
        </div>
    @endif

    <!-- Detalhes de Manutenção -->
    @if ($reserva->res_tipo == 'manutencao')
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Fornecedor (Oficina)</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $reserva->fornecedor->for_nome_fantasia ?? '(Não definido)' }}</dd>
        </div>
    @endif

    <div class="sm:col-span-2">
        <dt class="text-sm font-medium text-gray-500">Justificativa</dt>
        <dd class="mt-1 text-sm text-gray-900">{{ $reserva->res_just ?? '(Nenhuma)' }}</dd>
    </div>
    <div class="sm:col-span-2">
        <dt class="text-sm font-medium text-gray-500">Observações</dt>
        <dd class="mt-1 text-sm text-gray-900">{{ $reserva->res_obs ?? '(Nenhuma)' }}</dd>
    </div>

    <!-- CAMPOS DE INÍCIO -->
    @if (in_array($reserva->res_status, ['em_uso', 'em_revisao', 'encerrada', 'pendente_ajuste']))
        <div class="sm:col-span-1 pt-4 border-t">
            <dt class="text-sm font-medium text-gray-500">Hora da Saída</dt>
            <dd class="mt-1 text-sm font-bold text-gray-900">{{ $reserva->res_hora_saida ? $reserva->res_hora_saida->format('d/m/Y H:i') : 'N/D' }}</dd>
        </div>
        <div class="sm:col-span-1 pt-4 border-t">
            <dt class="text-sm font-medium text-gray-500">KM Inicial</dt>
            <dd class="mt-1 text-sm font-bold text-gray-900">{{ number_format($reserva->res_km_inicio, 0, ',', '.') ?? 'N/D' }} km</dd>
        </div>
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Combustível Inicial</dt>
            <dd class="mt-1 text-sm font-bold text-gray-900">{{ $reserva->res_comb_inicio ?? 'N/D' }}</dd>
        </div>
    @endif

    <!-- CAMPOS DE FIM -->
    @if (in_array($reserva->res_status, ['em_revisao', 'encerrada', 'pendente_ajuste']))
         <div class="sm:col-span-1 pt-4 border-t">
            <dt class="text-sm font-medium text-gray-500">Hora da Chegada</dt>
            <dd class="mt-1 text-sm font-bold text-gray-900">{{ $reserva->res_hora_chegada ? $reserva->res_hora_chegada->format('d/m/Y H:i') : 'N/D' }}</dd>
        </div>
        <div class="sm:col-span-1 pt-4 border-t">
            <dt class="text-sm font-medium text-gray-500">KM Final</dt>
            <dd class="mt-1 text-sm font-bold text-gray-900">{{ number_format($reserva->res_km_fim, 0, ',', '.') ?? 'N/D' }} km</dd>
        </div>
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Combustível Final</dt>
            <dd class="mt-1 text-sm font-bold text-gray-900">{{ $reserva->res_comb_fim ?? 'N/D' }}</dd>
        </div>
         <div class="sm:col-span-2">
            <dt class="text-sm font-medium text-gray-500">Obs. Finais (Motorista)</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $reserva->res_obs_finais ?? '(Nenhuma)' }}</dd>
        </div>
    @endif

    <!-- DADOS DA REVISÃO -->
    @if (in_array($reserva->res_status, ['encerrada', 'pendente_ajuste']))
         <div class="sm:col-span-1 pt-4 border-t border-indigo-200">
            <dt class="text-sm font-medium text-gray-500">Revisado Por</dt>
            <dd class="mt-1 text-sm font-bold text-indigo-700">{{ $reserva->revisor->name ?? 'N/D' }}</dd>
        </div>
         <div class="sm:col-span-1 pt-4 border-t border-indigo-200">
            <dt class="text-sm font-medium text-gray-500">Data da Revisão</dt>
            <dd class="mt-1 text-sm font-bold text-indigo-700">{{ $reserva->res_data_revisao ? $reserva->res_data_revisao->format('d/m/Y H:i') : 'N/D' }}</dd>
        </div>
         <div class="sm:col-span-2">
            <dt class="text-sm font-medium text-gray-500">Observações do Revisor</dt>
            <dd class="mt-1 text-sm text-indigo-700">{{ $reserva->res_obs_revisor ?? '(Nenhuma)' }}</dd>
        </div>
    @endif
</dl>
