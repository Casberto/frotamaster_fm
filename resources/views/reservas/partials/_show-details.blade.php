<div class="space-y-8">
    
    {{-- SEÇÃO 1: CABEÇALHO DE INFORMAÇÕES BÁSICAS --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            {{-- Tipo de Reserva --}}
            <div class="flex flex-col">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tipo</span>
                <div class="flex items-center gap-2">
                    <div class="p-2 rounded-lg {{ $reserva->res_tipo == 'viagem' ? 'bg-blue-50 text-blue-600' : 'bg-orange-50 text-orange-600' }}">
                        @if($reserva->res_tipo == 'viagem')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        @endif
                    </div>
                    <span class="font-semibold text-gray-900">{{ ucfirst($reserva->res_tipo) }}</span>
                </div>
            </div>

            {{-- Período --}}
            <div class="lg:col-span-2 flex flex-col">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Período Agendado</span>
                <div class="flex items-center flex-wrap gap-2 text-sm text-gray-900 font-medium">
                    <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-md border border-gray-100">
                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        {{ \Carbon\Carbon::parse($reserva->res_data_inicio)->format('d/m/Y H:i') }}
                    </div>
                    <span class="text-gray-400">➜</span>
                    <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-md border border-gray-100">
                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        {{ \Carbon\Carbon::parse($reserva->res_data_fim)->format('d/m/Y H:i') }}
                    </div>
                    @if($reserva->res_dia_todo)
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            Dia Todo
                        </span>
                    @endif
                </div>
            </div>

            {{-- Responsável (Motorista ou Fornecedor) --}}
            <div class="flex flex-col">
                @if ($reserva->res_tipo == 'viagem')
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Motorista</span>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                            {{ substr($reserva->motorista->mot_nome ?? '?', 0, 1) }}
                        </div>
                        <span class="text-sm font-medium text-gray-900 truncate" title="{{ $reserva->motorista->mot_nome ?? 'Não definido' }}">
                            {{ $reserva->motorista->mot_nome ?? '(Não definido)' }}
                        </span>
                    </div>
                @elseif ($reserva->res_tipo == 'manutencao')
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Fornecedor</span>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 truncate">
                            {{ $reserva->fornecedor->for_nome_fantasia ?? '(Interna / Não definido)' }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- SEÇÃO 2: COLUNA DA ESQUERDA (Rota e Justificativa) --}}
        <div class="lg:col-span-2 space-y-8">
            
            @if ($reserva->res_tipo == 'viagem')
            <div>
                <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 bg-indigo-500 rounded-full"></span>
                    Rota e Itinerário
                </h4>
                
                {{-- Timeline Vertical --}}
                <div class="relative pl-4 ml-2 border-l-2 border-indigo-100 space-y-8 py-2">
                    {{-- Origem --}}
                    <div class="relative group">
                        <div class="absolute -left-[21px] top-1 h-4 w-4 rounded-full border-2 border-white bg-indigo-500 shadow-sm"></div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Origem</p>
                            <p class="text-base font-medium text-gray-900">{{ $reserva->res_origem ?? '(Não informada)' }}</p>
                        </div>
                    </div>

                    {{-- Destino --}}
                    <div class="relative group">
                        <div class="absolute -left-[21px] top-1 h-4 w-4 rounded-full border-2 border-white bg-red-500 shadow-sm"></div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Destino</p>
                            <p class="text-base font-medium text-gray-900">{{ $reserva->res_destino ?? '(Não informado)' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div>
                <h4 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-1 h-4 bg-gray-500 rounded-full"></span>
                    Justificativa e Observações
                </h4>
                <div class="bg-gray-50 rounded-xl p-4 space-y-4 border border-gray-100">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase mb-1">Motivo da Reserva</p>
                        <p class="text-sm text-gray-700 italic">"{{ $reserva->res_just ?? 'Nenhuma justificativa informada.' }}"</p>
                    </div>
                    @if($reserva->res_obs)
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-1">Observações Gerais</p>
                        <p class="text-sm text-gray-700">{{ $reserva->res_obs }}</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- SEÇÃO 3: COLUNA DA DIREITA (Dados de Execução - Check-in/Out) --}}
        <div class="lg:col-span-1">
            
            @if (in_array($reserva->res_status, ['em_uso', 'em_revisao', 'encerrada', 'pendente_ajuste']))
                <h4 class="text-sm font-bold text-gray-900 mb-4">Dados de Execução</h4>
                <div class="space-y-4">
                    
                    {{-- Card de Saída --}}
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                        <div class="bg-gray-50 px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-600 uppercase flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                                Saída
                            </span>
                            <span class="text-xs font-mono text-gray-500">{{ $reserva->res_hora_saida ? $reserva->res_hora_saida->format('d/m H:i') : '--' }}</span>
                        </div>
                        <div class="p-3 grid grid-cols-2 gap-2">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">KM Inicial</p>
                                <p class="text-sm font-bold text-gray-900">{{ number_format($reserva->res_km_inicio, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Combustível</p>
                                <p class="text-sm font-bold text-gray-900">{{ ucfirst($reserva->res_comb_inicio) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card de Retorno (Se houver) --}}
                    @if (in_array($reserva->res_status, ['em_revisao', 'encerrada', 'pendente_ajuste']))
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                        <div class="bg-gray-50 px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-600 uppercase flex items-center gap-1">
                                <svg class="w-3 h-3 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                                Retorno
                            </span>
                            <span class="text-xs font-mono text-gray-500">{{ $reserva->res_hora_chegada ? $reserva->res_hora_chegada->format('d/m H:i') : '--' }}</span>
                        </div>
                        <div class="p-3 grid grid-cols-2 gap-2">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">KM Final</p>
                                <p class="text-sm font-bold text-gray-900">{{ number_format($reserva->res_km_fim, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">Combustível</p>
                                <p class="text-sm font-bold text-gray-900">{{ ucfirst($reserva->res_comb_fim) }}</p>
                            </div>
                            
                            {{-- Total Percorrido (Badge) --}}
                            <div class="col-span-2 mt-2 pt-2 border-t border-dashed border-gray-100">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] text-gray-400 uppercase">Rodagem Total</span>
                                    <span class="text-sm font-bold text-blue-600">+ {{ number_format($reserva->res_km_fim - $reserva->res_km_inicio, 0, ',', '.') }} km</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($reserva->res_obs_finais)
                        <div class="p-3 bg-yellow-50 border border-yellow-100 rounded-lg">
                            <p class="text-[10px] font-bold text-yellow-700 uppercase mb-1">Obs. Motorista (Retorno)</p>
                            <p class="text-xs text-yellow-900">{{ $reserva->res_obs_finais }}</p>
                        </div>
                    @endif

                    @else
                        {{-- Placeholder se ainda não retornou --}}
                        <div class="border-2 border-dashed border-gray-200 rounded-lg p-4 flex flex-col items-center justify-center text-gray-400 bg-gray-50/50">
                            <svg class="w-8 h-8 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="text-xs font-medium">Aguardando Retorno</span>
                        </div>
                    @endif

                </div>
            @endif
            
            {{-- Auditoria --}}
            @if (in_array($reserva->res_status, ['encerrada', 'pendente_ajuste']))
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex items-start gap-3">
                        <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 shrink-0">
                             <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Revisado por <span class="font-bold text-gray-700">{{ $reserva->revisor->name ?? 'Sistema' }}</span></p>
                            <p class="text-[10px] text-gray-400">{{ $reserva->res_data_revisao?->format('d/m/Y H:i') }}</p>
                            @if($reserva->res_obs_revisor)
                                <p class="text-xs text-purple-700 mt-1 bg-purple-50 p-2 rounded">"{{ $reserva->res_obs_revisor }}"</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>