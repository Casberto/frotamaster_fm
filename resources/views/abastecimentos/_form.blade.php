{{--
Este é o código para o formulário de cadastro/edição de abastecimento.
Ele contém a correção para o checkbox e a nova lógica de exibição do tipo de combustível.
--}}

@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6" role="alert">
        <p class="font-bold">Atenção</p>
        <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
    </div>
@endif

<div id="avisoCapacidade" class="hidden bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md mb-6" role="alert">
    <p><span class="font-bold">Aviso:</span> A quantidade informada ultrapassa a capacidade do tanque/bateria deste veículo.</p>
</div>

<div class="space-y-8">
    <div class="form-section">
        <h3 class="form-section-title">Dados do Abastecimento</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="id_veiculo" class="block font-medium text-sm text-gray-700">Veículo*</label>
                <select name="id_veiculo" id="id_veiculo" class="mt-1 block w-full" required>
                    <option value="">Selecione um veículo</option>
                    @foreach($veiculos as $veiculo)
                        <option value="{{ $veiculo->id }}" data-tipo-combustivel="{{ $veiculo->tipo_combustivel }}" @selected(old('id_veiculo', $abastecimento->id_veiculo ?? request()->get('id_veiculo')) == $veiculo->id)>
                            {{ $veiculo->placa }} - {{ $veiculo->marca }} {{ $veiculo->modelo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="data_abastecimento" class="block font-medium text-sm text-gray-700">Data*</label>
                <input type="date" name="data_abastecimento" id="data_abastecimento" class="mt-1 block w-full" value="{{ old('data_abastecimento', isset($abastecimento->data_abastecimento) ? \Carbon\Carbon::parse($abastecimento->data_abastecimento)->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
            </div>

            <div id="tipo_combustivel_wrapper" class="hidden md:col-span-2">
                <label for="tipo_combustivel" class="block font-medium text-sm text-gray-700">Tipo de Combustível*</label>
                <select name="tipo_combustivel" id="tipo_combustivel" class="mt-1 block w-full">
                    <option value="">Selecione o combustível</option>
                    <option value="gasolina" @selected(old('tipo_combustivel', $abastecimento->tipo_combustivel ?? '') == 'gasolina')>Gasolina</option>
                    <option value="etanol" @selected(old('tipo_combustivel', $abastecimento->tipo_combustivel ?? '') == 'etanol')>Etanol</option>
                    <option value="gnv" @selected(old('tipo_combustivel', $abastecimento->tipo_combustivel ?? '') == 'gnv')>GNV</option>
                </select>
            </div>

            <div class="md:col-span-1">
                <label for="quilometragem" class="block font-medium text-sm text-gray-700">
                    Quilometragem* <span id="km_atual_veiculo" class="text-xs text-gray-500 font-normal"></span>
                </label>
                <input type="number" name="quilometragem" id="quilometragem" class="mt-1 block w-full" value="{{ old('quilometragem', $abastecimento->quilometragem ?? '') }}" required>
            </div>
            <div class="md:col-span-1">
                <label for="nome_posto" class="block font-medium text-sm text-gray-700">Posto / Ponto de Recarga</label>
                <input type="text" name="nome_posto" id="nome_posto" class="mt-1 block w-full" value="{{ old('nome_posto', $abastecimento->nome_posto ?? '') }}">
            </div>
        </div>
    </div>
    
    <div class="form-section">
        <h3 class="form-section-title">Valores e Quantidades</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="custo_total" class="block font-medium text-sm text-gray-700">Valor Total Pago (R$)*</label>
                <input type="text" name="custo_total" id="custo_total" class="mt-1 block w-full calculator-input" value="{{ old('custo_total', $abastecimento->custo_total ?? '') }}" required>
            </div>
            <div>
                <label for="quantidade" class="block font-medium text-sm text-gray-700"><span id="label_unidade">Litros</span>*</label>
                <input type="text" name="quantidade" id="quantidade" class="mt-1 block w-full calculator-input" value="{{ old('quantidade', $abastecimento->quantidade ?? '') }}" required>
            </div>
            <div>
                <label for="valor_por_unidade" class="block font-medium text-sm text-gray-700">Valor por <span id="label_valor_unidade">Litro</span> (R$)*</label>
                <input type="text" name="valor_por_unidade" id="valor_por_unidade" class="mt-1 block w-full calculator-input" value="{{ old('valor_por_unidade', $abastecimento->valor_por_unidade ?? '') }}" required>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h3 class="form-section-title">Nível do Tanque</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nivel_tanque_chegada" class="block font-medium text-sm text-gray-700">Nível na Chegada</label>
                <select name="nivel_tanque_chegada" id="nivel_tanque_chegada" class="mt-1 block w-full">
                    <option value="">Não informado</option>
                    <option value="reserva" @selected(old('nivel_tanque_chegada', $abastecimento->nivel_tanque_chegada ?? '') == 'reserva')>Na reserva</option>
                    <option value="1/4" @selected(old('nivel_tanque_chegada', $abastecimento->nivel_tanque_chegada ?? '') == '1/4')>1/4</option>
                    <option value="1/2" @selected(old('nivel_tanque_chegada', $abastecimento->nivel_tanque_chegada ?? '') == '1/2')>1/2 (Meio tanque)</option>
                    <option value="3/4" @selected(old('nivel_tanque_chegada', $abastecimento->nivel_tanque_chegada ?? '') == '3/4')>3/4</option>
                </select>
            </div>
            <div>
                <label for="nivel_tanque_saida" class="block font-medium text-sm text-gray-700">Nível na Saída</label>
                <select name="nivel_tanque_saida" id="nivel_tanque_saida" class="mt-1 block w-full">
                    <option value="">Não informado</option>
                    <option value="reserva" @selected(old('nivel_tanque_saida', $abastecimento->nivel_tanque_saida ?? '') == 'reserva')>Na reserva</option>
                    <option value="1/4" @selected(old('nivel_tanque_saida', $abastecimento->nivel_tanque_saida ?? '') == '1/4')>1/4</option>
                    <option value="1/2" @selected(old('nivel_tanque_saida', $abastecimento->nivel_tanque_saida ?? '') == '1/2')>1/2 (Meio tanque)</option>
                    <option value="3/4" @selected(old('nivel_tanque_saida', $abastecimento->nivel_tanque_saida ?? '') == '3/4')>3/4</option>
                    <option value="cheio" @selected(old('nivel_tanque_saida', $abastecimento->nivel_tanque_saida ?? '') == 'cheio')>Tanque Cheio</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('abastecimentos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Salvar</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Seletores de Elementos ---
        const idVeiculoSelect = document.getElementById('id_veiculo');
        const tipoCombustivelWrapper = document.getElementById('tipo_combustivel_wrapper');
        const labelUnidade = document.getElementById('label_unidade');
        const labelValorUnidade = document.getElementById('label_valor_unidade');
        const custoTotalInput = document.getElementById('custo_total');
        const quantidadeInput = document.getElementById('quantidade');
        const valorUnidadeInput = document.getElementById('valor_por_unidade');
        const inputsCalculadora = [custoTotalInput, quantidadeInput, valorUnidadeInput];

        // --- LÓGICA DE EXIBIÇÃO DO CAMPO DE COMBUSTÍVEL ---
        function toggleCombustivelField() {
            const selectedOption = idVeiculoSelect.options[idVeiculoSelect.selectedIndex];
            if (!selectedOption || !selectedOption.dataset.tipoCombustivel) {
                tipoCombustivelWrapper.classList.add('hidden');
                return;
            };

            const tipoCombustivelVeiculo = selectedOption.dataset.tipoCombustivel;
            const tiposQueExigemSelecao = ['flex', 'gnv'];

            if (tiposQueExigemSelecao.includes(tipoCombustivelVeiculo)) {
                tipoCombustivelWrapper.classList.remove('hidden');
            } else {
                tipoCombustivelWrapper.classList.add('hidden');
            }

            if (tipoCombustivelVeiculo === 'eletrico') {
                labelUnidade.textContent = 'kWh';
                labelValorUnidade.textContent = 'kWh';
            } else {
                labelUnidade.textContent = 'Litros';
                labelValorUnidade.textContent = 'Litro';
            }
        }

        idVeiculoSelect.addEventListener('change', toggleCombustivelField);
        toggleCombustivelField(); // Executa na carga da página

        // --- LÓGICA DA CALCULADORA AUTOMÁTICA ---
        let lastEdited = null;
        inputsCalculadora.forEach(input => {
            input.addEventListener('mousedown', () => { lastEdited = input.id; });
            input.addEventListener('keyup', () => {
                const custoTotal = parseFloat($(custoTotalInput).val().replace(/\./g, '').replace(',', '.')) || 0;
                const quantidade = parseFloat($(quantidadeInput).val().replace(/\./g, '').replace(',', '.')) || 0;
                const valorUnidade = parseFloat($(valorUnidadeInput).val().replace(/\./g, '').replace(',', '.')) || 0;

                if (lastEdited !== 'valor_por_unidade' && custoTotal > 0 && quantidade > 0) {
                    const novoValor = (custoTotal / quantidade).toFixed(3).replace('.', ',');
                    $(valorUnidadeInput).val(novoValor);
                } 
                else if (lastEdited !== 'custo_total' && quantidade > 0 && valorUnidade > 0) {
                    const novoValor = (quantidade * valorUnidade).toFixed(2).replace('.', ',');
                    $(custoTotalInput).val(novoValor);
                } 
                else if (lastEdited !== 'quantidade' && custoTotal > 0 && valorUnidade > 0) {
                    const novoValor = (custoTotal / valorUnidade).toFixed(3).replace('.', ',');
                    $(quantidadeInput).val(novoValor);
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // O mapa de KMs é injetado aqui pelo Blade
        const veiculosKmMap = {!! json_encode($veiculosKmMap ?? []) !!};
        
        const idVeiculoSelect = document.getElementById('id_veiculo');
        const kmAtualVeiculoSpan = document.getElementById('km_atual_veiculo');

        function updateKmDisplay() {
            const veiculoId = idVeiculoSelect.value;
            if (veiculoId && veiculosKmMap[veiculoId] !== undefined) {
                kmAtualVeiculoSpan.textContent = `(Atual: ${veiculosKmMap[veiculoId]} km)`;
            } else {
                kmAtualVeiculoSpan.textContent = '';
            }
        }

        // Adiciona o listener e executa na carga da página para o caso de um formulário de edição
        idVeiculoSelect.addEventListener('change', updateKmDisplay);
        updateKmDisplay(); 
    });
</script>
