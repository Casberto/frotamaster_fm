<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Veículos com Problemas -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-red-50">
            <h3 class="text-lg leading-6 font-medium text-red-800">
                ⚠️ Atenção Necessária
            </h3>
        </div>
        <ul class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
            @forelse($operacional['veiculos_problemas'] as $veiculo)
                <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-indigo-600 truncate">
                            {{ $veiculo->vei_placa }} - {{ $veiculo->vei_modelo }}
                        </div>
                        <div class="ml-2 flex-shrink-0 flex">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Manutenção Vencida
                            </span>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-4 sm:px-6 text-gray-500 text-center text-sm">
                    Nenhum veículo com problemas críticos.
                </li>
            @endforelse
            
            @foreach($operacional['motoristas_pendencias'] as $motorista)
                 <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-indigo-600 truncate">
                            {{ $motorista->mot_nome }}
                        </div>
                        <div class="ml-2 flex-shrink-0 flex">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ $motorista->mot_status == 'Ativo' ? 'CNH Vencida' : $motorista->mot_status }}
                            </span>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Próximas Manutenções -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Próximas Manutenções
            </h3>
        </div>
        <ul class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
            @forelse($operacional['proximas_manutencoes'] as $manutencao)
                <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-indigo-600 truncate">
                            {{ $manutencao->veiculo->vei_placa }}
                        </p>
                        <div class="ml-2 flex-shrink-0 flex">
                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ \Carbon\Carbon::parse($manutencao->man_data_inicio)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-2 sm:flex sm:justify-between">
                        <div class="sm:flex">
                            <p class="flex items-center text-sm text-gray-500">
                                {{ $manutencao->man_descricao ?? 'Manutenção Agendada' }}
                            </p>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-4 sm:px-6 text-gray-500 text-center text-sm">
                    Nenhuma manutenção agendada próxima.
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Abastecimentos Recentes -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Abastecimentos Recentes
            </h3>
        </div>
        <ul class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
             @forelse($operacional['abastecimentos_recentes'] as $abastecimento)
                <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-indigo-600 truncate">
                            {{ $abastecimento->veiculo->vei_placa }}
                        </p>
                        <div class="ml-2 flex-shrink-0 flex">
                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                R$ {{ number_format($abastecimento->aba_vlr_tot, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-2 sm:flex sm:justify-between">
                        <div class="sm:flex">
                            <p class="flex items-center text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($abastecimento->aba_data)->format('d/m H:i') }} - {{ $abastecimento->aba_litros }}L
                            </p>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-4 sm:px-6 text-gray-500 text-center text-sm">
                    Nenhum abastecimento recente.
                </li>
            @endforelse
        </ul>
    </div>
</div>
