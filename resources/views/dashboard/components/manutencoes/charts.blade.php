<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Gráfico de Custos Mensais --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Custos de Manutenção (Últimos 12 Meses)</h3>
        <div class="h-64">
            <canvas id="maintenanceCostsChart"></canvas>
        </div>
    </div>

    {{-- Gráfico de Tipos Mais Comuns --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Tipos de Manutenção Mais Comuns</h3>
        <div class="h-64">
            <canvas id="maintenanceTypesChart"></canvas>
        </div>
    </div>

    {{-- Top Fornecedores --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Fornecedores (Valor Gasto)</h3>
        <div class="overflow-y-auto max-h-64">
             <ul class="divide-y divide-gray-200">
                @foreach($maintenanceData['graficos']['top_fornecedores'] as $fornecedor)
                    <li class="py-3 flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900">{{ $fornecedor->for_nome_fantasia }}</span>
                        <span class="text-sm text-gray-500">R$ {{ number_format($fornecedor->total_gasto, 2, ',', '.') }}</span>
                    </li>
                @endforeach
                 @if($maintenanceData['graficos']['top_fornecedores']->isEmpty())
                    <li class="py-3 text-center text-gray-500 text-sm">Sem dados disponíveis</li>
                @endif
            </ul>
        </div>
    </div>

    {{-- Tempo Médio --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-center items-center">
        <h3 class="text-lg font-medium text-gray-900 mb-2">Tempo Médio de Conclusão</h3>
        <div class="text-5xl font-bold text-indigo-600 my-4">
            {{ $maintenanceData['graficos']['tempo_medio'] }} <span class="text-xl text-gray-500 font-normal">dias</span>
        </div>
        <p class="text-sm text-gray-500 text-center">Média calculada com base nas manutenções concluídas nos últimos 12 meses.</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Custos Mensais
        const ctxCosts = document.getElementById('maintenanceCostsChart').getContext('2d');
        new Chart(ctxCosts, {
            type: 'bar',
            data: {
                labels: @json($maintenanceData['graficos']['custos_mensais']['labels']),
                datasets: [{
                    label: 'Custo Total (R$)',
                    data: @json($maintenanceData['graficos']['custos_mensais']['data']),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Tipos Mais Comuns
        const ctxTypes = document.getElementById('maintenanceTypesChart').getContext('2d');
        const typesData = @json($maintenanceData['graficos']['tipos_comuns']);
        new Chart(ctxTypes, {
            type: 'doughnut',
            data: {
                labels: Object.keys(typesData),
                datasets: [{
                    data: Object.values(typesData),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    });
</script>
