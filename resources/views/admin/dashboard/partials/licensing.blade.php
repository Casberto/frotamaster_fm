
<div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="licensingCharts">
    <!-- Distribuição de Planos -->
    <div class="bg-white shadow sm:rounded-lg flex flex-col">
         <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-semibold text-gray-800">
                Distribuição de Planos
            </h3>
        </div>
        <div class="p-6 flex-1">
             <div class="relative h-64">
                <canvas x-ref="plansChart"></canvas>
            </div>
            <div class="mt-4">
                <ul class="divide-y divide-gray-200">
                    @foreach($licensing['distribuicao_planos'] as $planoData)
                    <li class="py-2 flex items-center justify-between text-sm">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full mr-2 bg-blue-500"></span> <!-- Placeholder color -->
                            <span class="capitalize text-gray-700 font-medium">{{ $planoData->plano }}</span>
                        </div>
                        <span class="text-gray-900 font-bold">{{ $planoData->total }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Receita Estimada -->
    <div class="bg-white shadow sm:rounded-lg flex flex-col">
         <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-semibold text-gray-800">
                Financeiro (Projeção)
            </h3>
        </div>
        <div class="p-6 flex-1 flex flex-col justify-between">
            <div class="mb-4">
                <p class="text-sm text-gray-500">Estimativa mensal (MRR):</p>
                <p class="text-4xl font-extrabold text-gray-900 mt-1">R$ {{ number_format($licensing['receita_estimada'], 2, ',', '.') }}</p>
            </div>
            
             <div class="relative h-48 w-full">
                <canvas x-ref="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('licensingCharts', () => ({
            plans: @json($licensing['distribuicao_planos']),
            
            init() {
                this.$nextTick(() => {
                    this.initPlansChart();
                    this.initRevenueChart();
                });
            },

            initPlansChart() {
                const ctx = this.$refs.plansChart.getContext('2d');
                const labels = this.plans.map(p => p.plano.charAt(0).toUpperCase() + p.plano.slice(1));
                const data = this.plans.map(p => p.total);
                
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#6366F1', '#EC4899'], // Blue, Green, Yellow, Indigo, Pink
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            },

            initRevenueChart() {
                const ctx = this.$refs.revenueChart.getContext('2d');
                const crr = {{ $licensing['receita_estimada'] }};
                
                // Mock projection data
                const labels = ['Mês 1', 'Mês 2', 'Mês 3', 'Mês 4', 'Mês 5', 'Mês 6'];
                const data = labels.map((_, i) => crr * (1 + (i * 0.05))); // 5% growth per month

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Projeção (R$)',
                            data: data,
                            backgroundColor: '#3B82F6',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { display: false },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        }));
    });
</script>
         <div class="p-6 flex flex-col items-center justify-center">
             <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Receita total baseada em licenças ativas</p>
             <p class="text-4xl font-bold text-green-600 dark:text-green-400">
                 R$ {{ number_format($licensing['receita_estimada'], 2, ',', '.') }}
             </p>
         </div>
    </div>
</div>
