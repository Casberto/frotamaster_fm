@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6" role="alert">
        <p class="font-bold">Atenção</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-8" 
    x-data="manutencaoForm({
        servicosDisponiveis: {{ $servicos->map(fn($s) => ['id' => $s->ser_id, 'nome' => $s->ser_nome])->toJson() }},
        {{-- TRECHO AJUSTADO: Adicionado 'garantia' aos dados iniciais para a edição --}}
        servicosIniciais: {{ $manutencao->servicos->map(fn($s) => [
            'id' => $s->ser_id, 
            'nome' => $s->ser_nome, 
            'custo' => $s->pivot->ms_custo, 
            'garantia' => $s->pivot->ms_garantia ? \Carbon\Carbon::parse($s->pivot->ms_garantia)->format('Y-m-d') : null
        ])->toJson() }},
        custoPecasInicial: '{{ old('man_custo_pecas', $manutencao->man_custo_pecas) }}',
        custoMaoDeObraInicial: '{{ old('man_custo_mao_de_obra', $manutencao->man_custo_mao_de_obra) }}'
    })"
    x-init="calcularCustoTotal()">

    {{-- Seção de Dados da Manutenção --}}
    <div class="form-section">
        <h3 class="form-section-title">Dados Gerais</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="man_vei_id" class="block font-medium text-sm text-gray-700">Veículo*</label>
                <select name="man_vei_id" id="man_vei_id" class="mt-1 block w-full" required>
                    @foreach($veiculos as $veiculo)
                        <option value="{{ $veiculo->vei_id }}" @selected(old('man_vei_id', $manutencao->man_vei_id ?? '') == $veiculo->vei_id)>
                            {{ $veiculo->vei_placa }} - {{ $veiculo->vei_marca }} {{ $veiculo->vei_modelo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="man_tipo" class="block font-medium text-sm text-gray-700">Tipo*</label>
                <select name="man_tipo" id="man_tipo" class="mt-1 block w-full" required>
                    <option value="preventiva" @selected(old('man_tipo', $manutencao->man_tipo ?? '') == 'preventiva')>Preventiva</option>
                    <option value="corretiva" @selected(old('man_tipo', $manutencao->man_tipo ?? '') == 'corretiva')>Corretiva</option>
                    <option value="preditiva" @selected(old('man_tipo', $manutencao->man_tipo ?? '') == 'preditiva')>Preditiva</option>
                    <option value="outra" @selected(old('man_tipo', $manutencao->man_tipo ?? '') == 'outra')>Outra</option>
                </select>
            </div>
             <div>
                <label for="man_km" class="block font-medium text-sm text-gray-700">Quilometragem*</label>
                <input type="number" name="man_km" id="man_km" class="mt-1 block w-full" value="{{ old('man_km', $manutencao->man_km ?? '') }}" required>
            </div>
            <div>
                <label for="man_data_inicio" class="block font-medium text-sm text-gray-700">Data de Início*</label>
                <input type="date" name="man_data_inicio" id="man_data_inicio" class="mt-1 block w-full" value="{{ old('man_data_inicio', $manutencao->man_data_inicio ? \Carbon\Carbon::parse($manutencao->man_data_inicio)->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
            </div>
             <div>
                <label for="man_data_fim" class="block font-medium text-sm text-gray-700">Data de Conclusão</label>
                <input type="date" name="man_data_fim" id="man_data_fim" class="mt-1 block w-full" value="{{ old('man_data_fim', $manutencao->man_data_fim ? \Carbon\Carbon::parse($manutencao->man_data_fim)->format('Y-m-d') : '') }}">
            </div>
            <div>
                <label for="man_status" class="block font-medium text-sm text-gray-700">Status*</label>
                <select name="man_status" id="man_status" class="mt-1 block w-full" required>
                    <option value="agendada" @selected(old('man_status', $manutencao->man_status ?? 'agendada') == 'agendada')>Agendada</option>
                    <option value="em_andamento" @selected(old('man_status', $manutencao->man_status ?? '') == 'em_andamento')>Em Andamento</option>
                    <option value="concluida" @selected(old('man_status', $manutencao->man_status ?? '') == 'concluida')>Concluída</option>
                    <option value="cancelada" @selected(old('man_status', $manutencao->man_status ?? '') == 'cancelada')>Cancelada</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Seção de Serviços e Custos --}}
    <div class="form-section">
        <h3 class="form-section-title">Serviços e Custos</h3>
        
        {{-- Interface para Adicionar Serviços --}}
        <div class="bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-5">
                    <label for="novo_servico_id" class="block font-medium text-sm text-gray-700">Serviço</label>
                    <select id="novo_servico_id" x-model.number="novoServico.id" class="mt-1 block w-full">
                        <option value="">Selecione um serviço...</option>
                        <template x-for="servico in servicosDisponiveis" :key="servico.id">
                            <option x-show="!servicoJaAdicionado(servico.id)" :value="servico.id" x-text="servico.nome"></option>
                        </template>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label for="novo_servico_custo" class="block font-medium text-sm text-gray-700">Custo (R$)</label>
                    <input type="number" step="0.01" id="novo_servico_custo" x-model.number="novoServico.custo" class="mt-1 block w-full" placeholder="Ex: 150.00">
                </div>
                {{-- TRECHO AJUSTADO: Adicionado campo de data para a garantia do novo serviço --}}
                <div class="md:col-span-2">
                    <label for="novo_servico_garantia" class="block font-medium text-sm text-gray-700">Garantia</label>
                    <input type="date" id="novo_servico_garantia" x-model="novoServico.garantia" class="mt-1 block w-full">
                </div>
                <div class="md:col-span-2">
                    <button type="button" @click.prevent="adicionarServico()" class="w-full bg-gray-800 text-white rounded-md py-2 px-4 hover:bg-gray-700 transition">Adicionar</button>
                </div>
            </div>
        </div>
        
        {{-- Lista de Serviços Adicionados --}}
        <div class="mt-4 space-y-2">
            <template x-if="servicosAdicionados.length === 0">
                <p class="text-center text-gray-500 py-4">Nenhum serviço adicionado.</p>
            </template>
            <template x-for="(servico, index) in servicosAdicionados" :key="index">
                <div class="bg-white p-3 rounded-md border">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="font-medium" x-text="servico.nome"></p>
                            <p class="text-sm text-gray-600">Custo: R$ <span x-text="formatCurrency(servico.custo)"></span></p>
                        </div>
                        <button @click.prevent="removerServico(index)" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    {{-- TRECHO AJUSTADO: Adicionado campo de data para editar a garantia de um serviço já adicionado --}}
                    <div class="mt-2">
                        <label :for="'garantia_' + index" class="block font-medium text-xs text-gray-500">Garantia do Serviço</label>
                        <input type="date" :id="'garantia_' + index" x-model="servico.garantia" class="mt-1 block w-full md:w-1/3">
                    </div>

                    {{-- Hidden inputs for form submission --}}
                    <input type="hidden" :name="`servicos[${index}][id]`" :value="servico.id">
                    <input type="hidden" :name="`servicos[${index}][custo]`" :value="servico.custo">
                    <input type="hidden" :name="`servicos[${index}][garantia]`" :value="servico.garantia">
                </div>
            </template>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 pt-6 border-t">
            <div>
                <label for="man_custo_pecas" class="block font-medium text-sm text-gray-700">Custo Adicional Peças (R$)</label>
                <input type="number" step="0.01" name="man_custo_pecas" id="man_custo_pecas" x-model.number="custoPecas" @input="calcularCustoTotal()" class="mt-1 block w-full" placeholder="0.00">
            </div>
            <div>
                <label for="man_custo_mao_de_obra" class="block font-medium text-sm text-gray-700">Custo Adicional Mão de Obra (R$)</label>
                <input type="number" step="0.01" name="man_custo_mao_de_obra" id="man_custo_mao_de_obra" x-model.number="custoMaoDeObra" @input="calcularCustoTotal()" class="mt-1 block w-full" placeholder="0.00">
            </div>
            <div>
                <label for="man_custo_total" class="block font-medium text-sm text-gray-700">Custo Total (R$)*</label>
                <input type="text" name="man_custo_total" id="man_custo_total" x-model="custoTotal" class="mt-1 block w-full bg-gray-200" readonly required>
            </div>
        </div>
    </div>
    
    {{-- Seção de Fornecedor e Detalhes Adicionais --}}
    <div class="form-section">
        <h3 class="form-section-title">Fornecedor e Detalhes Adicionais</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="man_for_id" class="block font-medium text-sm text-gray-700">Fornecedor</label>
                <select name="man_for_id" id="man_for_id" class="mt-1 block w-full">
                    <option value="">Manutenção Interna / Não se aplica</option>
                    @foreach($fornecedores as $fornecedor)
                        <option value="{{ $fornecedor->for_id }}" @selected(old('man_for_id', $manutencao->man_for_id ?? '') == $fornecedor->for_id)>
                            {{ $fornecedor->for_nome_fantasia }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="man_responsavel" class="block font-medium text-sm text-gray-700">Responsável</label>
                <input type="text" name="man_responsavel" id="man_responsavel" class="mt-1 block w-full" value="{{ old('man_responsavel', $manutencao->man_responsavel ?? Auth::user()->name) }}">
            </div>
            <div>
                <label for="man_nf" class="block font-medium text-sm text-gray-700">Nota Fiscal</label>
                <input type="text" name="man_nf" id="man_nf" class="mt-1 block w-full" value="{{ old('man_nf', $manutencao->man_nf ?? '') }}">
            </div>
        </div>
    </div>

    {{-- Seção de Agendamento Futuro --}}
    <div class="form-section">
        <h3 class="form-section-title">Agendamento de Próxima Revisão</h3>
         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="man_prox_revisao_data" class="block font-medium text-sm text-gray-700">Próxima Revisão (Data)</label>
                <input type="date" name="man_prox_revisao_data" id="man_prox_revisao_data" class="mt-1 block w-full" value="{{ old('man_prox_revisao_data', $manutencao->man_prox_revisao_data ? \Carbon\Carbon::parse($manutencao->man_prox_revisao_data)->format('Y-m-d') : '') }}">
            </div>
            <div>
                <label for="man_prox_revisao_km" class="block font-medium text-sm text-gray-700">Próxima Revisão (KM)</label>
                <input type="number" name="man_prox_revisao_km" id="man_prox_revisao_km" class="mt-1 block w-full" value="{{ old('man_prox_revisao_km', $manutencao->man_prox_revisao_km ?? '') }}">
            </div>
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('manutencoes.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Salvar</button>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('manutencaoForm', (initialData) => ({
            servicosDisponiveis: initialData.servicosDisponiveis || [],
            servicosAdicionados: initialData.servicosIniciais || [],
            // TRECHO AJUSTADO: Adicionado 'garantia' ao objeto do novo serviço
            novoServico: { id: '', custo: '', garantia: '' },
            custoPecas: initialData.custoPecasInicial || 0,
            custoMaoDeObra: initialData.custoMaoDeObraInicial || 0,
            custoTotal: '0.00',

            adicionarServico() {
                if (!this.novoServico.id || !this.novoServico.custo) {
                    alert('Por favor, selecione um serviço e informe o custo.');
                    return;
                }
                const servicoSelecionado = this.servicosDisponiveis.find(s => s.id == this.novoServico.id);
                if (servicoSelecionado) {
                    this.servicosAdicionados.push({
                        id: servicoSelecionado.id,
                        nome: servicoSelecionado.nome,
                        custo: parseFloat(this.novoServico.custo),
                        // TRECHO AJUSTADO: Adicionado 'garantia' ao adicionar novo serviço
                        garantia: this.novoServico.garantia || null
                    });
                    // TRECHO AJUSTADO: Resetar o objeto inteiro do novo serviço
                    this.novoServico = { id: '', custo: '', garantia: '' };
                    this.calcularCustoTotal();
                }
            },

            removerServico(index) {
                this.servicosAdicionados.splice(index, 1);
                this.calcularCustoTotal();
            },

            servicoJaAdicionado(id) {
                return this.servicosAdicionados.some(s => s.id == id);
            },

            calcularCustoTotal() {
                const totalServicos = this.servicosAdicionados.reduce((acc, servico) => acc + parseFloat(servico.custo || 0), 0);
                const total = totalServicos + parseFloat(this.custoPecas || 0) + parseFloat(this.custoMaoDeObra || 0);
                this.custoTotal = total.toFixed(2);
            },
            
            formatCurrency(value) {
                if (typeof value !== 'number') {
                    value = parseFloat(value) || 0;
                }
                return value.toFixed(2).replace('.', ',');
            }
        }));
    });
</script>

