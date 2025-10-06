{{-- resources/views/dashboard/components/charts.blade.php --}}
<div x-data="chartsComponent" class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-200">

    {{-- CORREÇÃO: O cabeçalho agora funciona como um botão para minimizar/expandir os gráficos --}}
    <div @click="toggleCharts()" class="flex justify-between items-center cursor-pointer">
        <h3 class="text-xl font-semibold text-gray-800">Análise de Custos</h3>
        <button type="button" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6 transform transition-transform" :class="{'rotate-180': !showCharts}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>
    </div>

    {{-- Conteúdo expansível --}}
    <div x-show="showCharts" x-collapse>
        <!-- Header with Filters -->
        <div class="flex flex-wrap justify-end items-center mt-6 mb-6 gap-4 border-t pt-4">
            <div class="flex items-center space-x-2">
                <label for="period" class="text-sm font-medium text-gray-600">Período:</label>
                {{-- Adicionado @click.stop para evitar que o clique no select feche o collapse --}}
                <select id="period" x-model="period" @change="fetchChartData()" @click.stop class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm">
                    <option value="30">Últimos 30 dias</option>
                    <option value="90">Últimos 90 dias</option>
                    <option value="180">Últimos 6 meses</option>
                    <option value="365">Último Ano</option>
                </select>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center h-80">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <!-- Charts Grid -->
        <div x-show="!loading" class="space-y-6" x-transition.opacity.duration.500ms>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Card 1: Composição de Custos -->
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-700 mb-2">Composição de Custos</h4>
                    <div x-show="!hasData.custosPeriodo" class="flex items-center justify-center h-64 text-gray-500">
                        <p>Sem dados para exibir no período.</p>
                    </div>
                    <div x-show="hasData.custosPeriodo" class="h-64">
                        <canvas x-ref="custosPeriodoChart"></canvas>
                    </div>
                </div>

                <!-- Card 2: Evolução de Custos -->
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-700 mb-2">Evolução de Custos</h4>
                    <div x-show="!hasData.evolucaoCustos" class="flex items-center justify-center h-64 text-gray-500">
                        <p>Sem dados para exibir no período.</p>
                    </div>
                    <div x-show="hasData.evolucaoCustos" class="h-64">
                        <canvas x-ref="evolucaoCustosChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Card 3: Custo por Veículo -->
            <div class="bg-gray-50 p-4 rounded-lg border">
                <h4 class="font-semibold text-gray-700 mb-2">Veículos com Maiores Custos</h4>
                <div x-show="!hasData.custoPorVeiculo" class="flex items-center justify-center h-64 text-gray-500">
                    <p>Sem dados para exibir no período.</p>
                </div>
                <div x-show="hasData.custoPorVeiculo" :style="`height: ${custoPorVeiculoHeight}px`">
                    <canvas x-ref="custoPorVeiculoChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chartsComponent', () => ({
            showCharts: true, // Controla a visibilidade do painel
            loading: true,
            period: '30',
            charts: {},
            hasData: {
                custosPeriodo: false,
                evolucaoCustos: false,
                custoPorVeiculo: false,
            },
            custoPorVeiculoHeight: 300,

            init() {
                this.$nextTick(() => this.fetchChartData());
            },

            // Função para alternar a visibilidade
            toggleCharts() {
                this.showCharts = !this.showCharts;
            },

            async fetchChartData() {
                this.loading = true;
                try {
                    const response = await fetch(`{{ route('dashboard.chart-data') }}?period=${this.period}`);
                    if (!response.ok) throw new Error('Network response was not ok');
                    const data = await response.json();
                    this.updateCharts(data);
                } catch (error) {
                    console.error('Failed to fetch chart data:', error);
                    this.hasData = { custosPeriodo: false, evolucaoCustos: false, custoPorVeiculo: false };
                } finally {
                    this.loading = false;
                }
            },

            updateCharts(data) {
                this.hasData.custosPeriodo = data.custosPeriodo && data.custosPeriodo.data.some(d => d > 0);
                this.hasData.evolucaoCustos = data.evolucaoCustos && data.evolucaoCustos.data.some(d => d > 0);
                this.hasData.custoPorVeiculo = data.custoPorVeiculo && data.custoPorVeiculo.data.some(d => d > 0);

                this.$nextTick(() => {
                    if (this.hasData.custosPeriodo) this.renderCustosPeriodoChart(data.custosPeriodo);
                    if (this.hasData.evolucaoCustos) this.renderEvolucaoCustosChart(data.evolucaoCustos);
                    if (this.hasData.custoPorVeiculo) this.renderCustoPorVeiculoChart(data.custoPorVeiculo);
                });
            },

            formatCurrency(value) {
                return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
            },
            
            renderCustosPeriodoChart(chartData) {
                if (this.charts.custosPeriodo) this.charts.custosPeriodo.destroy();
                this.charts.custosPeriodo = new Chart(this.$refs.custosPeriodoChart, {
                    type: 'doughnut',
                    data: {
                        labels: chartData.labels,
                        datasets: [{ data: chartData.data, backgroundColor: ['#3182CE', '#38A169'], hoverOffset: 4 }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: { callbacks: { label: (c) => `${c.label}: ${this.formatCurrency(c.parsed)}` } }
                        }
                    }
                });
            },

            renderEvolucaoCustosChart(chartData) {
                if (this.charts.evolucaoCustos) this.charts.evolucaoCustos.destroy();
                this.charts.evolucaoCustos = new Chart(this.$refs.evolucaoCustosChart, {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Custo Total', data: chartData.data, borderColor: '#3182CE',
                            backgroundColor: 'rgba(49, 130, 206, 0.1)', fill: true, tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: { y: { ticks: { callback: (v) => new Intl.NumberFormat('pt-BR', { notation: 'compact' }).format(v) }}},
                        plugins: { tooltip: { callbacks: { label: (c) => `Custo Total: ${this.formatCurrency(c.parsed.y)}` } } }
                    }
                });
            },

            renderCustoPorVeiculoChart(chartData) {
                const bars = chartData.labels.length;
                this.custoPorVeiculoHeight = Math.max(200, bars * 35 + 60);

                if (this.charts.custoPorVeiculo) this.charts.custoPorVeiculo.destroy();
                this.$nextTick(() => {
                    this.charts.custoPorVeiculo = new Chart(this.$refs.custoPorVeiculoChart, {
                        type: 'bar',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: 'Custo Total', data: chartData.data,
                                backgroundColor: 'rgba(56, 161, 105, 0.7)', borderColor: '#2F855A', borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                            scales: { x: { ticks: { callback: (v) => new Intl.NumberFormat('pt-BR', { notation: 'compact' }).format(v) }}},
                            plugins: {
                                legend: { display: false },
                                tooltip: { callbacks: { label: (c) => `Custo Total: ${this.formatCurrency(c.parsed.x)}` } }
                            }
                        }
                    });
                })
            }
        }));
    });
</script>
@endpush

