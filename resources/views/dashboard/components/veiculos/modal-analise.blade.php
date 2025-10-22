{{-- resources/views/dashboard/components/veiculos/vehicle-analysis-modal.blade.php --}}
{{-- Este componente é o modal que exibe a análise de custos do veículo. --}}

<div x-data="{
        isOpen: false,
        analiseData: {},
        formatCurrency(value) {
            if (typeof value !== 'number') { value = parseFloat(value) || 0; }
            return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        },
        calculatePercentageChange(current, previous) {
            if (previous === 0) { return current > 0 ? 100 : 0; }
            if (current === 0) { return -100; }
            const change = ((current - previous) / previous) * 100;
            return change;
        }
    }"
    @open-analise.window="isOpen = true; analiseData = $event.detail.veiculo"
    x-show="isOpen" 
    @keydown.escape.window="isOpen = false" 
    class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/75 flex items-start justify-center p-4 sm:p-6 lg:p-10" x-transition.opacity x-cloak>
    <div @click.away="isOpen = false" class="bg-slate-50 rounded-xl shadow-xl w-full max-w-2xl">
        <div class="flex-shrink-0 flex justify-between items-center p-4 border-b bg-white rounded-t-xl">
            <h3 class="text-lg font-semibold text-gray-800">Análise de Custos - <span x-text="analiseData.vei_placa"></span></h3>
            <button @click="isOpen = false" class="text-gray-400 hover:text-gray-600">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-8 overflow-y-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <!-- Mês Anterior -->
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <h4 class="text-sm font-semibold text-gray-500 mb-2">Mês Anterior</h4>
                    <p class="text-2xl font-bold text-gray-800" x-text="formatCurrency(analiseData.custo_total_anterior)"></p>
                    <div class="mt-4 pt-4 border-t space-y-2 text-sm">
                        <p class="flex justify-between text-gray-600"><span>Manutenção:</span> <span class="font-medium" x-text="formatCurrency(analiseData.custo_anterior_manutencao)"></span></p>
                        <p class="flex justify-between text-gray-600"><span>Abastecimento:</span> <span class="font-medium" x-text="formatCurrency(analiseData.custo_anterior_abastecimento)"></span></p>
                    </div>
                </div>

                <!-- Mês Atual -->
                <div class="bg-white p-6 rounded-lg shadow-lg border-2 border-blue-500 relative">
                    <span class="absolute -top-3 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full">Mês Atual</span>
                    <h4 class="text-sm font-semibold text-gray-500 mb-2 invisible">Mês Atual</h4> <!-- hidden title for alignment -->
                    <p class="text-2xl font-bold text-blue-600" x-text="formatCurrency(analiseData.custo_total_mensal)"></p>

                    <div class="flex items-center justify-center text-sm font-semibold mt-2"
                        x-show="analiseData.custo_total_anterior > 0 || analiseData.custo_total_mensal > 0"
                        :class="{
                            'text-red-500': calculatePercentageChange(analiseData.custo_total_mensal, analiseData.custo_total_anterior) > 0,
                            'text-green-500': calculatePercentageChange(analiseData.custo_total_mensal, analiseData.custo_total_anterior) < 0,
                            'text-gray-500': calculatePercentageChange(analiseData.custo_total_mensal, analiseData.custo_total_anterior) == 0
                        }">
                        <svg x-show="calculatePercentageChange(analiseData.custo_total_mensal, analiseData.custo_total_anterior) !== 0" class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="{'rotate-180': calculatePercentageChange(analiseData.custo_total_mensal, analiseData.custo_total_anterior) < 0}">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                        <span x-text="`${Math.abs(calculatePercentageChange(analiseData.custo_total_mensal, analiseData.custo_total_anterior)).toFixed(0)}%`"></span>
                        <span x-show="calculatePercentageChange(analiseData.custo_total_mensal, analiseData.custo_total_anterior) === 0">0%</span>
                    </div>

                    <div class="mt-4 pt-4 border-t space-y-2 text-sm">
                        <p class="flex justify-between text-gray-600"><span>Manutenção:</span> <span class="font-medium" x-text="formatCurrency(analiseData.custo_mensal_manutencao)"></span></p>
                        <p class="flex justify-between text-gray-600"><span>Abastecimento:</span> <span class="font-medium" x-text="formatCurrency(analiseData.custo_mensal_abastecimento)"></span></p>
                    </div>
                </div>

                <!-- Média 12 Meses -->
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <h4 class="text-sm font-semibold text-gray-500 mb-2">Média 12 Meses</h4>
                    <p class="text-2xl font-bold text-gray-800" x-text="formatCurrency(analiseData.media_custo_total_12_meses)"></p>
                     <div class="mt-4 pt-4 border-t space-y-2 text-sm text-transparent">
                         <!-- Mantém o espaçamento igual aos outros cards -->
                        <p><span>-</span></p>
                        <p><span>-</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

