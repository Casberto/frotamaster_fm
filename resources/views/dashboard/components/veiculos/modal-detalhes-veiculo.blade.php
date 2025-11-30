{{-- resources/views/dashboard/components/veiculos/modal-detalhes-veiculo.blade.php --}}
<div x-data="{
        isOpen: false,
        isLoading: false,
        activeTab: 'geral',
        veiculo: {},
        kpis: {},
        charts: {},
        historico: [],
        alertas: [],
        documentos: [],
        chartInstances: {},
        fetchDetails(id) {
            this.isOpen = true;
            this.isLoading = true;
            this.activeTab = 'geral';
            
            fetch(`/veiculos/${id}/historico`)
                .then(res => res.json())
                .then(data => {
                    this.veiculo = data.veiculo;
                    this.kpis = data.kpis;
                    this.charts = data.charts;
                    this.historico = data.historico;
                    this.alertas = data.alertas;
                    this.documentos = data.documentos;
                    
                    // Initialize charts if needed (using a timeout to wait for DOM)
                    setTimeout(() => {
                        this.initCharts();
                    }, 100);
                })
                .catch(err => {
                    console.error(err);
                    alert('Erro ao carregar detalhes do veículo.');
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        initCharts() {
            if (typeof Chart === 'undefined') return;

            // Destroy existing charts
            Object.values(this.chartInstances).forEach(chart => chart.destroy());
            this.chartInstances = {};

            // 1. Custos Mensais
            if (this.charts.custos) {
                const ctxCustos = document.getElementById('chartVehicleCustos');
                if (ctxCustos) {
                    this.chartInstances.custos = new Chart(ctxCustos, {
                        type: 'bar',
                        data: {
                            labels: this.charts.custos.labels,
                            datasets: [{
                                label: 'Custo Total (R$)',
                                data: this.charts.custos.data,
                                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true }
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: (context) => this.formatCurrency(context.raw)
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // 2. Consumo
            if (this.charts.consumo) {
                const ctxConsumo = document.getElementById('chartVehicleConsumo');
                if (ctxConsumo) {
                    this.chartInstances.consumo = new Chart(ctxConsumo, {
                        type: 'line',
                        data: {
                            labels: this.charts.consumo.labels,
                            datasets: [{
                                label: 'Km/L',
                                data: this.charts.consumo.data,
                                borderColor: 'rgb(16, 185, 129)',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.3,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: false }
                            }
                        }
                    });
                }
            }

            // 3. Tipos de Manutenção
            if (this.charts.manutencao_tipo) {
                const ctxManutencao = document.getElementById('chartVehicleManutencao');
                if (ctxManutencao) {
                    this.chartInstances.manutencao = new Chart(ctxManutencao, {
                        type: 'doughnut',
                        data: {
                            labels: this.charts.manutencao_tipo.labels,
                            datasets: [{
                                data: this.charts.manutencao_tipo.data,
                                backgroundColor: [
                                    '#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#6366F1', '#8B5CF6'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'right' }
                            }
                        }
                    });
                }
            }

            // 4. Quilometragem
            if (this.charts.km_mes) {
                const ctxKm = document.getElementById('chartVehicleKm');
                if (ctxKm) {
                    this.chartInstances.km = new Chart(ctxKm, {
                        type: 'bar',
                        data: {
                            labels: this.charts.km_mes.labels,
                            datasets: [{
                                label: 'Km Rodados',
                                data: this.charts.km_mes.data,
                                backgroundColor: 'rgba(107, 114, 128, 0.5)',
                                borderColor: 'rgb(107, 114, 128)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }
            }
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value || 0);
        },
        formatDate(dateString) {
            if (!dateString) return '-';
            return new Date(dateString).toLocaleDateString('pt-BR');
        }
    }"
    @open-detalhes-veiculo.window="fetchDetails($event.detail.id)"
    x-show="isOpen"
    @keydown.escape.window="isOpen = false"
    class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/75 flex items-center justify-center p-4"
    x-transition.opacity
    x-cloak>

    <div @click.away="isOpen = false" class="bg-white rounded-xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col overflow-hidden">
        
        {{-- Header --}}
        <div class="flex justify-between items-center p-6 border-b bg-gray-50">
            <div class="flex items-center space-x-4">
                <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl">
                    <span x-text="veiculo.vei_placa ? veiculo.vei_placa.substring(0,2) : 'VE'"></span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800" x-text="veiculo.vei_placa + ' - ' + veiculo.vei_modelo"></h2>
                    <p class="text-sm text-gray-500" x-text="veiculo.vei_ano_fab + '/' + veiculo.vei_ano_mod + ' • ' + (veiculo.combustivelTexto || 'Combustível N/A')"></p>
                </div>
            </div>
            <button @click="isOpen = false" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Tabs --}}
        <div class="flex border-b px-6 bg-white">
            <button @click="activeTab = 'geral'" :class="{'border-blue-500 text-blue-600': activeTab === 'geral', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'geral'}" class="py-4 px-6 border-b-2 font-medium text-sm focus:outline-none transition">Visão Geral</button>
            <button @click="activeTab = 'historico'" :class="{'border-blue-500 text-blue-600': activeTab === 'historico', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'historico'}" class="py-4 px-6 border-b-2 font-medium text-sm focus:outline-none transition">Histórico Completo</button>
            <button @click="activeTab = 'custos'" :class="{'border-blue-500 text-blue-600': activeTab === 'custos', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'custos'}" class="py-4 px-6 border-b-2 font-medium text-sm focus:outline-none transition">Custos & Gráficos</button>
            <button @click="activeTab = 'documentos'" :class="{'border-blue-500 text-blue-600': activeTab === 'documentos', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'documentos'}" class="py-4 px-6 border-b-2 font-medium text-sm focus:outline-none transition">Documentos</button>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-6 bg-gray-50 relative">
            
            {{-- Loading Overlay --}}
            <div x-show="isLoading" class="absolute inset-0 bg-white/80 z-10 flex items-center justify-center">
                <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>

            {{-- Tab: Visão Geral --}}
            <div x-show="activeTab === 'geral'" class="space-y-6">
                {{-- KPI Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <div class="text-gray-500 text-sm font-medium">Odômetro Atual</div>
                        <div class="text-2xl font-bold text-gray-800 mt-1" x-text="veiculo.vei_km_atual ? parseInt(veiculo.vei_km_atual).toLocaleString('pt-BR') + ' km' : 'N/A'"></div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <div class="text-gray-500 text-sm font-medium">Custo Total (Histórico)</div>
                        <div class="text-2xl font-bold text-gray-800 mt-1" x-text="formatCurrency(kpis.total_gasto)"></div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <div class="text-gray-500 text-sm font-medium">Status</div>
                        <div class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium"
                             :class="{
                                'bg-green-100 text-green-800': veiculo.vei_status == 1,
                                'bg-red-100 text-red-800': veiculo.vei_status != 1
                             }">
                            <span x-text="veiculo.vei_status == 1 ? 'Ativo' : 'Inativo'"></span>
                        </div>
                    </div>
                </div>

                {{-- Alertas e Próximas Manutenções --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                        <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Alertas e Previsões
                        </h3>
                        <template x-if="alertas.length > 0">
                            <ul class="space-y-3">
                                <template x-for="alerta in alertas" :key="alerta.man_id">
                                    <li class="flex items-start p-3 bg-yellow-50 rounded-md border border-yellow-100">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-yellow-800" x-text="'Manutenção Agendada: ' + formatDate(alerta.man_data_inicio)"></p>
                                            <p class="text-xs text-yellow-600 mt-1" x-text="alerta.man_descricao || 'Sem descrição'"></p>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </template>
                        <template x-if="alertas.length === 0">
                            <p class="text-sm text-gray-500 italic">Nenhum alerta pendente.</p>
                        </template>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                        <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Últimos Registros
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm border-b pb-2">
                                <span class="text-gray-600">Última Manutenção</span>
                                <span class="font-medium text-gray-800" x-text="formatDate(kpis.ultima_manutencao)"></span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-b pb-2">
                                <span class="text-gray-600">Último Abastecimento</span>
                                <span class="font-medium text-gray-800" x-text="formatDate(kpis.ultimo_abastecimento)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab: Histórico --}}
            <div x-show="activeTab === 'historico'" class="space-y-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fornecedor</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="(item, index) in historico" :key="index">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="formatDate(item.data)"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                              :class="item.tipo === 'manutencao' ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800'"
                                              x-text="item.tipo === 'manutencao' ? 'Manutenção' : 'Abastecimento'">
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500" x-text="item.descricao"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="item.fornecedor"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right" x-text="formatCurrency(item.valor)"></td>
                                </tr>
                            </template>
                            <template x-if="historico.length === 0">
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum histórico encontrado.</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tab: Custos & Gráficos --}}
            <div x-show="activeTab === 'custos'" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <h4 class="font-semibold text-gray-700 mb-4">Evolução de Custos (6 Meses)</h4>
                        <div class="h-64 relative">
                            <canvas id="chartVehicleCustos"></canvas>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <h4 class="font-semibold text-gray-700 mb-4">Consumo Médio (Km/L)</h4>
                        <div class="h-64 relative">
                            <canvas id="chartVehicleConsumo"></canvas>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <h4 class="font-semibold text-gray-700 mb-4">Tipos de Manutenção</h4>
                        <div class="h-64 relative">
                            <canvas id="chartVehicleManutencao"></canvas>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                        <h4 class="font-semibold text-gray-700 mb-4">Quilometragem Mensal</h4>
                        <div class="h-64 relative">
                            <canvas id="chartVehicleKm"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab: Documentos --}}
            <div x-show="activeTab === 'documentos'" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="doc in documentos" :key="doc.doc_id">
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex flex-col justify-between">
                            <div>
                                <h4 class="font-semibold text-gray-800" x-text="doc.tipo_texto"></h4>
                                <p class="text-sm text-gray-500 mt-1" x-text="doc.doc_descricao || 'Sem descrição'"></p>
                            </div>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-xs font-medium px-2 py-1 rounded-full"
                                      :class="new Date(doc.doc_validade) < new Date() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'"
                                      x-text="new Date(doc.doc_validade) < new Date() ? 'Vencido' : 'Válido'">
                                </span>
                                <span class="text-xs text-gray-400" x-text="'Vence em: ' + formatDate(doc.doc_validade)"></span>
                            </div>
                        </div>
                    </template>
                    <template x-if="!documentos || documentos.length === 0">
                        <div class="col-span-full text-center py-10 text-gray-500">
                            Nenhum documento cadastrado.
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>
</div>
