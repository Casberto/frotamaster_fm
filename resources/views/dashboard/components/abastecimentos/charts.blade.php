{{-- 4.3 - Gráficos de Abastecimentos --}}
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
        </svg>
        Análises e Gráficos
    </h3>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Gráfico 1: Evolução de consumo por mês --}}
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">Evolução de Consumo (km/L)</h4>
            <div style="height: 250px;">
                <canvas id="chartEvolucaoConsumo"></canvas>
            </div>
        </div>

        {{-- Gráfico 2: Custo por tipo de combustível --}}
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">Custo por Tipo de Combustível</h4>
            <div style="height: 250px;">
                <canvas id="chartCustoCombustivel"></canvas>
            </div>
        </div>

        {{-- Gráfico 3: Ranking de eficiência - Motoristas --}}
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">Top 10 Motoristas Mais Eficientes</h4>
            <div style="height: 300px;">
                <canvas id="chartRankingMotoristas"></canvas>
            </div>
        </div>

        {{-- Gráfico 4: Ranking de eficiência - Veículos --}}
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">Top 10 Veículos Mais Eficientes</h4>
            <div style="height: 300px;">
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
    const evolucaoConsumo = @json($fuelingData['graficos']['evolucao_consumo'] ?? ['labels' => [], 'data' => []]);
    const custoCombustivel = @json($fuelingData['graficos']['custo_combustivel'] ?? []);
    const rankingMotoristas = @json($fuelingData['graficos']['ranking_motoristas'] ?? []);
    const rankingVeiculos = @json($fuelingData['graficos']['ranking_veiculos'] ?? []);

    // Gráfico 1: Evolução de Consumo
    new Chart(document.getElementById('chartEvolucaoConsumo'), {
        type: 'line',
        data: {
            labels: evolucaoConsumo.labels,
            datasets: [{
                label: 'Consumo Médio (km/L)',
                data: evolucaoConsumo.data,
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'km/L'
                    }
                }
            }
        }
    });

    // Gráfico 2: Custo por Combustível
    const custoCombLabels = Object.keys(custoCombustivel);
    const custoCombData = Object.values(custoCombustivel);
    
    new Chart(document.getElementById('chartCustoCombustivel'), {
        type: 'pie',
        data: {
            labels: custoCombLabels,
            datasets: [{
                data: custoCombData,
                backgroundColor: [
                    '#3b82f6', // Azul
                    '#10b981', // Verde
                    '#f59e0b', // Amarelo
                    '#ef4444', // Vermelho
                    '#8b5cf6', // Roxo
                    '#ec4899'  // Rosa
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.5,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': R$ ' + context.parsed.toFixed(2).replace('.', ',');
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
