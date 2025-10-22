{{-- resources/views/dashboard/components/veiculos/fleet-list.blade.php --}}
{{-- Este componente exibe a lista interativa de veículos (accordion). --}}

<div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalhamento de Veículos</h3>
    <div class="space-y-4">
        @forelse ($frota ?? [] as $veiculo)
            <div class="border rounded-lg overflow-hidden">
                {{-- Cabeçalho do Accordion --}}
                <div @click="openVeiculo === {{ $veiculo->vei_id }} ? openVeiculo = null : openVeiculo = {{ $veiculo->vei_id }}" class="p-4 cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                    <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-2">
                        {{-- Informações do Veículo (Placa, Modelo) --}}
                        <div class="flex items-center min-w-0">
                            <span class="font-bold text-gray-800 whitespace-nowrap">{{ $veiculo->vei_placa }}</span>
                            <span class="text-gray-600 ml-4 truncate">{{ $veiculo->vei_modelo }}</span>
                        </div>

                        {{-- Custo e Seta --}}
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-semibold text-gray-700 whitespace-nowrap">Custo Mês: R$ {{ number_format($veiculo->custo_total_mensal, 2, ',', '.') }}</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform flex-shrink-0" :class="{'rotate-180': openVeiculo === {{ $veiculo->vei_id }}}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                
                {{-- Conteúdo do Accordion --}}
                <div x-show="openVeiculo === {{ $veiculo->vei_id }}" x-collapse class="p-4 border-t bg-white">
                    @php
                        $ultimaManutencao = $veiculo->manutencoes->where('man_status', 'concluida')->first();
                        $ultimaVerificacao = $veiculo->ultimoAbastecimento;
                    @endphp
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Coluna 1: Última Manutenção --}}
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-700 border-b pb-1">Última Manutenção</h4>
                            @if($ultimaManutencao)
                                <p class="text-sm"><strong class="text-gray-600">Serviço:</strong> {{ $ultimaManutencao->servicos->pluck('ser_nome')->join(', ') ?: 'N/A' }}</p>
                                <p class="text-sm"><strong class="text-gray-600">Fornecedor:</strong> {{ $ultimaManutencao->fornecedor->for_nome_fantasia ?? 'N/A' }}</p>
                                <p class="text-sm"><strong class="text-gray-600">Data:</strong> {{ $ultimaManutencao->man_data_fim ? $ultimaManutencao->man_data_fim->format('d/m/Y') : $ultimaManutencao->man_data_inicio->format('d/m/Y') }}</p>
                                <p class="text-sm"><strong class="text-gray-600">Custo:</strong> R$ {{ number_format($ultimaManutencao->man_custo_total, 2, ',', '.') }}</p>
                            @else
                                <p class="text-sm text-gray-500">Nenhum registro de manutenção concluída.</p>
                            @endif
                        </div>

                        {{-- Coluna 2: Última Verificação (Abastecimento) --}}
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-700 border-b pb-1">Última Verificação</h4>
                            @if($ultimaVerificacao)
                                <div class="flex items-center text-sm space-x-2">
                                    @if($ultimaVerificacao->aba_pneus_calibrados)
                                        <svg class="w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        <span class="text-gray-700">Pneus Calibrados</span>
                                    @else
                                        <svg class="w-5 h-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                        <span class="text-gray-700">Pneus Não Calibrados</span>
                                    @endif
                                </div>
                                <div class="flex items-center text-sm space-x-2">
                                    @if($ultimaVerificacao->aba_oleo_verificado)
                                        <svg class="w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        <span class="text-gray-700">Óleo Verificado</span>
                                    @else
                                        <svg class="w-5 h-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                        <span class="text-gray-700">Óleo Não Verificado</span>
                                    @endif
                                </div>
                                <div class="flex items-center text-sm space-x-2">
                                     @if($ultimaVerificacao->aba_agua_verificada)
                                        <svg class="w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        <span class="text-gray-700">Água Verificada</span>
                                    @else
                                        <svg class="w-5 h-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                        <span class="text-gray-700">Água Não Verificada</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 pt-2">Em: {{ $ultimaVerificacao->aba_data->format('d/m/Y') }}</p>
                            @else
                                <p class="text-sm text-gray-500">Nenhum registro de abastecimento.</p>
                            @endif
                        </div>

                        {{-- Coluna 3: Infos Gerais --}}
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-700 border-b pb-1">Informações</h4>
                            <p class="text-sm"><strong class="text-gray-600">KM Atual:</strong> {{ number_format($veiculo->vei_km_atual, 0, ',', '.') }}</p>
                            <p class="text-sm"><strong class="text-gray-600">Combustível:</strong> {{ $veiculo->combustivelTexto }}</p>
                            <p class="text-sm"><strong class="text-gray-600">Ano:</strong> {{ $veiculo->vei_ano_fab }}/{{ $veiculo->vei_ano_mod }}</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t flex items-center space-x-4">
                        <button @click="$dispatch('open-historico', { id: {{ $veiculo->vei_id }}, placa: '{{ $veiculo->vei_placa }}' })" class="flex items-center space-x-2 text-sm px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                            <span>Histórico</span>
                        </button>
                        <button @click="$dispatch('open-analise', { veiculo: {{ json_encode($veiculo) }} })" class="flex items-center space-x-2 text-sm px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" /></svg>
                            <span>Análise Mensal</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500">Nenhum veículo ativo cadastrado.</p>
        @endforelse
    </div>
</div>

