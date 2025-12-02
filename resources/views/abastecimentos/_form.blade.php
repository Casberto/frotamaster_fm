@php
    // Para facilitar o acesso, definimos as variáveis aqui se não existirem (caso de 'create')
    $abastecimento = $abastecimento ?? null;
@endphp

@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6" role="alert">
        <p class="font-bold">Atenção</p>
        <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
    </div>
@endif

<div class="space-y-8">
    {{-- TAB 1: DADOS GERAIS --}}
    <div id="tab-geral-content" x-show="tab === 'geral' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Dados do Abastecimento
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Veículo --}}
                <div class="lg:col-span-1">
                    <label for="aba_vei_id" class="block font-medium text-sm text-gray-700">Veículo*</label>
                    <select name="aba_vei_id" id="aba_vei_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Selecione um veículo</option>
                        @foreach($veiculos as $veiculo)
                            <option value="{{ $veiculo->vei_id }}" @selected(old('aba_vei_id', $abastecimento->aba_vei_id ?? request()->get('vei_id')) == $veiculo->vei_id)>
                                {{ $veiculo->placaModelo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- Data --}}
                <div>
                    <label for="aba_data" class="block font-medium text-sm text-gray-700">Data*</label>
                    <input type="date" name="aba_data" id="aba_data" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('aba_data', $abastecimento && $abastecimento->aba_data ? $abastecimento->aba_data->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                </div>
                {{-- Quilometragem --}}
                <div>
                    <label for="aba_km" class="block font-medium text-sm text-gray-700">
                        Quilometragem* <span id="km_atual_veiculo" class="text-xs text-gray-500 font-normal"></span>
                    </label>
                    <input type="number" name="aba_km" id="aba_km" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('aba_km', $abastecimento->aba_km ?? '') }}" required>
                </div>
                {{-- Fornecedor (Posto) --}}
                <div class="lg:col-span-2">
                    <label for="aba_for_id" class="block font-medium text-sm text-gray-700">Posto / Ponto de Recarga</label>
                    <select name="aba_for_id" id="aba_for_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Não informado</option>
                        @foreach($fornecedores as $fornecedor)
                            <option value="{{ $fornecedor->for_id }}" @selected(old('aba_for_id', $abastecimento->aba_for_id ?? '') == $fornecedor->for_id)>
                                {{ $fornecedor->for_nome_fantasia }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    {{-- TAB 2: VALORES E QUANTIDADES --}}
    <div id="tab-valores-content" x-show="tab === 'valores' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force" style="display: none;">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Valores e Quantidades
            </h3>
            <div id="select-vehicle-message" class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-lg font-medium">Nenhum veículo selecionado</p>
                <p class="text-sm mt-1">Por favor, selecione um veículo na aba <strong>Dados Gerais</strong> para habilitar o preenchimento dos valores.</p>
                <button type="button" @click="tab = 'geral'" class="mt-4 px-4 py-2 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition text-sm font-medium">
                    Ir para Dados Gerais
                </button>
            </div>
            <div id="combustao-wrapper" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                     {{-- Tipo de Combustível (para Flex/Híbridos) --}}
                    <div id="tipo_combustivel_wrapper" class="hidden">
                        <label for="aba_combustivel" class="block font-medium text-sm text-gray-700">Combustível Utilizado*</label>
                        <select name="aba_combustivel" id="aba_combustivel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Selecione</option>
                            <option value="1" @selected(old('aba_combustivel', $abastecimento->aba_combustivel ?? '') == 1)>Gasolina</option>
                            <option value="2" @selected(old('aba_combustivel', $abastecimento->aba_combustivel ?? '') == 2)>Etanol</option>
                            <option value="3" @selected(old('aba_combustivel', $abastecimento->aba_combustivel ?? '') == 3)>Diesel</option>
                            <option value="4" @selected(old('aba_combustivel', $abastecimento->aba_combustivel ?? '') == 4)>GNV</option>
                        </select>
                    </div>
                    {{-- Valores --}}
                    <div>
                        <label for="aba_vlr_tot" class="block font-medium text-sm text-gray-700">Valor Total Pago (R$)*</label>
                        <input type="text" name="aba_vlr_tot" id="aba_vlr_tot" class="mt-1 block w-full calculator-input border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('aba_vlr_tot', $abastecimento ? number_format($abastecimento->aba_vlr_tot, 2, ',', '.') : '') }}" required>
                    </div>
                    <div>
                        <label for="aba_qtd" class="block font-medium text-sm text-gray-700"><span id="label_unidade">Litros</span>*</label>
                        <input type="text" name="aba_qtd" id="aba_qtd" class="mt-1 block w-full calculator-input border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('aba_qtd', $abastecimento ? number_format($abastecimento->aba_qtd, 3, ',', '.') : '') }}" required>
                    </div>
                    <div>
                        <label for="aba_vlr_und" class="block font-medium text-sm text-gray-700">Valor por <span id="label_valor_unidade">Litro</span> (R$)*</label>
                        <input type="text" name="aba_vlr_und" id="aba_vlr_und" class="mt-1 block w-full calculator-input border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('aba_vlr_und', $abastecimento ? number_format($abastecimento->aba_vlr_und, 3, ',', '.') : '') }}" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                     {{-- Nível do Tanque --}}
                    <div>
                        <label for="aba_tanque_inicio" class="block font-medium text-sm text-gray-700">Nível na Chegada</label>
                        <select name="aba_tanque_inicio" id="aba_tanque_inicio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Não informado</option>
                            <option value="reserva" @selected(old('aba_tanque_inicio', $abastecimento->aba_tanque_inicio ?? '') == 'reserva')>Reserva</option>
                            <option value="25" @selected(old('aba_tanque_inicio', $abastecimento->aba_tanque_inicio ?? '') == '25')>1/4</option>
                            <option value="50" @selected(old('aba_tanque_inicio', $abastecimento->aba_tanque_inicio ?? '') == '50')>1/2</option>
                            <option value="75" @selected(old('aba_tanque_inicio', $abastecimento->aba_tanque_inicio ?? '') == '75')>3/4</option>
                        </select>
                    </div>
                    {{-- Tanque Cheio --}}
                    <div class="flex items-center pt-6">
                        <label for="aba_tanque_cheio" class="flex items-center cursor-pointer">
                            <input type="checkbox" name="aba_tanque_cheio" id="aba_tanque_cheio" value="1" @checked(old('aba_tanque_cheio', $abastecimento->aba_tanque_cheio ?? false)) class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-2 block text-sm text-gray-900">Completou o tanque/carga?</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 3: CHECKLIST E OBSERVAÇÕES --}}
    <div id="tab-checklist-content" x-show="tab === 'checklist' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force" style="display: none;">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Verificações e Observações
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Checklist --}}
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-600">Itens verificados no local:</h4>
                    <label for="aba_pneus_calibrados" class="flex items-center cursor-pointer">
                        <input type="checkbox" name="aba_pneus_calibrados" id="aba_pneus_calibrados" value="1" @checked(old('aba_pneus_calibrados', $abastecimento->aba_pneus_calibrados ?? false)) class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <span class="ml-2 block text-sm text-gray-900">Pneus Calibrados</span>
                    </label>
                    <label for="aba_agua_verificada" class="flex items-center cursor-pointer">
                        <input type="checkbox" name="aba_agua_verificada" id="aba_agua_verificada" value="1" @checked(old('aba_agua_verificada', $abastecimento->aba_agua_verificada ?? false)) class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <span class="ml-2 block text-sm text-gray-900">Nível da Água do Radiador</span>
                    </label>
                    <label for="aba_oleo_verificado" class="flex items-center cursor-pointer">
                        <input type="checkbox" name="aba_oleo_verificado" id="aba_oleo_verificado" value="1" @checked(old('aba_oleo_verificado', $abastecimento->aba_oleo_verificado ?? false)) class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <span class="ml-2 block text-sm text-gray-900">Nível do Óleo do Motor</span>
                    </label>
                </div>
                {{-- Observações --}}
                <div>
                     <label for="aba_obs" class="block font-medium text-sm text-gray-700">Observações</label>
                    <textarea name="aba_obs" id="aba_obs" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('aba_obs', $abastecimento->aba_obs ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Mapa de Dados dos Veículos (injetado pelo Blade) ---
    const veiculosData = {!! json_encode($veiculosData ?? []) !!};
    
    // --- Seletores de Elementos ---
    const idVeiculoSelect = document.getElementById('aba_vei_id');
    const kmAtualVeiculoSpan = document.getElementById('km_atual_veiculo');
    const tipoCombustivelWrapper = document.getElementById('tipo_combustivel_wrapper');
    const tipoCombustivelSelect = document.getElementById('aba_combustivel');
    const labelUnidade = document.getElementById('label_unidade');
    const labelValorUnidade = document.getElementById('label_valor_unidade');
    const combustaoWrapper = document.getElementById('combustao-wrapper');
    const selectVehicleMessage = document.getElementById('select-vehicle-message');
    
    const custoTotalInput = document.getElementById('aba_vlr_tot');
    const quantidadeInput = document.getElementById('aba_qtd');
    const valorUnidadeInput = document.getElementById('aba_vlr_und');
    const inputsCalculadora = [custoTotalInput, quantidadeInput, valorUnidadeInput];

    function updateFormVisibility() {
        const veiculoId = idVeiculoSelect.value;
        if (!veiculoId || !veiculosData[veiculoId]) {
            combustaoWrapper.classList.add('hidden');
            if(selectVehicleMessage) selectVehicleMessage.classList.remove('hidden');
            return;
        }

        const veiculo = veiculosData[veiculoId];
        const tipoCombustivelVeiculo = veiculo.combustivel_tipo; // 1-Gasolina, 2-Etanol, 3-Diesel, 4-GNV, 5-Elétrico, 6-Flex, 7-Híbrido

        // Reset geral
        combustaoWrapper.classList.remove('hidden');
        if(selectVehicleMessage) selectVehicleMessage.classList.add('hidden');
        tipoCombustivelWrapper.classList.add('hidden');
        labelUnidade.textContent = 'Litros';
        labelValorUnidade.textContent = 'Litro';

        // Lógica para Elétricos (5)
        if (tipoCombustivelVeiculo == 5) {
            labelUnidade.textContent = 'kWh';
            labelValorUnidade.textContent = 'kWh';
            // Para elétricos, não usamos o select de combustível (pode ser null ou tratado no backend)
             tipoCombustivelSelect.value = ""; 
        }
        
        // Lógica para GNV (4)
        if (tipoCombustivelVeiculo == 4) {
            labelUnidade.textContent = 'm³';
            labelValorUnidade.textContent = 'm³';
            tipoCombustivelSelect.value = "4"; // Auto-seleciona GNV
        }

        // Lógica para Flex (6) e Híbridos (7)
        if (tipoCombustivelVeiculo == 6 || tipoCombustivelVeiculo == 7) {
            tipoCombustivelWrapper.classList.remove('hidden');
            tipoCombustivelSelect.required = true;
            // Não auto-seleciona, usuário deve escolher
        } else if (tipoCombustivelVeiculo != 5 && tipoCombustivelVeiculo != 4) {
             // Veículos mono-combustível (Gasolina=1, Etanol=2, Diesel=3)
             // Auto-seleciona o tipo correspondente
             tipoCombustivelSelect.value = tipoCombustivelVeiculo;
             tipoCombustivelSelect.required = false; // Não é obrigatório interagir pois já está setado
        }

        // Atualiza KM
        kmAtualVeiculoSpan.textContent = `(Atual: ${veiculo.km} km)`;
    }
    
    tipoCombustivelSelect.addEventListener('change', () => {
        const veiculoId = idVeiculoSelect.value;
        if (!veiculoId) return;

        const tipoCombustivelVeiculo = veiculosData[veiculoId].combustivel_tipo;
        
        // Se for Híbrido, pode ter GNV
        if (tipoCombustivelVeiculo == 7 && tipoCombustivelSelect.value == 4) { // 4 = GNV
            labelUnidade.textContent = 'm³';
            labelValorUnidade.textContent = 'm³';
        } else {
            labelUnidade.textContent = 'Litros';
            labelValorUnidade.textContent = 'Litro';
        }
    });

    idVeiculoSelect.addEventListener('change', updateFormVisibility);
    updateFormVisibility(); // Executa ao carregar a página

    // --- LÓGICA DA CALCULADORA AUTOMÁTICA ---
    let lastEdited = null;
    inputsCalculadora.forEach(input => {
        input.addEventListener('focus', () => { lastEdited = input.id; });
        input.addEventListener('keyup', () => {
            const clean = (val) => parseFloat(val.replace(/\./g, '').replace(',', '.')) || 0;
            
            const custoTotal = clean(custoTotalInput.value);
            const quantidade = clean(quantidadeInput.value);
            const valorUnidade = clean(valorUnidadeInput.value);

            if (lastEdited !== 'aba_vlr_und' && custoTotal > 0 && quantidade > 0) {
                const novoValor = (custoTotal / quantidade).toFixed(3).replace('.', ',');
                valorUnidadeInput.value = novoValor;
            } else if (lastEdited !== 'aba_vlr_tot' && quantidade > 0 && valorUnidade > 0) {
                const novoValor = (quantidade * valorUnidade).toFixed(2).replace('.', ',');
                custoTotalInput.value = novoValor;
            } else if (lastEdited !== 'aba_qtd' && custoTotal > 0 && valorUnidade > 0) {
                const novoValor = (custoTotal / valorUnidade).toFixed(3).replace('.', ',');
                quantidadeInput.value = novoValor;
            }
        });
    });

    // Máscaras de valor
    $('#aba_vlr_tot').mask('#.##0,00', {reverse: true});
    // $('#aba_qtd').mask('#.##0,000', {reverse: true}); // Removido para permitir digitação livre
    
    // Para valor unitário e quantidade, permitimos digitar a vírgula livremente
    // e formatamos ao sair do campo.
    $('#aba_vlr_und, #aba_qtd').on('input', function() {
        let value = $(this).val();
        
        // Remove caracteres inválidos (apenas números e vírgula)
        value = value.replace(/[^0-9,]/g, '');
        
        // Garante apenas uma vírgula
        const parts = value.split(',');
        if (parts.length > 2) {
            value = parts[0] + ',' + parts.slice(1).join('');
        }
        
        // Limita a 3 casas decimais
        if (parts.length > 1 && parts[1].length > 3) {
             value = parts[0] + ',' + parts[1].substring(0, 3);
        }
        
        if (value !== $(this).val()) {
            $(this).val(value);
        }
    });

    $('#aba_vlr_und, #aba_qtd').on('blur', function() {
        let val = $(this).val().replace(/\./g, '').replace(',', '.');
        if (val) {
            $(this).val(parseFloat(val).toFixed(3).replace('.', ','));
        }
    });
});
</script>
