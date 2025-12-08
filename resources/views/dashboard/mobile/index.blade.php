<div class="min-h-screen bg-gray-50 pb-24 w-full overflow-x-hidden"> 
    {{-- Mudei w-full para max-w-full e removi overflow-x-hidden. O layout deve se comportar, não ser forçado. --}}
    
    {{-- Header / Welcome Section --}}
    <div class="bg-white px-4 py-4 shadow-sm border-b border-gray-100 mb-4">
        <div class="flex items-center justify-between">
            <div class="min-w-0 flex-1 pr-4"> {{-- Adicionado min-w-0 e flex-1 para impedir que texto longo quebre o layout --}}
                <h2 class="text-xl font-bold text-gray-800 truncate">Olá, {{ Auth::user()->name }}</h2>
                <p class="text-sm text-gray-500 mt-1 truncate">Resumo da sua frota.</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-full flex-shrink-0"> {{-- flex-shrink-0 impede que o ícone seja esmagado --}}
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </div>
        </div>
        
        {{-- Mobile Filter --}}
        <div x-data="{ openFilter: false }" class="mt-4">
            <button @click="openFilter = !openFilter" class="w-full flex items-center justify-between text-sm text-blue-600 font-medium focus:outline-none">
                <span>
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filtrar Período
                </span>
                <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': openFilter}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="openFilter" class="mt-3 bg-gray-50 p-4 rounded-xl border border-gray-100" style="display: none;">
                <form action="{{ route('dashboard') }}" method="GET">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Início</label>
                            <input type="date" name="start_date" value="{{ $filterStartDate ?? '' }}" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Fim</label>
                            <input type="date" name="end_date" value="{{ $filterEndDate ?? '' }}" class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="mt-3 flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-sm hover:bg-blue-700 transition w-full">Aplicar Filtro</button>
                    </div>
                </form>
            </div>
            
            {{-- Active Filter Badge --}}
            @if(request('start_date') || request('end_date'))
            <div class="mt-2 flex items-center">
                 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Filtro Ativo: {{ \Carbon\Carbon::parse($filterStartDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($filterEndDate)->format('d/m/Y') }}
                    <a href="{{ route('dashboard') }}" class="ml-1.5 text-blue-600 hover:text-blue-800">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </a>
                </span>
            </div>
            @endif
        </div>
    </div>

    <div class="px-4 space-y-6">
        
        {{-- 1. Key Indicators Grid --}}
        <div class="grid grid-cols-2 gap-3">
            {{-- Veículos Ativos --}}
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full min-w-0">
                <div class="text-gray-500 text-[10px] font-bold uppercase tracking-wider truncate">Veículos</div>
                <div class="mt-2 flex items-baseline wrap">
                    <span class="text-2xl font-extrabold text-gray-900">{{ $indicadores['veiculos_ativos'] }}</span>
                    <span class="ml-1 text-xs text-gray-400 font-medium">/ {{ $indicadores['veiculos_total'] }}</span>
                </div>
            </div>

            {{-- Gasto Total Mês (Combined) --}}
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full min-w-0">
                <div class="text-gray-500 text-[10px] font-bold uppercase tracking-wider truncate">Gasto Total (Mês)</div>
                <div class="mt-2">
                    <span class="text-lg font-extrabold text-gray-900 tracking-tight truncate block">R$ {{ number_format($indicadores['custo_total_mes'], 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Gasto Manutenção --}}
             <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full min-w-0 {{ $indicadores['custo_manutencao_mes'] > 0 ? 'ring-1 ring-red-50' : '' }}">
                <div class="flex items-center space-x-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                    <div class="text-gray-500 text-[10px] font-bold uppercase tracking-wider truncate">Manutenção</div>
                </div>
                <div class="mt-2">
                    <span class="text-lg font-extrabold text-gray-900 tracking-tight truncate block">R$ {{ number_format($indicadores['custo_manutencao_mes'], 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Gasto Abastecimento --}}
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full min-w-0 {{ $indicadores['custo_abastecimento_mes'] > 0 ? 'ring-1 ring-blue-50' : '' }}">
                 <div class="flex items-center space-x-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                    <div class="text-gray-500 text-[10px] font-bold uppercase tracking-wider truncate">Abastecimento</div>
                </div>
                <div class="mt-2">
                    <span class="text-lg font-extrabold text-gray-900 tracking-tight truncate block">R$ {{ number_format($indicadores['custo_abastecimento_mes'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Consumption Estimates Section (Per Vehicle) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
             <div class="bg-blue-50 px-4 py-3 border-b border-blue-100 rounded-t-2xl flex justify-between items-center" x-data="{ showDisclaimer: false }">
                <h4 class="text-sm font-bold text-blue-800">Estimativa de Consumo</h4>
                <div class="relative">
                    <button @click="showDisclaimer = !showDisclaimer" class="text-blue-600 hover:text-blue-800 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="showDisclaimer" @click.away="showDisclaimer = false" class="absolute right-0 mt-2 w-64 p-3 bg-white rounded-lg shadow-lg border border-gray-100 z-10 text-xs text-gray-600" style="display: none;">
                        <p><strong>Atenção:</strong> Estes valores são estimativas baseadas apenas em abastecimentos onde o tanque foi completamente cheio ("Tanque Cheio"). Para maior precisão, registre sempre se encheu o tanque. Mostrado individualmente por veículo.</p>
                    </div>
                </div>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse($indicadores['estimativa_consumo'] as $estimate)
                    <div class="p-4">
                        <div class="text-xs font-bold text-gray-700 mb-2 truncate">{{ $estimate['veiculo'] }}</div>
                        <div class="grid grid-cols-3 gap-2 text-center divide-x divide-gray-100">
                             <div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Gasolina</div>
                                <div class="text-sm font-bold text-gray-800">
                                    {{ $estimate['medias']['Gasolina'] ?? '--' }} <span class="text-[10px] text-gray-500 font-normal">km/l</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Etanol</div>
                                <div class="text-sm font-bold text-gray-800">
                                    {{ $estimate['medias']['Etanol'] ?? '--' }} <span class="text-[10px] text-gray-500 font-normal">km/l</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Diesel</div>
                                <div class="text-sm font-bold text-gray-800">
                                    {{ $estimate['medias']['Diesel'] ?? '--' }} <span class="text-[10px] text-gray-500 font-normal">km/l</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-sm text-gray-500">
                        Nenhum dado disponível. Necessário ao menos 2 abastecimentos com tanque cheio no período.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Alerts Section --}}
        @if($indicadores['manutencoes_vencidas'] > 0)
        <div class="bg-red-50 border border-red-100 p-4 rounded-2xl shadow-sm animate-pulse">
            <div class="flex items-start">
                <div class="flex-shrink-0 bg-red-100 rounded-full p-1">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 w-full min-w-0"> {{-- min-w-0 aqui é CRUCIAL para o texto quebrar linha corretamente --}}
                    <h3 class="text-sm font-bold text-red-800 truncate">Atenção Necessária</h3>
                    <p class="text-sm text-red-700 mt-1 break-words"> {{-- break-words garante que nada vaze --}}
                        Você tem <span class="font-bold">{{ $indicadores['manutencoes_vencidas'] }} manutenções vencidas</span>.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- 2. Charts Section --}}
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-gray-800 px-1">Análise Financeira</h3>
            
            {{-- Evolução de Custos --}}
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Evolução de Custos (6 Meses)</h4>
                {{-- AQUI ESTÁ A CORREÇÃO PRINCIPAL DO SEU LAYOUT --}}
                {{-- Adicionei 'w-full overflow-hidden' para forçar o canvas a respeitar a largura --}}
                <div class="relative h-48 w-full overflow-hidden">
                    <canvas id="mobileChartEvolucaoCustos"></canvas>
                </div>
            </div>

            {{-- Composição de Custos --}}
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Composição (Mês Atual)</h4>
                <div class="relative h-48 w-full overflow-hidden flex justify-center">
                    <canvas id="mobileChartComposicaoCustos"></canvas>
                </div>
            </div>
        </div>

        {{-- 3. Operational Summary --}}
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-gray-800 px-1">Resumo Operacional</h3>

            {{-- Veículos com Problemas --}}
            @if(count($operacional['veiculos_problemas']) > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-red-50 px-4 py-3 border-b border-red-100">
                    <h4 class="text-sm font-bold text-red-800 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Veículos com Problemas
                    </h4>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($operacional['veiculos_problemas'] as $veiculo)
                    <div class="p-4 flex items-center justify-between">
                        <div class="min-w-0 pr-2"> {{-- Contenção de texto --}}
                            <div class="font-bold text-gray-800 truncate">{{ $veiculo->vei_placa }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ $veiculo->vei_modelo }}</div>
                        </div>
                        <span class="px-2 py-1 bg-red-100 text-red-700 text-[10px] font-bold uppercase rounded-full flex-shrink-0">
                            Manutenção Vencida
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Próximas Manutenções --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                    <h4 class="text-sm font-bold text-gray-700">Próximas Manutenções</h4>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($operacional['proximas_manutencoes'] as $manutencao)
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-bold text-gray-800">{{ $manutencao->veiculo->vei_placa }}</span>
                            <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full border border-green-100 flex-shrink-0 ml-2">
                                {{ \Carbon\Carbon::parse($manutencao->man_data_inicio)->format('d/m') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $manutencao->man_descricao ?? 'Manutenção Agendada' }}</p>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-500 text-sm">Nenhuma manutenção próxima.</div>
                    @endforelse
                </div>
            </div>

            {{-- Abastecimentos Recentes --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                    <h4 class="text-sm font-bold text-gray-700">Últimos Abastecimentos</h4>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($operacional['abastecimentos_recentes'] as $abastecimento)
                    <div class="p-4 flex justify-between items-center">
                        <div class="min-w-0 pr-2">
                            <div class="font-bold text-gray-800 truncate">{{ $abastecimento->veiculo->vei_placa }}</div>
                            <div class="text-xs text-gray-500 truncate">
                                {{ \Carbon\Carbon::parse($abastecimento->aba_data)->format('d/m H:i') }} • {{ $abastecimento->aba_litros }}L
                            </div>
                        </div>
                        <div class="font-bold text-gray-900 flex-shrink-0">
                            R$ {{ number_format($abastecimento->aba_vlr_tot, 2, ',', '.') }}
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-500 text-sm">Nenhum abastecimento recente.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 4. Highlights (Veículos Mais Caros) --}}
        <div class="space-y-4">
             <h3 class="text-lg font-bold text-gray-800 px-1">Destaques</h3>
             <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                    <h4 class="text-sm font-bold text-gray-700">Top 5 Custos (Mês)</h4>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($destaques['veiculos_mais_caros'] as $item)
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center min-w-0"> {{-- min-w-0 novamente --}}
                            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xs mr-3 flex-shrink-0">
                                {{ $loop->iteration }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-gray-800 truncate">{{ $item['veiculo']->vei_placa }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ $item['veiculo']->vei_modelo }}</div>
                            </div>
                        </div>
                        <div class="font-bold text-gray-900 flex-shrink-0 ml-2">
                            R$ {{ number_format($item['valor'], 2, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Evolução de Custos (Mobile)
        const ctxEvolucaoMobile = document.getElementById('mobileChartEvolucaoCustos').getContext('2d');
        new Chart(ctxEvolucaoMobile, {
            type: 'line',
            data: {
                labels: @json($graficos['evolucao_custos']['labels']),
                datasets: [{
                    label: 'Custo Total',
                    data: @json($graficos['evolucao_custos']['data']),
                    borderColor: 'rgb(79, 70, 229)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 2,
                    pointRadius: 3,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return 'R$ ' + context.parsed.y.toLocaleString('pt-BR');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 10 },
                            callback: function(value) {
                                if(value >= 1000) return 'R$ ' + (value/1000).toFixed(0) + 'k';
                                return value;
                            }
                        },
                        grid: { borderDash: [2, 4] }
                    },
                    x: {
                        ticks: { font: { size: 10 }, maxRotation: 45, minRotation: 0 }, // Evita que labels longos empurrem o layout
                        grid: { display: false }
                    }
                }
            }
        });

        // Composição de Custos (Mobile)
        const ctxComposicaoMobile = document.getElementById('mobileChartComposicaoCustos').getContext('2d');
        new Chart(ctxComposicaoMobile, {
            type: 'doughnut',
            data: {
                labels: @json($graficos['composicao_custos']['labels']),
                datasets: [{
                    data: @json($graficos['composicao_custos']['data']),
                    backgroundColor: [
                        'rgb(239, 68, 68)', // Manutenção
                        'rgb(59, 130, 246)', // Combustível
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 11 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = total > 0 ? ((value / total) * 100).toFixed(1) + "%" : "0%";
                                return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value) + ' (' + percentage + ')';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>