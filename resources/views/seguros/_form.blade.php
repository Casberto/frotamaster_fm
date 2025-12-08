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

<div class="space-y-8">
    {{-- TAB 1: DADOS GERAIS --}}
    <div id="tab-geral-content" x-show="tab === 'geral' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Informações da Apólice
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Veículo --}}
                <div class="md:col-span-1">
                    <label for="seg_vei_id" class="block font-medium text-sm text-gray-700">Veículo*</label>
                    <select name="seg_vei_id" id="seg_vei_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Selecione um Veículo</option>
                        @foreach($veiculos as $veiculo)
                            <option value="{{ $veiculo->vei_id }}" @selected(old('seg_vei_id', $apolice->seg_vei_id ?? '') == $veiculo->vei_id)>
                                {{ $veiculo->vei_placa }} - {{ $veiculo->vei_modelo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Seguradora --}}
                <div class="md:col-span-1">
                    <label for="seg_for_id" class="block font-medium text-sm text-gray-700">Seguradora*</label>
                    <select name="seg_for_id" id="seg_for_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Selecione a Seguradora</option>
                        @foreach($seguradoras as $seguradora)
                            <option value="{{ $seguradora->for_id }}" @selected(old('seg_for_id', $apolice->seg_for_id ?? '') == $seguradora->for_id)>
                                {{ $seguradora->for_nome_fantasia }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Número da Apólice --}}
                <div class="md:col-span-1">
                    <label for="seg_numero" class="block font-medium text-sm text-gray-700">Número da Apólice*</label>
                    <input type="text" name="seg_numero" id="seg_numero" value="{{ old('seg_numero', $apolice->seg_numero ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                </div>

                {{-- Tipo de Seguro --}}
                <div class="md:col-span-1">
                    <label for="seg_tipo" class="block font-medium text-sm text-gray-700">Tipo de Seguro</label>
                    <select name="seg_tipo" id="seg_tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Selecione</option>
                        <option value="Compreensivo (Total)" @selected(old('seg_tipo', $apolice->seg_tipo ?? '') == 'Compreensivo (Total)')>Compreensivo (Total)</option>
                        <option value="Responsabilidade Civil (RCF)" @selected(old('seg_tipo', $apolice->seg_tipo ?? '') == 'Responsabilidade Civil (RCF)')>Responsabilidade Civil (RCF)</option>
                        <option value="Roubo e Furto" @selected(old('seg_tipo', $apolice->seg_tipo ?? '') == 'Roubo e Furto')>Roubo e Furto</option>
                        <option value="Acidentes Pessoais (APP)" @selected(old('seg_tipo', $apolice->seg_tipo ?? '') == 'Acidentes Pessoais (APP)')>Acidentes Pessoais (APP)</option>
                        <option value="Outro" @selected(old('seg_tipo', $apolice->seg_tipo ?? '') == 'Outro')>Outro</option>
                    </select>
                </div>

                {{-- Datas --}}
                <div class="md:col-span-1">
                    <label for="seg_inicio" class="block font-medium text-sm text-gray-700">Início da Vigência</label>
                    <input type="date" name="seg_inicio" id="seg_inicio" value="{{ old('seg_inicio', isset($apolice->seg_inicio) ? $apolice->seg_inicio->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                <div class="md:col-span-1">
                    <label for="seg_fim" class="block font-medium text-sm text-gray-700">Fim da Vigência</label>
                    <input type="date" name="seg_fim" id="seg_fim" value="{{ old('seg_fim', isset($apolice->seg_fim) ? $apolice->seg_fim->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>

                {{-- Valores --}}
                <div class="md:col-span-1">
                    <label for="seg_valor_total" class="block font-medium text-sm text-gray-700">Valor Total (R$)</label>
                    <div x-data="{
                        raw: '{{ old('seg_valor_total', $apolice->seg_valor_total ?? '') }}',
                        display: '',
                        format(value) {
                            if (!value) return '';
                            let number = parseFloat(value).toFixed(2);
                            return number.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        },
                        init() {
                            if (this.raw) {
                                this.display = this.format(this.raw);
                            }
                        },
                        input(e) {
                            let value = e.target.value.replace(/\D/g, '');
                            if (!value) {
                                this.raw = '';
                                this.display = '';
                                return;
                            }
                            let floatVal = parseFloat(value) / 100;
                            this.raw = floatVal.toFixed(2);
                            this.display = this.format(this.raw);
                            e.target.value = this.display;
                        }
                    }" x-init="init">
                        <input type="text" x-model="display" @input="input" id="seg_valor_total_display" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="0,00">
                        <input type="hidden" name="seg_valor_total" x-model="raw">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 md:col-span-1">
                     <div>
                        <label for="seg_parcelas" class="block font-medium text-sm text-gray-700">Nº de Parcelas</label>
                        <input type="number" name="seg_parcelas" id="seg_parcelas" value="{{ old('seg_parcelas', $apolice->seg_parcelas ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="seg_franquia" class="block font-medium text-sm text-gray-700">Franquia (R$)</label>
                        <div x-data="{
                            raw: '{{ old('seg_franquia', $apolice->seg_franquia ?? '') }}',
                            display: '',
                            format(value) {
                                if (!value) return '';
                                let number = parseFloat(value).toFixed(2);
                                return number.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            },
                            init() {
                                if (this.raw) {
                                    this.display = this.format(this.raw);
                                }
                            },
                            input(e) {
                                let value = e.target.value.replace(/\D/g, '');
                                if (!value) {
                                    this.raw = '';
                                    this.display = '';
                                    return;
                                }
                                let floatVal = parseFloat(value) / 100;
                                this.raw = floatVal.toFixed(2);
                                this.display = this.format(this.raw);
                                e.target.value = this.display;
                            }
                        }" x-init="init">
                            <input type="text" x-model="display" @input="input" id="seg_franquia_display" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="0,00">
                            <input type="hidden" name="seg_franquia" x-model="raw">
                        </div>
                    </div>
                </div>

                {{-- Status (Apenas na edição) --}}
                @if(isset($apolice) && $apolice->exists)
                <div class="md:col-span-1">
                    <label for="seg_status" class="block font-medium text-sm text-gray-700">Status</label>
                    <select name="seg_status" id="seg_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="Ativo" @selected(old('seg_status', $apolice->seg_status ?? '') == 'Ativo')>Ativo</option>
                        <option value="Vencida" @selected(old('seg_status', $apolice->seg_status ?? '') == 'Vencida')>Vencida</option>
                        <option value="Em renovação" @selected(old('seg_status', $apolice->seg_status ?? '') == 'Em renovação')>Em renovação</option>
                        <option value="Cancelada" @selected(old('seg_status', $apolice->seg_status ?? '') == 'Cancelada')>Cancelada</option>
                    </select>
                </div>
                @endif

                {{-- Observações --}}

                {{-- Upload de Arquivo --}}
                <div class="md:col-span-2 mb-4">
                    <label for="seg_arquivo" class="block font-medium text-sm text-gray-700">Documento da Apólice (PDF/Imagem)</label>
                    <input type="file" name="seg_arquivo" id="seg_arquivo" class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100
                    ">
                    @if(isset($apolice) && $apolice->seg_arquivo)
                        <p class="mt-1 text-xs text-gray-500">
                            Arquivo atual: <a href="{{ route('seguros.download', $apolice->seg_id) }}" target="_blank" class="text-blue-600 hover:underline">Baixar Documento</a>
                        </p>
                    @endif
                </div>

                {{-- Observações --}}
                <div class="md:col-span-2">
                    <label for="seg_obs" class="block font-medium text-sm text-gray-700">Observações</label>
                    <textarea name="seg_obs" id="seg_obs" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('seg_obs', $apolice->seg_obs ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

