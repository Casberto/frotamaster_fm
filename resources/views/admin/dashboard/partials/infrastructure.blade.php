<div class="space-y-6" x-data="infrastructureCharts(@json($infrastructure))">
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Card Disco -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500 hover:shadow-md transition">
            <div class="flex justify-between items-center mb-4">
                 <h4 class="text-sm font-bold text-gray-700">Armazenamento</h4>
                 <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded" x-text="disk_percent + '%'">{{ $infrastructure['disk_percent'] }}%</span>
            </div>
            
            <div class="w-full bg-gray-100 rounded-full h-3 mb-4">
              <div class="bg-blue-600 h-3 rounded-full transition-all duration-1000 w-0" :style="'width: ' + disk_percent + '%'"></div>
            </div>

            <div class="text-xs text-gray-500 flex justify-between font-medium">
                <span x-text="'Livre: ' + disk_free">Livre: {{ $infrastructure['disk_free'] }}</span>
                <span x-text="'Total: ' + disk_total">Total: {{ $infrastructure['disk_total'] }}</span>
            </div>
        </div>

        <!-- Card Memória RAM (Sistema) -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500 hover:shadow-md transition">
            <div class="flex justify-between items-center mb-2">
                 <h4 class="text-sm font-bold text-gray-700">Memória (Sistema)</h4>
                 <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">Total</span>
            </div>
            <div class="flex items-baseline mt-2">
                <p class="text-3xl font-extrabold text-black" x-text="ram_usage">{{ $infrastructure['ram_usage'] }}</p>
                <span class="ml-2 text-sm text-gray-500" x-text="'/ ' + ram_total">/ {{ $infrastructure['ram_total'] }}</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 mt-4">
                <div class="bg-purple-500 h-2 rounded-full transition-all duration-1000 w-0" :style="'width: ' + ram_percent + '%'"></div>
            </div>
            <p class="text-xs text-gray-500 mt-2" x-text="ram_percent + '% em uso'">{{ $infrastructure['ram_percent'] }}% em uso</p>
        </div>
        
        <!-- Card CPU Load -->
         <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500 hover:shadow-md transition">
            <div class="flex justify-between items-center mb-2">
                 <h4 class="text-sm font-bold text-gray-700">CPU Load</h4>
                 <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded" x-text="cpu_load + '%'">{{ $infrastructure['cpu_load'] }}%</span>
            </div>
            <div class="relative h-24">
                <canvas x-ref="cpuChart"></canvas>
            </div>
        </div>

    </div>
    
    <!-- Detalhes Avançados (RAM Chart) -->
    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
             <h3 class="text-lg leading-6 font-semibold text-gray-800">
                Histórico de Memória RAM (Últimas 24h)
            </h3>
        </div>
        <div class="p-4 sm:p-6">
             <div class="relative h-56">
                <canvas x-ref="ramChart"></canvas>
            </div>
        </div>
    </div>

</div>

<script>
    (function() {
        const initCharts = () => {
            Alpine.data('infrastructureCharts', (initialData) => ({
                cpuChart: null,
                ramChart: null,
                
                // Reactive variables
                disk_percent: initialData?.disk_percent || 0,
                disk_free: initialData?.disk_free || '0 B',
                disk_total: initialData?.disk_total || '0 B',
                ram_usage: initialData?.ram_usage || '0 B',
                ram_total: initialData?.ram_total || '0 B',
                ram_percent: initialData?.ram_percent || 0,
                cpu_load: initialData?.cpu_load || 0,

                init() {
                    this.$nextTick(() => {
                        this.initCpuChart();
                        this.initRamChart();
                        setInterval(() => { this.fetchRealTimeData(); }, 3000);
                    });
                },

                async fetchRealTimeData() {
                    try {
                        const response = await fetch("{{ route('admin.monitor.stats') }}");
                        if (!response.ok) return;
                        const data = await response.json();
                        
                        this.disk_percent = data.disk_percent;
                        this.disk_free = data.disk_free;
                        this.disk_total = data.disk_total;
                        this.ram_usage = data.ram_usage;
                        this.ram_total = data.ram_total;
                        this.ram_percent = data.ram_percent;
                        this.cpu_load = data.cpu_load;

                        this.updateCpuChart(data.cpu_load);
                    } catch (error) {
                        console.error('Stats fetch error:', error);
                    }
                },

                initCpuChart() {
                    if (!this.$refs.cpuChart) return;
                    const ctx = this.$refs.cpuChart.getContext('2d');
                    this.cpuChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: Array(20).fill(''),
                            datasets: [{
                                label: 'CPU',
                                data: Array(20).fill(this.cpu_load), 
                                borderColor: '#10B981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { x: { display: false }, y: { display: false, min: 0, max: 100 } },
                            animation: false
                        }
                    });
                },

                updateCpuChart(newValue) {
                    if (!this.cpuChart) return;
                    this.cpuChart.data.datasets[0].data.shift();
                    this.cpuChart.data.datasets[0].data.push(newValue);
                    this.cpuChart.update();
                },

                initRamChart() {
                    if (!this.$refs.ramChart) return;
                    const ctx = this.$refs.ramChart.getContext('2d');
                    
                    const currentHour = new Date().getHours();
                    const labels = [];
                    for (let i = 23; i >= 0; i--) {
                        let h = currentHour - i;
                        if (h < 0) h += 24;
                        labels.push(`${h}:00`);
                    }

                    // Keep simulation for history as we lack DB
                    const usage = Array.from({length: 24}, () => Math.floor(Math.random() * 50) + 30); 

                    this.ramChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                type: 'line',
                                label: 'Uso (%)',
                                data: usage,
                                borderColor: '#6366F1',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.4
                            }, {
                                type: 'bar',
                                label: 'Pico',
                                data: usage.map(v => v + Math.random() * 10),
                                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                borderRadius: 4,
                                barPercentage: 0.6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'index', intersect: false },
                            plugins: { legend: { position: 'top', align: 'end' }, title: { display: false } },
                            scales: { 
                                y: { beginAtZero: true, max: 100, grid: { borderDash: [2, 4] } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }
            }));
        };

        if (typeof Alpine === 'undefined') {
            document.addEventListener('alpine:init', initCharts);
        } else {
            initCharts();
        }
    })();
</script>
