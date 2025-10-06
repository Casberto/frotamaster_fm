<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </div>
    </x-slot>

    {{-- Verifica se o usuário tem uma empresa associada. --}}
    @if (!Auth::user()->id_empresa)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{ __("You're logged in!") }}
            </div>
        </div>
    @else
        {{-- Conteúdo principal do Dashboard --}}
        {{-- CORREÇÃO: x-data foi simplificado para controlar apenas o accordion de veículos. O estado dos modais foi movido para os próprios modais. --}}
        <div class="space-y-8" x-data="{ openVeiculo: null }">
        
            <!-- Cards de Resumo -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Veículos Ativos -->
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                         <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h6m-6 4h6m-6 4h6"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Veículos Ativos</h3>
                        <p class="mt-1 text-3xl font-bold text-gray-900">{{ $veiculosAtivos }}</p>
                    </div>
                </div>
                <!-- Alertas Próximos -->
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
                     <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Alertas Próximos</h3>
                        <p class="mt-1 text-3xl font-bold text-gray-900">{{ $alertasProximos }}</p>
                    </div>
                </div>
                <!-- Manutenções Vencidas -->
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Manutenções Vencidas</h3>
                        <p class="mt-1 text-3xl font-bold text-gray-900">{{ $manutencoesVencidas }}</p>
                    </div>
                </div>
                <!-- Custo Total do Mês -->
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
                     <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0-1V4m0 2.01V5M12 20v-1m0-1v.01m0-1.01V18m0-1.01V17m0 2.01V18m0 .01V19m0-1.01V18m0-2.01V17m0-1.01V16"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Custo Total do Mês</h3>
                        <p class="mt-1 text-3xl font-bold text-gray-900">R$ {{ number_format($custoTotalMensal, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Novos Cards de Análise -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                 <!-- Top Fornecedor -->
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
                    <div class="bg-indigo-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.25m11.25 0v-7.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21m-4.5 0H9M15 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H4.5m15 0v-7.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21m-4.5 0H12" /> </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Top Fornecedor (Mês)</h3>
                        <p class="mt-1 text-xl font-semibold text-gray-800 truncate">{{ $topFornecedor }}</p>
                    </div>
                </div>
                <!-- Serviço Mais Frequente -->
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
                     <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-1.007 1.11-1.11h2.592c.55-0.104 1.02.365 1.11 1.11l.09 1.586c.27.043.53.11.78.201l1.467-.77c.504-0.267 1.12.11 1.32.618l1.34 2.322c.2.507-.06.108-.48.134l-1.14.395c.09.262.16.533.22.811l.395 1.14c.254.524-.08.113-.59.132l-2.323 1.342c-.507.2-.618.823-.134 1.32l.77 1.467c.09.25.158.51.202.78l1.586.09c.542.09 1.008.56 1.11 1.11v2.592c.104.55-.365 1.02-1.11 1.11l-1.586.09c-.043.27-.11.53-.202.78l.77 1.467c.267.504-.11 1.12-.618 1.32l-2.323 1.34c-.507.2-.108-.06-1.34-.48l-.395-1.14a8.21 8.21 0 01-.81.22l-1.14.395c-.524.254-1.13-.08-1.32-.59l-1.342-2.323c-.2-.507.06-.618.48-1.34l1.14-.395a8.21 8.21 0 01-.22-.81l-.395-1.14c-.254-.524.08-1.13.59-1.32l2.323-1.342c.507-.2.618-.823.134-1.32l-.77-1.467c-.09-.25-.158-.51-.202-.78l-1.586-.09c-.542-.09-1.008-.56-1.11-1.11V6.65c-.104-.55.365-1.02 1.11-1.11h1.586c.27-.043.53-.11.78-.201L9.594 3.94zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" /> </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Serviço Mais Frequente</h3>
                        <p class="mt-1 text-xl font-semibold text-gray-800 truncate">{{ $servicoMaisFrequente }}</p>
                    </div>
                </div>
                 <!-- Custo Médio por KM -->
                <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
                     <div class="bg-cyan-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-cyan-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.75A.75.75 0 013 4.5h.75m0 0H21m-18 0h18M3 6.75h18M3 9h18m-18 2.25h18m-18 2.25h18m-18 2.25h18M3 16.5h18m-18-3.75h18m-18-3.75h18m-18-3.75h18" /> </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Custo Médio por KM</h3>
                        <p class="mt-1 text-xl font-semibold text-gray-800">R$ {{ number_format($custoMedioPorKm, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Coluna Frota de Veículos --}}
                <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Frota de Veículos</h3>
                    <div class="space-y-4">
                        @forelse ($frota as $veiculo)
                            <div class="border rounded-lg overflow-hidden">
                                <div @click="openVeiculo === {{ $veiculo->vei_id }} ? openVeiculo = null : openVeiculo = {{ $veiculo->vei_id }}" class="flex justify-between items-center p-4 cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                    <div class="flex items-center space-x-4">
                                        <span class="font-bold text-gray-800">{{ $veiculo->vei_placa }}</span>
                                        <span class="text-gray-600">{{ $veiculo->vei_modelo }}</span>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm font-semibold text-gray-700">Custo Mês: R$ {{ number_format($veiculo->custo_total_mensal, 2, ',', '.') }}</span>
                                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" :class="{'rotate-180': openVeiculo === {{ $veiculo->vei_id }}}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                
                                <div x-show="openVeiculo === {{ $veiculo->vei_id }}" x-collapse class="p-4 border-t bg-white">
                                    @php
                                        $ultimaManutencao = $veiculo->manutencoes->first();
                                        $ultimaVerificacao = $veiculo->ultimoAbastecimento;
                                    @endphp
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        {{-- Coluna 1: Última Manutenção --}}
                                        <div class="space-y-3">
                                            <h4 class="font-semibold text-gray-700 border-b pb-1">Última Manutenção</h4>
                                            @if($ultimaManutencao)
                                                <p class="text-sm"><strong class="text-gray-600">Serviço:</strong> {{ $ultimaManutencao->servicos->pluck('ser_nome')->join(', ') ?: 'N/A' }}</p>
                                                <p class="text-sm"><strong class="text-gray-600">Fornecedor:</strong> {{ $ultimaManutencao->fornecedor->for_nome_fantasia ?? 'N/A' }}</p>
                                                <p class="text-sm"><strong class="text-gray-600">Data:</strong> {{ $ultimaManutencao->man_data_inicio->format('d/m/Y') }}</p>
                                                <p class="text-sm"><strong class="text-gray-600">Custo:</strong> R$ {{ number_format($ultimaManutencao->man_custo_total, 2, ',', '.') }}</p>
                                            @else
                                                <p class="text-sm text-gray-500">Nenhum registro de manutenção.</p>
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
                                            <p class="text-sm"><strong class="text-gray-600">Combustível:</strong> {{ Str::ucfirst($veiculo->vei_combustivel) }}</p>
                                            <p class="text-sm"><strong class="text-gray-600">Ano:</strong> {{ $veiculo->vei_ano_fab }}/{{ $veiculo->vei_ano_mod }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-6 pt-4 border-t flex items-center space-x-4">
                                        {{-- CORREÇÃO: Botão agora dispara um evento global 'open-historico' com os dados necessários --}}
                                        <button @click="$dispatch('open-historico', { id: {{ $veiculo->vei_id }}, placa: '{{ $veiculo->vei_placa }}' })" class="flex items-center space-x-2 text-sm px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                            <span>Histórico</span>
                                        </button>
                                        {{-- CORREÇÃO: Botão agora dispara um evento global 'open-analise' com o objeto completo do veículo --}}
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

                {{-- Coluna Próximos Lembretes --}}
                <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Próximos Lembretes</h3>
                     <div class="space-y-4">
                        @forelse ($proximosLembretes as $lembrete)
                            <div class="border-l-4 border-yellow-400 pl-4 py-2">
                                <p class="font-semibold text-gray-800">{{ $lembrete->man_descricao ?? 'Agendamento' }}</p>
                                <p class="text-sm text-gray-600">{{ $lembrete->veiculo->vei_placa }} - {{ $lembrete->veiculo->vei_modelo }}</p>
                                <p class="text-sm text-yellow-600 font-medium">Vence {{ \Carbon\Carbon::parse($lembrete->man_data_inicio)->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-center text-gray-500">Nenhum lembrete futuro.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        @push('modals')
            <!-- Modal de Histórico -->
            {{-- CORREÇÃO: Modal agora tem seu próprio estado (x-data), ouve o evento 'open-historico' e contém suas próprias funções --}}
            <div x-data="{
                    isOpen: false,
                    historicoLoading: false,
                    historicoData: { man: [], abs: [] },
                    veiculoPlaca: '',
                    historicoUrlTemplate: '{{ route('veiculos.historico', ['id' => ':id']) }}',
                    fetchHistorico(veiculoId, veiculoPlaca) {
                        this.isOpen = true;
                        this.historicoLoading = true;
                        this.veiculoPlaca = veiculoPlaca;
                        this.historicoData = { man: [], abs: [] };
                        const url = this.historicoUrlTemplate.replace(':id', veiculoId);
                        fetch(url)
                            .then(response => {
                                if (!response.ok) throw new Error('Network response was not ok');
                                return response.json();
                            })
                            .then(data => {
                                this.historicoData.man = data.manutencoes || [];
                                this.historicoData.abs = data.abastecimentos || [];
                            })
                            .catch(error => {
                                console.error('Houve um problema com a operação de busca:', error);
                                alert('Não foi possível carregar o histórico.');
                            })
                            .finally(() => {
                                this.historicoLoading = false;
                            });
                    },
                    formatDate(dateString) {
                        if (!dateString) return 'N/A';
                        const date = new Date(dateString.split('T')[0] + 'T00:00:00');
                        if (isNaN(date)) { return 'Data inválida'; }
                        const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
                        return date.toLocaleDateString('pt-BR', options);
                    },
                    formatCurrency(value) {
                        if (typeof value !== 'number') { value = parseFloat(value) || 0; }
                        return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                    }
                }"
                @open-historico.window="fetchHistorico($event.detail.id, $event.detail.placa)"
                x-show="isOpen"
                @keydown.escape.window="isOpen = false"
                class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/75 flex items-start justify-center p-4 sm:p-6 lg:p-10"
                x-transition.opacity
                x-cloak>

                <div @click.away="isOpen = false"
                    class="bg-white rounded-xl shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">

                    <!-- Cabeçalho -->
                    <div class="flex-shrink-0 flex justify-between items-center p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Histórico do Veículo - <span x-text="veiculoPlaca"></span>
                        </h3>
                        <button @click="isOpen = false" class="text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Corpo -->
                    <div class="flex-grow p-6 overflow-y-auto bg-slate-50">

                        <!-- Loading -->
                        <div x-show="historicoLoading" class="flex justify-center items-center h-64">
                            <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0
                                    c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <!-- Conteúdo -->
                        <div x-show="!historicoLoading" class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:items-start" x-cloak>

                            <!-- COLUNA MANUTENÇÕES -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-4 text-center lg:text-left">
                                    Últimas 5 Manutenções
                                </h4>

                                <!-- Estado vazio -->
                                <div x-show="historicoData.man.length === 0" x-transition>
                                    <div
                                        class="flex flex-col items-center justify-center text-center text-gray-500 p-6 border rounded-lg bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-10 w-10 mb-2 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42
                                                15.17l2.496-3.03c.317-.384.74-.626
                                                1.208-.766M11.42 15.17l-4.655
                                                5.653a2.548 2.548 0
                                                11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164
                                                1.163-.188 1.743-.14a4.5 4.5 0
                                                004.486-6.336l-3.276 3.277a3.004 3.004 0
                                                01-2.25-2.25l3.276-3.276a4.5 4.5 0
                                                00-6.336 4.486c.091 1.076-.071
                                                2.264-.904 2.95l-.102.085m-1.745
                                                1.437L5.909 7.5H4.5L2.25
                                                3.75l1.5-1.5L7.5 4.5v1.409l4.26
                                                4.26m-1.745 1.437 1.745-1.437m6.615
                                                8.206L15.75 15.75M4.867
                                                19.125h.008v.008h-.008v-.008z"/>
                                        </svg>
                                        <p>Nenhuma manutenção encontrada.</p>
                                    </div>
                                </div>

                                <!-- Linha do tempo -->
                                <div x-show="historicoData.man.length > 0"
                                    class="relative pl-6 border-l-2 border-slate-200" x-transition>
                                    <template x-for="(manutencao, index) in historicoData.man" :key="index">
                                        <div x-show="manutencao && manutencao.man_id" class="mb-6 relative">
                                            <div
                                                class="absolute -left-[31px] top-1 flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 ring-8 ring-slate-50">
                                                <svg class="h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11.42 15.17L17.25 21A2.652 2.652 0
                                                        0021 17.25l-5.877-5.877M11.42
                                                        15.17l2.496-3.03c.317-.384.74-.626
                                                        1.208-.766M11.42 15.17l-4.655
                                                        5.653a2.548 2.548 0
                                                        11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164
                                                        1.163-.188 1.743-.14a4.5 4.5 0
                                                        004.486-6.336l-3.276 3.277a3.004 3.004 0
                                                        01-2.25-2.25l3.276-3.276a4.5 4.5 0
                                                        00-6.336 4.486c.091 1.076-.071
                                                        2.264-.904 2.95l-.102.085m-1.745
                                                        1.437L5.909 7.5H4.5L2.25
                                                        3.75l1.5-1.5L7.5 4.5v1.409l4.26
                                                        4.26m-1.745 1.437 1.745-1.437m6.615
                                                        8.206L15.75 15.75M4.867
                                                        19.125h.008v.008h-.008v-.008z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-4 p-4 bg-white rounded-lg border shadow-sm">
                                                <p class="font-semibold text-gray-800"
                                                x-text="manutencao.servicos.map(s => s.ser_nome).join(', ') || 'Manutenção'"></p>
                                                <p class="text-sm text-gray-500"
                                                x-text="`Data: ${formatDate(manutencao.man_data_inicio)}`"></p>
                                                <p class="text-sm text-gray-500"
                                                x-text="`Fornecedor: ${manutencao.fornecedor ? manutencao.fornecedor.for_nome_fantasia : 'N/A'}`"></p>
                                                <p class="text-sm font-bold text-gray-700"
                                                x-text="`Custo: ${formatCurrency(manutencao.man_custo_total)}`"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- COLUNA ABASTECIMENTOS -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-4 text-center lg:text-left">
                                    Últimos 5 Abastecimentos
                                </h4>

                                <!-- Estado vazio -->
                                <div x-show="historicoData.abs.length === 0" x-transition
                                    class="flex flex-col items-center justify-center text-center text-gray-500 p-6 border rounded-lg bg-white">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-10 w-10 mb-2 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 7.5h.01M8.25 12h.01M8.25
                                            16.5h.01M12 7.5h.01M12 12h.01M12
                                            16.5h.01M15.75 7.5h.01M15.75 12h.01M15.75
                                            16.5h.01M4.5 12a7.5 7.5 0
                                            0115 0v2.25a2.25 2.25 0
                                            01-2.25 2.25H6.75A2.25 2.25 0
                                            014.5 14.25V12z"/>
                                    </svg>
                                    <p>Nenhum abastecimento encontrado.</p>
                                </div>

                                <!-- Linha do tempo -->
                                <div x-show="historicoData.abs.length > 0"
                                    class="relative pl-6 border-l-2 border-slate-200" x-transition>
                                    <template x-for="(abastecimento, index) in historicoData.abs" :key="index">
                                        <div x-show="abastecimento && abastecimento.aba_id" class="mb-6 relative">
                                            <div
                                                class="absolute -left-[31px] top-1 flex h-6 w-6 items-center justify-center rounded-full bg-green-100 ring-8 ring-slate-50">
                                                <svg class="h-4 w-4 text-green-600"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M8.25 7.5h.01M8.25 12h.01M8.25
                                                        16.5h.01M12 7.5h.01M12 12h.01M12
                                                        16.5h.01M15.75 7.5h.01M15.75
                                                        12h.01M15.75 16.5h.01M4.5 12a7.5
                                                        7.5 0 0115 0v2.25a2.25 2.25 0
                                                        01-2.25 2.25H6.75A2.25 2.25 0
                                                        014.5 14.25V12z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-4 p-4 bg-white rounded-lg border shadow-sm">
                                                <p class="font-semibold text-gray-800"
                                                x-text="`Data: ${formatDate(abastecimento.aba_data)}`"></p>
                                                <p class="text-sm text-gray-500"
                                                x-text="`Posto: ${abastecimento.fornecedor?.for_nome_fantasia ?? 'N/A'}`"></p>
                                                <p class="text-sm text-gray-500"
                                                x-text="`Qtd: ${parseFloat(abastecimento.aba_qtd).toFixed(2)} ${abastecimento.aba_und_med}`"></p>
                                                <p class="text-sm font-bold text-gray-700"
                                                x-text="`Total: ${formatCurrency(abastecimento.aba_vlr_tot)}`"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal de Análise Mensal -->
            {{-- CORREÇÃO: Modal agora tem seu próprio estado (x-data) e ouve o evento 'open-analise' --}}
            <div x-data="{
                    isOpen: false,
                    analiseData: {},
                    formatCurrency(value) {
                        if (typeof value !== 'number') { value = parseFloat(value) || 0; }
                        return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                    },
                    calculatePercentageChange(current, previous) {
                        if (previous === 0) { return current > 0 ? 100 : 0; }
                        const change = ((current - previous) / previous) * 100;
                        return change;
                    }
                }"
                @open-analise.window="isOpen = true; analiseData = $event.detail.veiculo"
                x-show="isOpen" 
                @keydown.escape.window="isOpen = false" 
                class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/75 flex items-start justify-center p-4 sm:p-6 lg:p-10" x-transition.opacity x-cloak>
                <div @click.away="isOpen = false" class="bg-slate-50 rounded-xl shadow-xl w-full max-w-4xl">
                    <div class="flex-shrink-0 flex justify-between items-center p-4 border-b bg-white rounded-t-xl">
                        <h3 class="text-lg font-semibold text-gray-800">Análise de Custos - <span x-text="analiseData.vei_placa"></span></h3>
                        <button @click="isOpen = false" class="text-gray-400 hover:text-gray-600">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div class="p-8 overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                            <!-- Mês Anterior -->
                            <div class="bg-white p-6 rounded-lg shadow-sm border">
                                <h4 class="text-sm font-semibold text-gray-500 mb-2">Mês Anterior</h4>
                                <p class="text-3xl font-bold text-gray-800" x-text="formatCurrency(analiseData.custo_total_anterior)"></p>
                                <div class="mt-4 pt-4 border-t space-y-2 text-sm">
                                    <p class="flex justify-between text-gray-600"><span>Manutenção:</span> <span class="font-medium" x-text="formatCurrency(analiseData.custo_anterior_manutencao)"></span></p>
                                    <p class="flex justify-between text-gray-600"><span>Abastecimento:</span> <span class="font-medium" x-text="formatCurrency(analiseData.custo_anterior_abastecimento)"></span></p>
                                </div>
                            </div>

                            <!-- Mês Atual -->
                            <div class="bg-white p-6 rounded-lg shadow-lg border-2 border-blue-500 relative">
                                <span class="absolute -top-3 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full">Mês Atual</span>
                                <h4 class="text-sm font-semibold text-gray-500 mb-2 invisible">Mês Atual</h4> <!-- hidden title for alignment -->
                                <p class="text-4xl font-bold text-blue-600" x-text="formatCurrency(analiseData.custo_total_mensal)"></p>

                                <div class="flex items-center justify-center text-sm font-semibold mt-2"
                                    x-show="analiseData.custo_total_anterior > 0"
                                    :class="{
                                        'text-red-500': calculatePercentageChange(analiseData.custo_total_mensal, analiseData.custo_total_anterior) > 0,
                                        'text-green-500': calculatePercentageChange(analiseData.custo_total_mensal, analiseData.custo_total_anterior) < 0,
                                        'text-gray-500': calculatePercentageChange(analiseData.custo_total_mensal, analiseData.custo_total_anterior) == 0
                                    }">
                                    <svg x-show="calculatePercentageChange(analiseData.custo_total_mensal, analiseData.custo_total_anterior) !== 0" class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="{'rotate-180': calculatePercentageChange(analiseData.custo_total_mensal, analiseData.custo_total_anterior) < 0}">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                    <span x-text="`${Math.abs(calculatePercentageChange(analiseData.custo_total_mensal, analiseData.custo_total_anterior)).toFixed(0)}%`"></span>
                                </div>

                                <div class="mt-4 pt-4 border-t space-y-2 text-sm">
                                    <p class="flex justify-between text-gray-600"><span>Manutenção:</span> <span class="font-medium" x-text="formatCurrency(analiseData.custo_mensal_manutencao)"></span></p>
                                    <p class="flex justify-between text-gray-600"><span>Abastecimento:</span> <span class="font-medium" x-text="formatCurrency(analiseData.custo_mensal_abastecimento)"></span></p>
                                </div>
                            </div>

                            <!-- Média 12 Meses -->
                            <div class="bg-white p-6 rounded-lg shadow-sm border">
                                <h4 class="text-sm font-semibold text-gray-500 mb-2">Média 12 Meses</h4>
                                <p class="text-3xl font-bold text-gray-800" x-text="formatCurrency(analiseData.media_custo_total_12_meses)"></p>
                                 <div class="mt-4 pt-4 border-t space-y-2 text-sm text-transparent">
                                     <!-- Mantém o espaçamento igual aos outros cards -->
                                    <p><span>-</span></p>
                                    <p><span>-</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endpush
    @endif
</x-app-layout>

