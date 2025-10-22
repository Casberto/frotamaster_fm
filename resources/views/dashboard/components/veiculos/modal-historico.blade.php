{{-- resources/views/dashboard/components/veiculos/vehicle-history-modal.blade.php --}}
{{-- Este componente é o modal que exibe o histórico de manutenções e abastecimentos. --}}

<div x-data="{
        isOpen: false,
        historicoLoading: false,
        historicoData: { man: [], abs: [] },
        veiculoPlaca: '',
        historicoUrlTemplate: '{{ route('veiculos.historico', ['id' => ':id']) }}',
        fetchHistorico(veiculoId, veiculoPlaca) {
            this.isOpen = true;
            this.historicoLoading = true;
            this.veiculoPlaca = veiculoPlaca;
            this.historicoData = { man: [], abs: [] };
            const url = this.historicoUrlTemplate.replace(':id', veiculoId);
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    this.historicoData.man = data.manutencoes || [];
                    this.historicoData.abs = data.abastecimentos || [];
                })
                .catch(error => {
                    console.error('Houve um problema com a operação de busca:', error);
                    alert('Não foi possível carregar o histórico.');
                })
                .finally(() => {
                    this.historicoLoading = false;
                });
        },
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString.split('T')[0] + 'T00:00:00');
            if (isNaN(date)) { return 'Data inválida'; }
            const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            return date.toLocaleDateString('pt-BR', options);
        },
        formatCurrency(value) {
            if (typeof value !== 'number') { value = parseFloat(value) || 0; }
            return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }
    }"
    @open-historico.window="fetchHistorico($event.detail.id, $event.detail.placa)"
    x-show="isOpen"
    @keydown.escape.window="isOpen = false"
    class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/75 flex items-start justify-center p-4 sm:p-6 lg:p-10"
    x-transition.opacity
    x-cloak>

    <div @click.away="isOpen = false"
        class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">

        <!-- Cabeçalho -->
        <div class="flex-shrink-0 flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">
                Histórico do Veículo - <span x-text="veiculoPlaca"></span>
            </h3>
            <button @click="isOpen = false" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Corpo -->
        <div class="flex-grow p-6 overflow-y-auto bg-slate-50">

            <!-- Loading -->
            <div x-show="historicoLoading" class="flex justify-center items-center h-64">
                <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0
                        c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <!-- Conteúdo -->
            <div x-show="!historicoLoading" class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:items-start" x-cloak>

                <!-- COLUNA MANUTENÇÕES -->
                <div>
                    <h4 class="font-semibold text-gray-700 mb-4 text-center lg:text-left">
                        Últimas 5 Manutenções
                    </h4>

                    <!-- Estado vazio -->
                    <div x-show="historicoData.man.length === 0" x-transition>
                        <div
                            class="flex flex-col items-center justify-center text-center text-gray-500 p-6 border rounded-lg bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-10 w-10 mb-2 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42
                                    15.17l2.496-3.03c.317-.384.74-.626
                                    1.208-.766M11.42 15.17l-4.655
                                    5.653a2.548 2.548 0
                                    11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164
                                    1.163-.188 1.743-.14a4.5 4.5 0
                                    004.486-6.336l-3.276 3.277a3.004 3.004 0
                                    01-2.25-2.25l3.276-3.276a4.5 4.5 0
                                    00-6.336 4.486c.091 1.076-.071
                                    2.264-.904 2.95l-.102.085m-1.745
                                    1.437L5.909 7.5H4.5L2.25
                                    3.75l1.5-1.5L7.5 4.5v1.409l4.26
                                    4.26m-1.745 1.437 1.745-1.437m6.615
                                    8.206L15.75 15.75M4.867
                                    19.125h.008v.008h-.008v-.008z"/>
                            </svg>
                            <p>Nenhuma manutenção encontrada.</p>
                        </div>
                    </div>

                    <!-- Linha do tempo -->
                    <div x-show="historicoData.man.length > 0"
                        class="relative pl-6 border-l-2 border-slate-200" x-transition>
                        <template x-for="(manutencao, index) in historicoData.man" :key="index">
                            <div x-show="manutencao && manutencao.man_id" class="mb-6 relative">
                                <div
                                    class="absolute -left-[31px] top-1 flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 ring-8 ring-slate-50">
                                    <svg class="h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11.42 15.17L17.25 21A2.652 2.652 0
                                            0021 17.25l-5.877-5.877M11.42
                                            15.17l2.496-3.03c.317-.384.74-.626
                                            1.208-.766M11.42 15.17l-4.655
                                            5.653a2.548 2.548 0
                                            11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164
                                            1.163-.188 1.743-.14a4.5 4.5 0
                                            004.486-6.336l-3.276 3.277a3.004 3.004 0
                                            01-2.25-2.25l3.276-3.276a4.5 4.5 0
                                            00-6.336 4.486c.091 1.076-.071
                                            2.264-.904 2.95l-.102.085m-1.745
                                            1.437L5.909 7.5H4.5L2.25
                                            3.75l1.5-1.5L7.5 4.5v1.409l4.26
                                            4.26m-1.745 1.437 1.745-1.437m6.615
                                            8.206L15.75 15.75M4.867
                                            19.125h.008v.008h-.008v-.008z"/>
                                    </svg>
                                </div>
                                <div class="ml-4 p-4 bg-white rounded-lg border shadow-sm">
                                    <p class="font-semibold text-gray-800"
                                    x-text="manutencao.servicos.map(s => s.ser_nome).join(', ') || 'Manutenção'"></p>
                                    <p class="text-sm text-gray-500"
                                    x-text="`Data: ${formatDate(manutencao.man_data_inicio)}`"></p>
                                    <p class="text-sm text-gray-500"
                                    x-text="`Fornecedor: ${manutencao.fornecedor ? manutencao.fornecedor.for_nome_fantasia : 'N/A'}`"></p>
                                    <p class="text-sm font-bold text-gray-700"
                                    x-text="`Custo: ${formatCurrency(manutencao.man_custo_total)}`"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- COLUNA ABASTECIMENTOS -->
                <div>
                    <h4 class="font-semibold text-gray-700 mb-4 text-center lg:text-left">
                        Últimos 5 Abastecimentos
                    </h4>

                    <!-- Estado vazio -->
                    <div x-show="historicoData.abs.length === 0" x-transition
                        class="flex flex-col items-center justify-center text-center text-gray-500 p-6 border rounded-lg bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 mb-2 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 7.5h.01M8.25 12h.01M8.25
                                16.5h.01M12 7.5h.01M12 12h.01M12
                                16.5h.01M15.75 7.5h.01M15.75 12h.01M15.75
                                16.5h.01M4.5 12a7.5 7.5 0
                                0115 0v2.25a2.25 2.25 0
                                01-2.25 2.25H6.75A2.25 2.25 0
                                014.5 14.25V12z"/>
                        </svg>
                        <p>Nenhum abastecimento encontrado.</p>
                    </div>

                    <!-- Linha do tempo -->
                    <div x-show="historicoData.abs.length > 0"
                        class="relative pl-6 border-l-2 border-slate-200" x-transition>
                        <template x-for="(abastecimento, index) in historicoData.abs" :key="index">
                            <div x-show="abastecimento && abastecimento.aba_id" class="mb-6 relative">
                                <div
                                    class="absolute -left-[31px] top-1 flex h-6 w-6 items-center justify-center rounded-full bg-green-100 ring-8 ring-slate-50">
                                    <svg class="h-4 w-4 text-green-600"
                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 7.5h.01M8.25 12h.01M8.25
                                            16.5h.01M12 7.5h.01M12 12h.01M12
                                            16.5h.01M15.75 7.5h.01M15.75
                                            12h.01M15.75 16.5h.01M4.5 12a7.5
                                            7.5 0 0115 0v2.25a2.25 2.25 0
                                            01-2.25 2.25H6.75A2.25 2.25 0
                                            014.5 14.25V12z"/>
                                    </svg>
                                </div>
                                <div class="ml-4 p-4 bg-white rounded-lg border shadow-sm">
                                    <p class="font-semibold text-gray-800"
                                    x-text="`Data: ${formatDate(abastecimento.aba_data)}`"></p>
                                    <p class="text-sm text-gray-500"
                                    x-text="`Posto: ${abastecimento.fornecedor?.for_nome_fantasia ?? 'N/A'}`"></p>
                                    <p class="text-sm text-gray-500"
                                    x-text="`Qtd: ${parseFloat(abastecimento.aba_qtd).toFixed(2)} ${abastecimento.aba_und_med}`"></p>
                                    <p class="text-sm font-bold text-gray-700"
                                    x-text="`Total: ${formatCurrency(abastecimento.aba_vlr_tot)}`"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

