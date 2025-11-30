<div x-data="{
    open: false,
    loading: false,
    details: null,
    openModal(id) {
        this.open = true;
        this.loading = true;
        this.details = null;
        fetch(`/manutencoes/${id}/detalhes`)
            .then(response => response.json())
            .then(data => {
                this.details = data;
                this.loading = false;
            })
            .catch(error => {
                console.error('Erro ao carregar detalhes:', error);
                this.loading = false;
            });
    },
    closeModal() {
        this.open = false;
    }
}"
@open-maintenance-modal.window="openModal($event.detail.id)"
x-show="open"
style="display: none;"
class="fixed inset-0 z-50 overflow-y-auto"
aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeModal"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Detalhes da Manutenção
                        </h3>
                        
                        <div class="mt-4">
                            <template x-if="loading">
                                <div class="flex justify-center py-4">
                                    <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </template>

                            <template x-if="!loading && details">
                                <div class="space-y-4 text-sm text-gray-600">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <p class="font-bold text-gray-700">Veículo</p>
                                            <p x-text="details.veiculo ? details.veiculo.vei_placa + ' - ' + details.veiculo.vei_modelo : 'N/A'"></p>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-700">Fornecedor</p>
                                            <p x-text="details.fornecedor ? details.fornecedor.for_nome_fantasia : 'N/A'"></p>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-700">Data Início</p>
                                            <p x-text="new Date(details.man_data_inicio).toLocaleDateString('pt-BR')"></p>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-700">Status</p>
                                            <p class="capitalize" x-text="details.man_status.replace('_', ' ')"></p>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-700">Tipo</p>
                                            <p class="capitalize" x-text="details.man_tipo.replace('_', ' ')"></p>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-700">Valor Total</p>
                                            <p x-text="'R$ ' + parseFloat(details.man_custo_total).toLocaleString('pt-BR', { minimumFractionDigits: 2 })"></p>
                                        </div>
                                    </div>

                                    <div class="border-t pt-4">
                                        <p class="font-bold text-gray-700 mb-2">Serviços Realizados</p>
                                        <ul class="list-disc pl-5 space-y-1">
                                            <template x-for="servico in details.servicos" :key="servico.ser_id">
                                                <li>
                                                    <span x-text="servico.ser_nome"></span>
                                                    <span class="text-gray-400 text-xs" x-text="servico.pivot && servico.pivot.ms_custo ? ' - R$ ' + parseFloat(servico.pivot.ms_custo).toLocaleString('pt-BR', { minimumFractionDigits: 2 }) : ''"></span>
                                                </li>
                                            </template>
                                            <template x-if="!details.servicos || details.servicos.length === 0">
                                                <li class="text-gray-400 italic">Nenhum serviço listado</li>
                                            </template>
                                        </ul>
                                    </div>

                                    <template x-if="details.man_observacoes">
                                        <div class="border-t pt-4">
                                            <p class="font-bold text-gray-700">Observações</p>
                                            <p x-text="details.man_observacoes"></p>
                                        </div>
                                    </template>
                                    
                                    <template x-if="details.man_garantia">
                                        <div class="border-t pt-4">
                                            <p class="font-bold text-gray-700">Garantia até</p>
                                            <p x-text="new Date(details.man_garantia).toLocaleDateString('pt-BR')"></p>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="closeModal">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>
