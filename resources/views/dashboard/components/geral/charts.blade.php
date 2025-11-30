<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Evolução de Custos -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Evolução de Custos (6 Meses)</h3>
        <div class="relative h-64">
            <canvas id="chartEvolucaoCustos"></canvas>
        </div>
    </div>

    <!-- Composição de Custos -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Composição de Custos (Mês Atual)</h3>
        <div class="relative h-64 flex justify-center">
            <canvas id="chartComposicaoCustos"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Evolução de Custos
        const ctxEvolucao = document.getElementById('chartEvolucaoCustos').getContext('2d');
        new Chart(ctxEvolucao, {
            type: 'line',
            data: {
                labels: @json($graficos['evolucao_custos']['labels']),
                datasets: [{
                    label: 'Custo Total (R$)',
                    data: @json($graficos['evolucao_custos']['data']),
                    borderColor: 'rgb(79, 70, 229)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Composição de Custos
        const ctxComposicao = document.getElementById('chartComposicaoCustos').getContext('2d');
        new Chart(ctxComposicao, {
            type: 'doughnut',
            data: {
                labels: @json($graficos['composicao_custos']['labels']),
                datasets: [{
                    data: @json($graficos['composicao_custos']['data']),
                    backgroundColor: [
                        'rgb(239, 68, 68)', // Manutenção (Red)
                        'rgb(59, 130, 246)', // Combustível (Blue)
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                let value = context.parsed;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1) + "%";
                                return label + new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value) + ' (' + percentage + ')';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
