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
    {{-- Seção 1: Dados Principais --}}
    <div class="form-section">
        <h3 class="form-section-title">Dados do Abastecimento</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Veículo --}}
            <div class="lg:col-span-1">
                <label for="aba_vei_id" class="block font-medium text-sm text-gray-700">Veículo*</label>
                <select name="aba_vei_id" id="aba_vei_id" class="mt-1 block w-full" required>
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
                <input type="date" name="aba_data" id="aba_data" class="mt-1 block w-full" value="{{ old('aba_data', $abastecimento ? $abastecimento->aba_data->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
            </div>
            {{-- Quilometragem --}}
            <div>
                <label for="aba_km" class="block font-medium text-sm text-gray-700">
                    Quilometragem* <span id="km_atual_veiculo" class="text-xs text-gray-500 font-normal"></span>
                </label>
                <input type="number" name="aba_km" id="aba_km" class="mt-1 block w-full" value="{{ old('aba_km', $abastecimento->aba_km ?? '') }}" required>
            </div>
            {{-- Fornecedor (Posto) --}}
            <div class="lg:col-span-2">
                <label for="aba_for_id" class="block font-medium text-sm text-gray-700">Posto / Ponto de Recarga</label>
                <select name="aba_for_id" id="aba_for_id" class="mt-1 block w-full">
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
    
    {{-- Seção 2: Detalhes do Combustível/Energia --}}
    <div class="form-section">
        <h3 class="form-section-title">Valores e Quantidades</h3>
        <div id="combustao-wrapper">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                 {{-- Tipo de Combustível (para Flex/Híbridos) --}}
                <div id="tipo_combustivel_wrapper" class="hidden">
                    <label for="aba_combustivel" class="block font-medium text-sm text-gray-700">Combustível Utilizado*</label>
                    <select name="aba_combustivel" id="aba_combustivel" class="mt-1 block w-full">
                        <option value="">Selecione</option>
                        <option value="1" @selected(old('aba_combustivel', $abastecimento->aba_combustivel ?? '') == 1)>Gasolina</option>
                        <option value="2" @selected(old('aba_combustivel', $abastecimento->aba_combustivel ?? '') == 2)>Etanol</option>
                        <option value="4" @selected(old('aba_combustivel', $abastecimento->aba_combustivel ?? '') == 4)>GNV</option>
                    </select>
                </div>
                {{-- Valores --}}
                <div>
                    <label for="aba_vlr_tot" class="block font-medium text-sm text-gray-700">Valor Total Pago (R$)*</label>
                    <input type="text" name="aba_vlr_tot" id="aba_vlr_tot" class="mt-1 block w-full calculator-input" value="{{ old('aba_vlr_tot', $abastecimento ? number_format($abastecimento->aba_vlr_tot, 2, ',', '.') : '') }}" required>
                </div>
                <div>
                    <label for="aba_qtd" class="block font-medium text-sm text-gray-700"><span id="label_unidade">Litros</span>*</label>
                    <input type="text" name="aba_qtd" id="aba_qtd" class="mt-1 block w-full calculator-input" value="{{ old('aba_qtd', $abastecimento ? number_format($abastecimento->aba_qtd, 3, ',', '.') : '') }}" required>
                </div>
                <div>
                    <label for="aba_vlr_und" class="block font-medium text-sm text-gray-700">Valor por <span id="label_valor_unidade">Litro</span> (R$)*</label>
                    <input type="text" name="aba_vlr_und" id="aba_vlr_und" class="mt-1 block w-full calculator-input" value="{{ old('aba_vlr_und', $abastecimento ? number_format($abastecimento->aba_vlr_und, 3, ',', '.') : '') }}" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                 {{-- Nível do Tanque --}}
                <div>
                    <label for="aba_tanque_inicio" class="block font-medium text-sm text-gray-700">Nível na Chegada</label>
                    <select name="aba_tanque_inicio" id="aba_tanque_inicio" class="mt-1 block w-full">
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

    {{-- Seção 3: Checklist e Observações --}}
    <div class="form-section">
        <h3 class="form-section-title">Verificações e Observações</h3>
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
                <textarea name="aba_obs" id="aba_obs" rows="4" class="mt-1 block w-full">{{ old('aba_obs', $abastecimento->aba_obs ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- Botões de Ação --}}
<div class="flex items-center justify-end mt-8">
    <a href="{{ route('abastecimentos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Salvar</button>
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
    
    const custoTotalInput = document.getElementById('aba_vlr_tot');
    const quantidadeInput = document.getElementById('aba_qtd');
    const valorUnidadeInput = document.getElementById('aba_vlr_und');
    const inputsCalculadora = [custoTotalInput, quantidadeInput, valorUnidadeInput];

    function updateFormVisibility() {
        const veiculoId = idVeiculoSelect.value;
        if (!veiculoId || !veiculosData[veiculoId]) {
            combustaoWrapper.classList.add('hidden');
            return;
        }

        const veiculo = veiculosData[veiculoId];
        const tipoCombustivelVeiculo = veiculo.combustivel_tipo; // 1-Gasolina, 2-Etanol, 3-Diesel, 4-GNV, 5-Elétrico, 6-Flex, 7-Híbrido

        // Reset geral
        combustaoWrapper.classList.remove('hidden');
        tipoCombustivelWrapper.classList.add('hidden');
        labelUnidade.textContent = 'Litros';
        labelValorUnidade.textContent = 'Litro';

        // Lógica para Elétricos (5)
        if (tipoCombustivelVeiculo == 5) {
            labelUnidade.textContent = 'kWh';
            labelValorUnidade.textContent = 'kWh';
        }
        
        // Lógica para GNV (4)
        if (tipoCombustivelVeiculo == 4) {
            labelUnidade.textContent = 'm³';
            labelValorUnidade.textContent = 'm³';
        }

        // Lógica para Flex (6) e Híbridos (7)
        if (tipoCombustivelVeiculo == 6 || tipoCombustivelVeiculo == 7) {
            tipoCombustivelWrapper.classList.remove('hidden');
            tipoCombustivelSelect.required = true;
        } else {
            tipoCombustivelSelect.required = false;
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
    $('#aba_qtd, #aba_vlr_und').mask('#.##0,000', {reverse: true});
});
</script>

