{{-- 5.3 - Gráficos de Reservas --}}
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
        <svg class="w-5 h-5 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
        </svg>
        Análises e Gráficos
    </h3>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 min-w-0">
        
        {{-- Gráfico 1: Evolução de reservas por mês --}}
        <div class="bg-gray-50 p-4 rounded-lg min-w-0">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">Evolução de Reservas (Últimos 12 Meses)</h4>
            <div class="h-[250px] w-full max-w-full relative">
                <canvas id="chartEvolucaoReservas"></canvas>
            </div>
        </div>

        {{-- Gráfico 2: Reservas por tipo --}}
        <div class="bg-gray-50 p-4 rounded-lg min-w-0">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">Reservas por Tipo</h4>
            <div class="h-[250px] w-full max-w-full relative">
                <canvas id="chartReservasTipo"></canvas>
            </div>
        </div>

        {{-- Gráfico 3: KM Previsto vs Real --}}
        <div class="bg-gray-50 p-4 rounded-lg min-w-0">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">KM Previsto vs Real (Últimos 6 Meses)</h4>
            <div class="h-[300px] w-full max-w-full relative">
                <canvas id="chartKmPrevistoVsReal"></canvas>
            </div>
        </div>

        {{-- Gráfico 4: Veículos mais reservados --}}
        <div class="bg-gray-50 p-4 rounded-lg min-w-0">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">Top 10 Veículos Mais Reservados</h4>
            <div class="h-[300px] w-full max-w-full relative">
                <canvas id="chartVeiculosReservados"></canvas>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dados do PHP
    const evolucaoReservas = @json($reservationsData['graficos']['evolucao_reservas'] ?? ['labels' => [], 'data' => []]);
    const reservasPorTipo = @json($reservationsData['graficos']['por_tipo'] ?? []);
    const kmPrevistoVsReal = @json($reservationsData['graficos']['km_previsto_vs_real'] ?? ['labels' => [], 'previsto' => [], 'real' => []]);
    const veiculosReservados = @json($reservationsData['graficos']['veiculos_mais_reservados'] ?? []);

    // Gráfico 1: Evolução de Reservas
    new Chart(document.getElementById('chartEvolucaoReservas'), {
        type: 'line',
        data: {
            labels: evolucaoReservas.labels,
            datasets: [{
                label: 'Número de Reservas',
                data: evolucaoReservas.data,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                    ticks: {
                        stepSize: 1
                    },
                    title: {
                        display: true,
                        text: 'Quantidade'
                    }
                }
            }
        }
    });

    // Gráfico 2: Reservas por Tipo
    const tipoLabels = Object.keys(reservasPorTipo);
    const tipoData = Object.values(reservasPorTipo);
    
    new Chart(document.getElementById('chartReservasTipo'), {
        type: 'doughnut',
        data: {
            labels: tipoLabels,
            datasets: [{
                data: tipoData,
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
                }
            }
        }
    });

    // Gráfico 3: KM Previsto vs Real
    new Chart(document.getElementById('chartKmPrevistoVsReal'), {
        type: 'bar',
        data: {
            labels: kmPrevistoVsReal.labels,
            datasets: [
                {
                    label: 'KM Previsto',
                    data: kmPrevistoVsReal.previsto,
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: '#3b82f6',
                    borderWidth: 1
                },
                {
                    label: 'KM Real',
                    data: kmPrevistoVsReal.real,
                    backgroundColor: 'rgba(16, 185, 129, 0.5)',
                    borderColor: '#10b981',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quilômetros'
                    }
                }
            }
        }
    });

    // Gráfico 4: Veículos Mais Reservados
    const veiLabels = veiculosReservados.map(v => v.vei_placa || 'N/A');
    const veiData = veiculosReservados.map(v => v.total_reservas || 0);
    
    new Chart(document.getElementById('chartVeiculosReservados'), {
        type: 'bar',
        data: {
            labels: veiLabels,
            datasets: [{
                label: 'Total de Reservas',
                data: veiData,
                backgroundColor: '#8b5cf6',
                borderColor: '#7c3aed',
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
                    ticks: {
                        stepSize: 1
                    },
                    title: {
                        display: true,
                        text: 'Número de Reservas'
                    }
                }
            }
        }
    });
});
</script>
@endpush
