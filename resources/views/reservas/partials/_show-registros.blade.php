<!-- SEÇÃO: Registros da Reserva -->
@if (in_array($reserva->res_status, ['em_uso', 'em_revisao', 'encerrada', 'pendente_ajuste']))
<div class="mt-8 pt-6 border-t">
    <h4 class="text-lg font-medium text-gray-800 mb-4">Registros da Reserva</h4>

    <!-- Subseção: Abastecimentos Vinculados -->
    <div class="mb-6 bg-gray-50 p-4 rounded-lg shadow-sm">
        <div class="flex justify-between items-center mb-3">
            <h5 class="text-md font-semibold text-gray-700">Abastecimentos Vinculados</h5>
            @if($reserva->res_status == 'em_uso')
                <x-secondary-button :href="route('abastecimentos.create', ['veiculo_id' => $reserva->res_vei_id])" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Registrar Novo
                </x-secondary-button>
            @endif
        </div>

        @if($reserva->abastecimentos->isEmpty())
            <p class="text-sm text-gray-500">Nenhum abastecimento vinculado.</p>
        @else
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Data</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">KM</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Litros</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Valor Total</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Pagamento</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Reembolso</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($reserva->abastecimentos as $abastecimento)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $abastecimento->aba_data->format('d/m/Y') }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ number_format($abastecimento->aba_km, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ number_format($abastecimento->aba_qtd, 2, ',', '.') }} L</td>
                                <td class="px-4 py-2 whitespace-nowrap">R$ {{ number_format($abastecimento->aba_vlr_tot, 2, ',', '.') }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $abastecimento->pivot->rab_forma_pagto ?? 'N/D' }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span class="{{ $abastecimento->pivot->rab_reembolso ? 'text-green-600 font-semibold' : 'text-gray-500' }}">
                                        {{ $abastecimento->pivot->rab_reembolso ? 'Sim' : 'Não' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    {{-- Permite desvincular se 'em_uso' ou 'em_revisao' --}}
                                    @if(in_array($reserva->res_status, ['em_uso', 'em_revisao']))
                                    <form action="{{ route('reservas.abastecimentos.detach', [$reserva, $abastecimento]) }}" method="POST" onsubmit="return confirm('Desvincular este abastecimento da reserva?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Desvincular</button>
                                    </form>
                                    @else
                                     <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($reserva->res_status == 'em_uso')
            <form action="{{ route('reservas.abastecimentos.attach', $reserva) }}" method="POST" class="mt-4 border-t pt-4">
                @csrf
                <h6 class="text-sm font-medium text-gray-600 mb-2">Vincular Abastecimento Existente</h6>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2">
                        <x-input-label for="abastecimento_id" value="Selecione o Abastecimento" />
                        @php
                            $abastecimentosDisponiveis = \App\Models\Abastecimento::where('aba_vei_id', $reserva->res_vei_id)
                                ->where('aba_emp_id', $reserva->res_emp_id)
                                ->whereDoesntHave('reservas', function ($query) use ($reserva) { $query->where('reservas.res_id', $reserva->res_id); })
                                ->orderBy('aba_data', 'desc')->limit(20)->get();
                        @endphp
                        <select id="abastecimento_id" name="abastecimento_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm" required>
                            <option value="">-- Selecione --</option>
                            @foreach ($abastecimentosDisponiveis as $abs)
                                <option value="{{ $abs->aba_id }}">
                                    {{ $abs->aba_data->format('d/m/y') }} - {{ number_format($abs->aba_km, 0, ',', '.') }} km - R$ {{ number_format($abs->aba_vlr_tot, 2, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="forma_pagamento" value="Forma de Pagamento" />
                        <x-text-input id="forma_pagamento" name="forma_pagamento" type="text" class="mt-1 block w-full text-sm" placeholder="Ex: TAG, Cartão Frota" required />
                    </div>
                    <div class="flex items-end space-x-4">
                        <div class="flex items-center">
                            <input id="reembolso_sim_abs" name="reembolso" type="radio" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            <label for="reembolso_sim_abs" class="ml-2 block text-sm font-medium text-gray-700">Reemb.</label>
                        </div>
                        <div class="flex items-center">
                            <input id="reembolso_nao_abs" name="reembolso" type="radio" value="0" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            <label for="reembolso_nao_abs" class="ml-2 block text-sm font-medium text-gray-700">Não Reemb.</label>
                        </div>
                         <x-primary-button type="submit" class="text-sm !py-1.5">
                            Vincular
                        </x-primary-button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <!-- Subseção: Pedágios -->
    <div class="mb-6 bg-gray-50 p-4 rounded-lg shadow-sm">
        <h5 class="text-md font-semibold text-gray-700 mb-3">Pedágios Registrados</h5>
        @if($reserva->pedagios->isEmpty())
            <p class="text-sm text-gray-500">Nenhum pedágio registrado.</p>
        @else
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Data/Hora</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Descrição</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Valor</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Pagamento</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Reembolso</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($reserva->pedagios as $pedagio)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $pedagio->rpe_data_hora->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-2">{{ $pedagio->rpe_desc }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">R$ {{ number_format($pedagio->rpe_valor, 2, ',', '.') }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $pedagio->rpe_forma_pagto }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                     <span class="{{ $pedagio->rpe_reembolso ? 'text-green-600 font-semibold' : 'text-gray-500' }}">
                                        {{ $pedagio->rpe_reembolso ? 'Sim' : 'Não' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    @if(in_array($reserva->res_status, ['em_uso', 'em_revisao']))
                                    <form action="{{ route('reservas.pedagios.detach', [$reserva, $pedagio]) }}" method="POST" onsubmit="return confirm('Remover este registro de pedágio?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Remover</button>
                                    </form>
                                    @else
                                     <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($reserva->res_status == 'em_uso')
            <form action="{{ route('reservas.pedagios.attach', $reserva) }}" method="POST" class="mt-4 border-t pt-4">
                @csrf
                <h6 class="text-sm font-medium text-gray-600 mb-2">Registrar Novo Pedágio</h6>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    <div>
                        <x-input-label for="rpe_data_hora" value="Data/Hora" />
                        <x-text-input id="rpe_data_hora" name="rpe_data_hora" type="datetime-local" class="mt-1 block w-full text-sm" required />
                    </div>
                     <div>
                        <x-input-label for="rpe_desc" value="Descrição" />
                        <x-text-input id="rpe_desc" name="rpe_desc" type="text" class="mt-1 block w-full text-sm" placeholder="Ex: Pedágio Rodovia X" required />
                    </div>
                     <div>
                        <x-input-label for="rpe_valor" value="Valor (R$)" />
                        <x-text-input id="rpe_valor" name="rpe_valor" type="number" step="0.01" class="mt-1 block w-full text-sm" required />
                    </div>
                    <div>
                        <x-input-label for="rpe_forma_pagto" value="Forma Pagamento" />
                        <x-text-input id="rpe_forma_pagto" name="rpe_forma_pagto" type="text" class="mt-1 block w-full text-sm" placeholder="Ex: TAG, Dinheiro" required />
                    </div>
                    <div class="flex items-end space-x-4">
                        <div class="flex items-center">
                            <input id="reembolso_sim_ped" name="rpe_reembolso" type="radio" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            <label for="reembolso_sim_ped" class="ml-2 block text-sm font-medium text-gray-700">Reemb.</label>
                        </div>
                        <div class="flex items-center">
                            <input id="reembolso_nao_ped" name="rpe_reembolso" type="radio" value="0" checked class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            <label for="reembolso_nao_ped" class="ml-2 block text-sm font-medium text-gray-700">Não Reemb.</label>
                        </div>
                         <x-primary-button type="submit" class="text-sm !py-1.5">
                            Adicionar
                        </x-primary-button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <!-- Subseção: Passageiros -->
     <div class="mb-6 bg-gray-50 p-4 rounded-lg shadow-sm">
        <h5 class="text-md font-semibold text-gray-700 mb-3">Passageiros</h5>
         @if($reserva->passageiros->isEmpty())
            <p class="text-sm text-gray-500">Nenhum passageiro registrado.</p>
        @else
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Nome</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Documento</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Entrou Em</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Saiu Em</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($reserva->passageiros as $passageiro)
                            <tr>
                                <td class="px-4 py-2">{{ $passageiro->rpa_nome }}</td>
                                <td class="px-4 py-2">{{ $passageiro->rpa_doc ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $passageiro->rpa_entrou_em }}</td>
                                <td class="px-4 py-2">{{ $passageiro->rpa_saiu_em ?? '-' }}</td> {{-- Saiu Em pode ser preenchido depois --}}
                                <td class="px-4 py-2 whitespace-nowrap">
                                     @if(in_array($reserva->res_status, ['em_uso', 'em_revisao']))
                                     <form action="{{ route('reservas.passageiros.detach', [$reserva, $passageiro]) }}" method="POST" onsubmit="return confirm('Remover este passageiro?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Remover</button>
                                    </form>
                                    @else
                                     <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($reserva->res_status == 'em_uso')
            <form action="{{ route('reservas.passageiros.attach', $reserva) }}" method="POST" class="mt-4 border-t pt-4">
                 @csrf
                <h6 class="text-sm font-medium text-gray-600 mb-2">Adicionar Passageiro</h6>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <x-input-label for="rpa_nome" value="Nome Completo" />
                        <x-text-input id="rpa_nome" name="rpa_nome" type="text" class="mt-1 block w-full text-sm" required />
                    </div>
                     <div>
                        <x-input-label for="rpa_doc" value="Documento (Opcional)" />
                        <x-text-input id="rpa_doc" name="rpa_doc" type="text" class="mt-1 block w-full text-sm" />
                    </div>
                     <div>
                        <x-input-label for="rpa_entrou_em" value="Local/Momento de Entrada" />
                        <x-text-input id="rpa_entrou_em" name="rpa_entrou_em" type="text" class="mt-1 block w-full text-sm" placeholder="Ex: Sede, 08:00" required />
                    </div>
                     <x-primary-button type="submit" class="text-sm !py-1.5">
                        Adicionar
                    </x-primary-button>
                </div>
            </form>
        @endif
    </div>

    <!-- Subseção: Manutenções (Apenas para reserva de manutenção) -->
     @if ($reserva->res_tipo == 'manutencao')
        <div class="mb-6 bg-gray-50 p-4 rounded-lg shadow-sm">
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-md font-semibold text-gray-700">Manutenções Vinculadas</h5>
                @if($reserva->res_status == 'em_uso')
                    <x-secondary-button :href="route('manutencoes.create', ['veiculo_id' => $reserva->res_vei_id, 'fornecedor_id' => $reserva->res_for_id])" target="_blank">
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Registrar Nova
                    </x-secondary-button>
                @endif
            </div>

            @if($reserva->manutencoes->isEmpty())
                <p class="text-sm text-gray-500">Nenhuma manutenção vinculada.</p>
            @else
                <div class="overflow-x-auto mb-4">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-gray-600">Data Início</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-600">Tipo</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-600">KM</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-600">Custo Total</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-600">Status</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-600">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($reserva->manutencoes as $manutencao)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ $manutencao->man_data_inicio->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ ucfirst($manutencao->man_tipo) }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">{{ number_format($manutencao->man_km, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">R$ {{ number_format($manutencao->man_custo_total, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2">
                                         <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $manutencao->man_status == 'agendada' ? 'bg-blue-100 text-blue-800' :
                                                       ($manutencao->man_status == 'em_andamento' ? 'bg-yellow-100 text-yellow-800' :
                                                       ($manutencao->man_status == 'concluida' ? 'bg-green-100 text-green-800' :
                                                       ($manutencao->man_status == 'cancelada' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                            {{ ucfirst(str_replace('_', ' ', $manutencao->man_status)) }}
                                        </span>
                                    </td>
                                     <td class="px-4 py-2 whitespace-nowrap">
                                        @if(in_array($reserva->res_status, ['em_uso', 'em_revisao']))
                                        <form action="{{ route('reservas.manutencoes.detach', [$reserva, $manutencao]) }}" method="POST" onsubmit="return confirm('Desvincular esta manutenção da reserva?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Desvincular</button>
                                        </form>
                                        @else
                                        <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if($reserva->res_status == 'em_uso')
                <form action="{{ route('reservas.manutencoes.attach', $reserva) }}" method="POST" class="mt-4 border-t pt-4">
                    @csrf
                    <h6 class="text-sm font-medium text-gray-600 mb-2">Vincular Manutenção Existente</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                        <div>
                            <x-input-label for="manutencao_id" value="Selecione a Manutenção" />
                            @php
                                // Filtrar manutenções do mesmo veículo que ainda não estão vinculadas a ESTA reserva
                                $manutencoesDisponiveis = \App\Models\Manutencao::where('man_vei_id', $reserva->res_vei_id)
                                    ->where('man_emp_id', $reserva->res_emp_id)
                                    ->whereDoesntHave('reservas', function ($query) use ($reserva) { $query->where('reservas.res_id', $reserva->res_id); })
                                    ->orderBy('man_data_inicio', 'desc')->limit(20)->get();
                            @endphp
                            <select id="manutencao_id" name="manutencao_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm" required>
                                <option value="">-- Selecione --</option>
                                @foreach ($manutencoesDisponiveis as $man)
                                    <option value="{{ $man->man_id }}">
                                        {{ $man->man_data_inicio->format('d/m/y') }} - {{ ucfirst($man->man_tipo) }} - R$ {{ number_format($man->man_custo_total, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                         <x-primary-button type="submit" class="text-sm !py-1.5">
                            Vincular
                        </x-primary-button>
                    </div>
                </form>
            @endif
        </div>
     @endif

</div>
@endif
