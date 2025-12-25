<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Ordem de Serviço') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="osWizard()">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('oficina.os.store') }}" method="POST" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @csrf
                
                <div class="bg-gray-100 h-2 w-full">
                    <div class="bg-blue-600 h-2 transition-all duration-300" :style="'width: ' + ((step/3)*100) + '%'"></div>
                </div>

                <div class="p-6">
                    
                    <div x-show="step === 1" x-transition>
                        <h3 class="text-lg font-bold text-gray-700 mb-4">1. Identificar Veículo</h3>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Placa do Veículo</label>
                            <div class="flex gap-2">
                                <input type="text" x-model="placa" @input.debounce.500ms="buscarPlaca" @keydown.enter.prevent="buscarPlaca" name="placa" 
                                    class="uppercase text-3xl font-black text-center w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="ABC-1234" maxlength="8" required>
                                
                                <button type="button" @click="buscarPlaca" class="px-4 bg-gray-200 rounded-lg hover:bg-gray-300">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </div>
                            <p x-show="buscando" class="text-sm text-blue-500 mt-1">A procurar...</p>
                        </div>

                        <div x-show="veiculoEncontrado" class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-green-100 rounded-full p-2">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-bold text-green-800" x-text="veiculo.vct_modelo + ' - ' + veiculo.vct_marca"></h3>
                                    
                                    <div x-show="!trocarCliente">
                                        <p class="text-xs text-green-600" x-text="'Cliente: ' + cliente.clo_nome"></p>
                                        <button type="button" @click="trocarCliente = true" class="text-xs text-blue-600 hover:underline mt-1 font-bold">
                                            <span x-text="'Não é o ' + cliente.clo_nome + '? Trocar cadastrado'"></span>
                                        </button>
                                        <input type="hidden" name="veiculo_id" x-model="veiculo.vct_id">
                                    </div>

                                    <div x-show="trocarCliente" class="mt-3 bg-white p-3 rounded border border-gray-200 shadow-sm">
                                        <p class="text-xs font-bold text-gray-500 mb-2">Cadastrar Novo Dono:</p>
                                        <div class="grid grid-cols-1 gap-2">
                                            <input type="text" name="novo_nome_cliente" class="text-sm border-gray-300 rounded" placeholder="Nome do Novo Cliente">
                                            <input type="text" name="novo_telefone_cliente" class="text-sm border-gray-300 rounded" placeholder="WhatsApp (00) 00000-0000">
                                            <input type="hidden" name="alterar_cliente" :value="trocarCliente ? 1 : 0">
                                        </div>
                                        <button type="button" @click="trocarCliente = false" class="text-xs text-red-500 hover:underline mt-2">Cancelar troca</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- SELEÇÃO DE COMBUSTÍVEL -->
                         <div class="mt-4 mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Combustível</label>
                            <select name="combustivel" x-model="combustivel" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Selecione...</option>
                                <option value="Flex">Flex</option>
                                <option value="Gasolina">Gasolina</option>
                                <option value="Etanol">Etanol</option>
                                <option value="Diesel">Diesel</option>
                                <option value="GNV">GNV</option>
                                <option value="Elétrico">Elétrico</option>
                            </select>
                        </div>

                        <div x-show="!veiculoEncontrado && placa.length >= 7 && !buscando">
                            <div class="border-t pt-4 mt-4">
                                <p class="text-sm text-orange-600 font-bold mb-3">Veículo não registado. Preencha os dados:</p>
                                
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label class="text-xs font-bold text-gray-500">Modelo *</label>
                                        <input type="text" name="modelo_veiculo" class="w-full text-sm rounded border-gray-300">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-gray-500">Marca</label>
                                        <input type="text" name="marca_veiculo" class="w-full text-sm rounded border-gray-300">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="text-xs font-bold text-gray-500">Nome do Cliente *</label>
                                    <input type="text" name="nome_cliente" class="w-full text-sm rounded border-gray-300">
                                </div>

                                <div class="mb-3">
                                    <label class="text-xs font-bold text-gray-500">WhatsApp (Para enviar link) *</label>
                                    <input type="text" name="telefone" class="w-full text-sm rounded border-gray-300" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="button" @click="nextStep" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold shadow hover:bg-blue-700 w-full sm:w-auto">
                                Continuar &rarr;
                            </button>
                        </div>
                    </div>

                    <div x-show="step === 2" x-transition style="display: none;">
                        <h3 class="text-lg font-bold text-gray-700 mb-4">2. O que vamos fazer?</h3>

                            <div>
                                <label for="problema" class="block text-sm font-medium text-gray-700">Relato do Problema / Solicitação</label>
                                <textarea name="problema" id="problema" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            </div>

                            <div class="mt-4 flex items-center">
                                <input type="checkbox" name="gerar_orcamento" id="gerar_orcamento" value="1" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="gerar_orcamento" class="ml-2 block text-sm text-gray-900 font-bold">
                                    Gerar Orçamento / Aguardar Aprovação?
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 ml-6">Se desmarcado, a OS irá direto para "Peças/Execução" após o diagnóstico, pulando a etapa de aprovação do cliente.</p>
                            <button type="button" class="mt-2 text-xs text-blue-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                                Usar microfone (em breve)
                            </button>


                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prioridade / Urgência</label>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="prioridade" value="normal" class="peer sr-only" checked>
                                    <div class="text-center p-2 border rounded-lg peer-checked:bg-blue-100 peer-checked:border-blue-500 peer-checked:text-blue-700">
                                        🐢 Normal
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="prioridade" value="alta" class="peer sr-only">
                                    <div class="text-center p-2 border rounded-lg peer-checked:bg-orange-100 peer-checked:border-orange-500 peer-checked:text-orange-700">
                                        🔧 Alta
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="prioridade" value="urgente" class="peer sr-only">
                                    <div class="text-center p-2 border rounded-lg peer-checked:bg-red-100 peer-checked:border-red-500 peer-checked:text-red-700">
                                        🔥 Urgente
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <button type="button" @click="step = 1" class="text-gray-600 px-4 py-2 hover:underline">Voltar</button>
                            <button type="button" @click="nextStep" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold shadow hover:bg-blue-700">
                                Próximo &rarr;
                            </button>
                        </div>
                    </div>

                    <div x-show="step === 3" x-transition style="display: none;">
                        <h3 class="text-lg font-bold text-gray-700 mb-4">3. Estado do Carro</h3>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nível de Combustível</label>
                            <div class="relative pt-1">
                                <input type="range" name="nivel_combustivel" min="0" max="100" step="25" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>Reserva</span>
                                    <span>1/4</span>
                                    <span>1/2</span>
                                    <span>3/4</span>
                                    <span>Cheio</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Avarias Visíveis (Risco, Amolgadela)</label>
                            <input type="text" name="avarias_desc" class="w-full rounded-lg border-gray-300" placeholder="Ex: Risco porta direita...">
                        </div>

                        <div class="mt-8 flex justify-between items-center">
                            <button type="button" @click="step = 2" class="text-gray-600 px-4 py-2 hover:underline">Voltar</button>
                            <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-black shadow-lg hover:bg-green-700 transform hover:scale-105 transition">
                                ABRIR ORDEM DE SERVIÇO
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script>
        function osWizard() {
            return {
                step: 1,
                placa: '',
                step: 1,
                placa: '',
                buscando: false,
                veiculoEncontrado: false,
                trocarCliente: false,
                combustivel: '',
                veiculo: {},
                cliente: {},

                async buscarPlaca() {
                    if (this.placa.length < 7) return;
                    
                    this.buscando = true;
                    // Reset
                    this.veiculoEncontrado = false;
                    this.veiculo = {};

                    try {
                        const response = await fetch(`{{ route('oficina.veiculos.buscar-placa') }}?placa=${this.placa}`);
                        const data = await response.json();

                        if (data.encontrado) {
                            this.veiculoEncontrado = true;
                            this.veiculo = data.veiculo;
                            this.cliente = data.cliente;
                            this.combustivel = data.veiculo.vct_combustivel || '';
                        }
                    } catch (error) {
                        console.error('Erro ao buscar placa:', error);
                    } finally {
                        this.buscando = false;
                    }
                },

                nextStep() {
                    // Validação simples antes de avançar
                    if (this.step === 1 && this.placa.length < 7) {
                        alert('Digite a placa corretamente.');
                        return;
                    }
                    this.step++;
                }
            }
        }
    </script>
</x-app-layout>
