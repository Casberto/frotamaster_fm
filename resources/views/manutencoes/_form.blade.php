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

<div class="space-y-3" 
    x-data="manutencaoForm({
        servicosDisponiveis: {{ $servicos->map(fn($s) => ['id' => $s->ser_id, 'nome' => $s->ser_nome])->toJson() }},
        servicosIniciais: {{ $manutencao->servicos->map(fn($s) => [
            'id' => $s->ser_id, 
            'nome' => $s->ser_nome, 
            'custo' => $s->pivot->ms_custo, 
            'garantia' => $s->pivot->ms_garantia ? \Carbon\Carbon::parse($s->pivot->ms_garantia)->format('Y-m-d') : null
        ])->toJson() }},
        custoPecasInicial: '{{ old('man_val_pecas', $manutencao->man_val_pecas ?? $manutencao->man_custo_pecas) }}',
        custoMaoDeObraInicial: '{{ old('man_val_mao_obra', $manutencao->man_val_mao_obra ?? $manutencao->man_custo_mao_de_obra) }}',
        valorCobradoInicial: '{{ old('man_val_cobrado', $manutencao->man_val_cobrado ?? 0) }}',
        statusInicial: '{{ old('man_status_pagamento', $manutencao->man_status_pagamento ?? 'pendente') }}',
        formaInicial: '{{ old('man_forma_pagamento', $manutencao->man_forma_pagamento ?? '') }}'
    })"
    x-init="calcularCustoTotal()">

    {{-- TAB 1: DADOS GERAIS --}}
    <div id="tab-geral-content" x-show="tab === 'geral' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Dados Gerais
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="man_vei_id" class="block font-medium text-sm text-gray-700">Veículo*</label>
                    <select name="man_vei_id" id="man_vei_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        @foreach($veiculos as $veiculo)
                            <option value="{{ $veiculo->vei_id }}" @selected(old('man_vei_id', $manutencao->man_vei_id ?? '') == $veiculo->vei_id)>
                                {{ $veiculo->vei_placa }} - {{ $veiculo->vei_marca }} {{ $veiculo->vei_modelo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="man_tipo" class="block font-medium text-sm text-gray-700">Tipo*</label>
                    <select name="man_tipo" id="man_tipo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="preventiva" @selected(old('man_tipo', $manutencao->man_tipo ?? '') == 'preventiva')>Preventiva</option>
                        <option value="corretiva" @selected(old('man_tipo', $manutencao->man_tipo ?? '') == 'corretiva')>Corretiva</option>
                        <option value="preditiva" @selected(old('man_tipo', $manutencao->man_tipo ?? '') == 'preditiva')>Preditiva</option>
                        <option value="outra" @selected(old('man_tipo', $manutencao->man_tipo ?? '') == 'outra')>Outra</option>
                    </select>
                </div>
                 <div>
                    <label for="man_km" class="block font-medium text-sm text-gray-700">Quilometragem*</label>
                    <div x-data="{
                        raw: '{{ old('man_km', $manutencao->man_km ?? '') }}',
                        format(v) {
                            if (!v) return '';
                            return v.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        },
                        update(e) {
                            let v = e.target.value.replace(/\D/g, '');
                            this.raw = v;
                            e.target.value = this.format(v);
                        }
                    }" x-init="$refs.input.value = format(raw)">
                        <input type="text" x-ref="input" @input="update" id="man_km_input" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <input type="hidden" name="man_km" id="man_km" x-model="raw">
                    </div>
                </div>
                <div>
                    <label for="man_data_inicio" class="block font-medium text-sm text-gray-700">Data de Início*</label>
                    <input type="date" name="man_data_inicio" id="man_data_inicio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('man_data_inicio', $manutencao->man_data_inicio ? \Carbon\Carbon::parse($manutencao->man_data_inicio)->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                </div>
                 <div>
                    <label for="man_data_fim" class="block font-medium text-sm text-gray-700">Data de Conclusão</label>
                    <input type="date" name="man_data_fim" id="man_data_fim" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('man_data_fim', $manutencao->man_data_fim ? \Carbon\Carbon::parse($manutencao->man_data_fim)->format('Y-m-d') : '') }}">
                </div>
                <div>
                    <label for="man_status" class="block font-medium text-sm text-gray-700">Status*</label>
                    <select name="man_status" id="man_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="agendada" @selected(old('man_status', $manutencao->man_status ?? 'agendada') == 'agendada')>Agendada</option>
                        <option value="em_andamento" @selected(old('man_status', $manutencao->man_status ?? '') == 'em_andamento')>Em Andamento</option>
                        <option value="concluida" @selected(old('man_status', $manutencao->man_status ?? '') == 'concluida')>Concluída</option>
                        <option value="cancelada" @selected(old('man_status', $manutencao->man_status ?? '') == 'cancelada')>Cancelada</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <label for="man_observacoes" class="block font-medium text-sm text-gray-700">Observações</label>
                <textarea name="man_observacoes" id="man_observacoes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('man_observacoes', $manutencao->man_observacoes ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- TAB 2: SERVIÇOS E CUSTOS --}}
    <div id="tab-servicos-content" x-show="tab === 'servicos' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force" style="display: none;">
        
        {{-- SEÇÃO 1: COMPOSIÇÃO DE CUSTOS --}}
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                1. Composição de Custos (Internos)
            </h3>
            
            {{-- Interface para Adicionar Serviços --}}
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-5">
                        <label for="novo_servico_id" class="block font-medium text-sm text-gray-700">Adicionar Serviço</label>
                        <select id="novo_servico_id" x-model.number="novoServico.id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Selecione um serviço...</option>
                            <template x-for="servico in servicosDisponiveis" :key="servico.id">
                                <option x-show="!servicoJaAdicionado(servico.id)" :value="servico.id" x-text="servico.nome"></option>
                            </template>
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label for="novo_servico_custo" class="block font-medium text-sm text-gray-700">Custo Unitário (R$)</label>
                        <input type="number" step="0.01" id="novo_servico_custo" x-model.number="novoServico.custo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="0.00">
                    </div>
                     <div class="md:col-span-2">
                        <label for="novo_servico_garantia" class="block font-medium text-sm text-gray-700">Garantia</label>
                        <input type="date" id="novo_servico_garantia" x-model="novoServico.garantia" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <button type="button" @click.prevent="adicionarServico()" class="w-full bg-gray-800 text-white rounded-md py-2 px-4 hover:bg-gray-700 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Adicionar
                        </button>
                    </div>
                </div>
            </div>
            
            {{-- Lista de Serviços Adicionados --}}
            <div class="mt-4 space-y-2 mb-6">
                <template x-if="servicosAdicionados.length === 0">
                    <p class="text-center text-gray-400 py-4 italic text-sm">Nenhum serviço lançado nesta manutenção ainda.</p>
                </template>
                <template x-for="(servico, index) in servicosAdicionados" :key="index">
                    <div class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded hover:bg-gray-50 transition">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="font-medium text-gray-800" x-text="servico.nome"></p>
                                <div class="mt-1">
                                    <label :for="'garantia_' + index" class="text-xs text-gray-500 mr-2">Garantia:</label>
                                    <input type="date" :id="'garantia_' + index" x-model="servico.garantia" class="text-xs border-gray-200 rounded-sm p-1 text-gray-600 focus:ring-0 focus:border-blue-400 w-32">
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-500 mr-2">Custo:</span>
                                <span class="font-medium text-gray-800">R$ <span x-text="formatCurrency(servico.custo)"></span></span>
                            </div>
                        </div>
                        <button @click.prevent="removerServico(index)" class="text-red-400 hover:text-red-600 p-2" title="Remover serviço">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </template>
            </div>

            {{-- Custos Adicionais --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-gray-100 bg-gray-50/50 p-4 rounded-lg">
                <div>
                    <label for="man_val_pecas" class="block font-medium text-sm text-gray-600">Peças Adicionais (R$)</label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">R$</span>
                        </div>
                        <input type="number" step="0.01" name="man_val_pecas" id="man_val_pecas" x-model.number="custoPecas" @input="calcularCustoTotal()" class="block w-full rounded-md border-gray-300 pl-10 focus:border-red-500 focus:ring-red-500 sm:text-sm" placeholder="0.00">
                    </div>
                </div>
                <div>
                    <label for="man_val_mao_obra" class="block font-medium text-sm text-gray-600">Mão de Obra Extra (R$)</label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">R$</span>
                        </div>
                        <input type="number" step="0.01" name="man_val_mao_obra" id="man_val_mao_obra" x-model.number="custoMaoDeObra" @input="calcularCustoTotal()" class="block w-full rounded-md border-gray-300 pl-10 focus:border-red-500 focus:ring-red-500 sm:text-sm" placeholder="0.00">
                    </div>
                </div>
                <div class="bg-red-50 p-4 rounded-md border border-red-100 flex flex-col justify-center items-center">
                    <span class="text-xs font-bold text-red-600 uppercase tracking-widest">Custo Total Interno</span>
                    <span class="text-2xl font-bold text-red-700">R$ <span x-text="formatCurrency(custoTotal)"></span></span>
                    <input type="hidden" name="man_custo_total" :value="custoTotal">
                </div>
            </div>
        </div>

        {{-- SEÇÃO 2: DADOS DO PAGAMENTO --}}
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-l-4 border-l-blue-500 border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                2. Dados do Pagamento (Despesa)
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center mb-6">
                 <div class="bg-red-50 p-4 rounded-md border border-red-100 flex flex-col justify-center items-center md:col-span-2">
                    <span class="text-xs font-bold text-red-600 uppercase tracking-widest">Total a Pagar</span>
                    <span class="text-3xl font-extrabold text-red-700">R$ <span x-text="formatCurrency(custoTotal)"></span></span>
                    <p class="text-xs text-gray-500 mt-1">Soma de Serviços + Peças + Mão de Obra</p>
                </div>
            </div>

            <!-- Dados de Pagamento -->
            <div class="bg-gray-50 rounded p-4 border border-gray-200">
                <h4 class="text-sm font-bold text-gray-700 mb-3 border-b pb-1">Condições de Pagamento</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="man_status_pagamento" class="block font-medium text-sm text-gray-700">Status Recebimento</label>
                        <select name="man_status_pagamento" x-model="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="pendente">⏳ Pendente</option>
                            <option value="pago">✅ Pago</option>
                            <option value="atrasado">⚠️ Atrasado</option>
                            <option value="cancelado">🚫 Cancelado</option>
                        </select>
                    </div>

                    <div x-show="status === 'pago'" x-transition>
                        <label for="man_forma_pagamento" class="block font-medium text-sm text-gray-700">Forma de Pagamento</label>
                        <select name="man_forma_pagamento" x-model="forma" @change="calcularVencimento()" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Selecione...</option>
                            <option value="pix">PIX / Transferência</option>
                            <option value="dinheiro">Dinheiro</option>
                            <option value="cartao_credito">Cartão de Crédito</option>
                            <option value="boleto">Boleto Bancário</option>
                        </select>
                    </div>

                    <div x-show="status === 'pago'" x-transition>
                        <label for="man_dat_compensacao" class="block font-medium text-sm text-gray-700">Data do Pagamento</label>
                        <input type="date" name="man_dat_compensacao" x-ref="dataCompensacao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('man_dat_compensacao', $manutencao->man_dat_compensacao ? \Carbon\Carbon::parse($manutencao->man_dat_compensacao)->format('Y-m-d') : '') }}" />
                        <p class="text-[10px] text-gray-500 mt-1">Quando a despesa sai da conta.</p>
                    </div>
                    
                    <input type="hidden" name="man_dat_pagamento" :value="status === 'pago' ? new Date().toISOString().split('T')[0] : ''">
                </div>
            </div>
        </div>
    </div>
    
    {{-- TAB 3: DETALHES E FORNECEDOR --}}
    <div id="tab-detalhes-content" x-show="tab === 'detalhes' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force" style="display: none;">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Fornecedor e Detalhes Adicionais
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="man_for_id" class="block font-medium text-sm text-gray-700">Fornecedor</label>
                    <select name="man_for_id" id="man_for_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
                    <input type="text" name="man_responsavel" id="man_responsavel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('man_responsavel', $manutencao->man_responsavel ?? Auth::user()->name) }}">
                </div>
                <div>
                    <label for="man_nf" class="block font-medium text-sm text-gray-700">Nota Fiscal</label>
                    <input type="text" name="man_nf" id="man_nf" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('man_nf', $manutencao->man_nf ?? '') }}">
                </div>
                </div>
            </div>
            

        </div>

        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Agendamento de Próxima Revisão
            </h3>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="man_prox_revisao_data" class="block font-medium text-sm text-gray-700">Próxima Revisão (Data)</label>
                    <input type="date" name="man_prox_revisao_data" id="man_prox_revisao_data" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('man_prox_revisao_data', $manutencao->man_prox_revisao_data ? \Carbon\Carbon::parse($manutencao->man_prox_revisao_data)->format('Y-m-d') : '') }}">
                </div>
                <div>
                    <label for="man_prox_revisao_km" class="block font-medium text-sm text-gray-700">Próxima Revisão (KM)</label>
                    <div x-data="{
                        raw: '{{ old('man_prox_revisao_km', $manutencao->man_prox_revisao_km ?? '') }}',
                        format(v) {
                            if (!v) return '';
                            return v.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        },
                        update(e) {
                            let v = e.target.value.replace(/\D/g, '');
                            this.raw = v;
                            e.target.value = this.format(v);
                        }
                    }" x-init="$refs.input.value = format(raw)">
                        <input type="text" x-ref="input" @input="update" id="man_prox_revisao_km_input" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <input type="hidden" name="man_prox_revisao_km" id="man_prox_revisao_km" x-model="raw">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('manutencaoForm', (initialData) => ({
            servicosDisponiveis: initialData.servicosDisponiveis || [],
            servicosAdicionados: initialData.servicosIniciais || [],
            novoServico: { id: '', custo: '', garantia: '' },
            
            // Campos de Custo (Agora mapeados para man_val_*)
            custoPecas: initialData.custoPecasInicial || 0,
            custoMaoDeObra: initialData.custoMaoDeObraInicial || 0,
            custoTotal: 0, // Será calculado

            // Campos Financeiros (Novos)
            valorCobrado: initialData.valorCobradoInicial || 0,
            status: initialData.statusInicial || 'pendente',
            forma: initialData.formaInicial || '',
            // Data compensação controlada via x-ref ou model direto se possível
            
            init() {
                this.calcularCustoTotal();
                // Watchers para recalcular lucro se necessário, ou usar getters
            },

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
                        garantia: this.novoServico.garantia || null
                    });
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
            


            calcularVencimento() {
                const inputDate = this.$refs.dataCompensacao;
                if(this.forma === 'cartao_credito') {
                    let d = new Date();
                    d.setDate(d.getDate() + 30);
                    inputDate.value = d.toISOString().split('T')[0];
                } else if (this.forma === 'pix' || this.forma === 'dinheiro') {
                    inputDate.value = new Date().toISOString().split('T')[0];
                }
            },
            
            formatCurrency(value) {
                if (typeof value !== 'number') {
                    value = parseFloat(value) || 0;
                }
                return value.toFixed(2).replace('.', ',');
            },

            formatMoney(val) {
                 return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val || 0);
            }
        }));
    });
</script>
