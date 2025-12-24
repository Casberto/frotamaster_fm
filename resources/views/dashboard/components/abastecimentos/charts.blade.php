{{-- 4.3 - Gráficos de Abastecimentos --}}
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
        </svg>
        Análises e Gráficos
    </h3>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 min-w-0">
        
        {{-- Lista: Estimativa de Consumo (Substituindo Gráfico 1) --}}
        <div class="bg-gray-50 p-4 rounded-lg min-w-0 flex flex-col">
            <div class="flex justify-between items-center mb-4">
                 <h4 class="text-sm font-semibold text-gray-700">Estimativa de Consumo (Tanque Cheio)</h4>
                 <div class="relative group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-gray-400 cursor-help">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
                    </svg>
                    <div class="absolute right-0 bottom-full mb-2 hidden group-hover:block w-64 p-2 bg-gray-800 text-white text-xs rounded shadow-lg z-10">
                        Médias calculadas apenas com abastecimentos de tanque cheio. Necessário ao menos 2 registros no período.
                    </div>
                </div>
            </div>
           
            <div class="overflow-y-auto max-h-[250px] pr-2 space-y-3 custom-scrollbar">
                @if(isset($indicadores['estimativa_consumo']) && count($indicadores['estimativa_consumo']) > 0)
                    @foreach($indicadores['estimativa_consumo'] as $estimate)
                        <div class="bg-white p-3 rounded border border-gray-100 shadow-sm">
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
                    @endforeach
                @else
                    <div class="h-full flex flex-col items-center justify-center text-gray-500 text-sm italic p-4">
                        <svg class="w-8 h-8 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Nenhum dado suficiente para cálculo.
                    </div>
                @endif
            </div>
        </div>

        {{-- Gráfico 2: Custo por tipo de combustível --}}
        <div class="bg-gray-50 p-4 rounded-lg min-w-0 flex flex-col">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">Custo por Tipo de Combustível</h4>
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 flex-1">
                <div class="h-[200px] w-[200px] relative shrink-0">
                     <canvas id="chartCustoCombustivel"></canvas>
                      {{-- Centro do Doughnut --}}
                     <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="text-center">
                            <span class="block text-xs text-gray-500">Total</span>
                            <span id="totalCustoLabel" class="block text-sm font-bold text-gray-800"></span>
                        </div>
                     </div>
                </div>
                
                {{-- Legenda Customizada --}}
                <div id="legendCustoCombustivel" class="w-full space-y-3">
                    {{-- Preenchido via JS --}}
                </div>
            </div>
        </div>

        {{-- Gráfico 3: Ranking de eficiência - Motoristas --}}
        <div class="bg-gray-50 p-4 rounded-lg min-w-0">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">Top 10 Motoristas Mais Eficientes</h4>
            <div class="h-[300px] w-full max-w-full relative">
                <canvas id="chartRankingMotoristas"></canvas>
            </div>
        </div>

        {{-- Gráfico 4: Ranking de eficiência - Veículos --}}
        <div class="bg-gray-50 p-4 rounded-lg min-w-0">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">Top 10 Veículos Mais Eficientes</h4>
            <div class="h-[300px] w-full max-w-full relative">
                <canvas id="chartRankingVeiculos"></canvas>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dados do PHP
    const custoCombustivel = @json($fuelingData['graficos']['custo_combustivel'] ?? []);
    const rankingMotoristas = @json($fuelingData['graficos']['ranking_motoristas'] ?? []);
    const rankingVeiculos = @json($fuelingData['graficos']['ranking_veiculos'] ?? []);

    // Formatter para moeda
    const moneyFormatter = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });

    // Gráfico 2: Custo por Combustível (Doughnut)
    const custoCombLabels = Object.keys(custoCombustivel);
    const custoCombData = Object.values(custoCombustivel).map(Number); // Garantir numeros
    const totalCusto = custoCombData.reduce((a, b) => a + b, 0);

    // Atualizar Label Central de Total
    const totalLabelEl = document.getElementById('totalCustoLabel');
    if(totalLabelEl) {
        // Formatar de forma compacta se for muito grande, ou normal
        totalLabelEl.innerText = moneyFormatter.format(totalCusto);
    }
    
    // Cores para os gráficos e legenda
    const chartColors = [
        '#3b82f6', // Azul
        '#10b981', // Verde
        '#f59e0b', // Amarelo
        '#ef4444', // Vermelho
        '#8b5cf6', // Roxo
        '#ec4899'  // Rosa
    ];

    // Gerar Legenda HTML
    const legendContainer = document.getElementById('legendCustoCombustivel');
    
    if (custoCombLabels.length > 0) {
        legendContainer.innerHTML = ''; // Limpar
        custoCombLabels.forEach((label, index) => {
            const value = custoCombData[index];
            const percent = totalCusto > 0 ? ((value / totalCusto) * 100).toFixed(1) : 0;
            const color = chartColors[index % chartColors.length];

            const itemHtml = `
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full mr-2" style="background-color: ${color}"></span>
                        <span class="text-gray-600 font-medium">${label}</span>
                    </div>
                    <div class="text-right">
                        <div class="text-gray-900 font-semibold">${moneyFormatter.format(value)}</div>
                        <div class="text-xs text-gray-500">${percent}%</div>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                   <div class="bg-blue-600 h-1.5 rounded-full" style="width: ${percent}%; background-color: ${color}"></div>
                </div>
            `;
            const div = document.createElement('div');
            div.innerHTML = itemHtml;
            legendContainer.appendChild(div);
        });
    } else {
        legendContainer.innerHTML = '<p class="text-sm text-gray-500 text-center">Sem dados de abastecimento.</p>';
        if(totalLabelEl) totalLabelEl.innerText = 'R$ 0,00';
    }

    new Chart(document.getElementById('chartCustoCombustivel'), {
        type: 'doughnut',
        data: {
            labels: custoCombLabels,
            datasets: [{
                data: custoCombData,
                backgroundColor: chartColors,
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%', // Espessura do anel
            plugins: {
                legend: {
                    display: false // Usamos nossa legenda customizada
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            const percentage = totalCusto > 0 ? ((value / totalCusto) * 100).toFixed(1) + '%' : '0%';
                            return context.label + ': ' + moneyFormatter.format(value) + ' (' + percentage + ')';
                        }
                    }
                }
            }
        }
    });

    // Gráfico 3: Ranking Motoristas
    const motLabels = rankingMotoristas.map(m => m.nome || 'N/A');
    const motData = rankingMotoristas.map(m => m.media || 0);
    
    new Chart(document.getElementById('chartRankingMotoristas'), {
        type: 'bar',
        data: {
            labels: motLabels,
            datasets: [{
                label: 'Média km/L',
                data: motData,
                backgroundColor: '#10b981',
                borderColor: '#059669',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'km/L'
                    }
                }
            }
        }
    });

    // Gráfico 4: Ranking Veículos
    const veiLabels = rankingVeiculos.map(v => v.placa || 'N/A');
    const veiData = rankingVeiculos.map(v => v.media || 0);
    
    new Chart(document.getElementById('chartRankingVeiculos'), {
        type: 'bar',
        data: {
            labels: veiLabels,
            datasets: [{
                label: 'Média km/L',
                data: veiData,
                backgroundColor: '#3b82f6',
                borderColor: '#2563eb',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'km/L'
                    }
                }
            }
        }
    });
});
</script>
@endpush
