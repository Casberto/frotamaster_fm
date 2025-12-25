<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    
    <div class="bg-white p-4 rounded-lg shadow h-80">
        <h4 class="font-bold text-gray-700 mb-4">🏆 Top 5 Veículos (Gastos)</h4>
        <div class="relative h-60">
            <canvas id="topClientesChart"></canvas>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow h-80">
        <h4 class="font-bold text-gray-700 mb-4">📉 Composição de Despesas</h4>
        <div class="relative h-60 flex justify-center">
            <canvas id="lucroChart"></canvas>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Top Clientes (Bar Chart Horizontal)
    const ctx1 = document.getElementById('topClientesChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: @json($chartTopClientes['labels'] ?? []),
            datasets: [{
                label: 'Total Gasto (R$)',
                data: @json($chartTopClientes['data'] ?? []),
                backgroundColor: 'rgba(59, 130, 246, 0.6)', // Azul Tailwind
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y', // Faz a barra ser horizontal
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // Composição de Custos (Doughnut)
    const ctx2 = document.getElementById('lucroChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Peças', 'Mão de Obra'],
            datasets: [{
                data: [@json($totalPecas ?? 0), @json($totalMO ?? 0)],
                backgroundColor: ['#3B82F6', '#F59E0B'], // Azul (Peças), Amarelo (MO)
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
                            if (context.parsed !== null) {
                                label += new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(context.parsed);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>
