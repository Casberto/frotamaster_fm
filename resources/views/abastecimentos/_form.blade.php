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
                        <option value="{{ $veiculo->id }}" @selected(old('id_veiculo', $abastecimento->id_veiculo ?? '') == $veiculo->id)>
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
                    {{-- As opções serão preenchidas dinamicamente pelo JavaScript --}}
                </select>
            </div>

            <div class="md:col-span-1">
                <label for="quilometragem" class="block font-medium text-sm text-gray-700">Quilometragem*</label>
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
                <label for="nivel_tanque_inicio" class="block font-medium text-sm text-gray-700">Nível na Chegada</label>
                <select name="nivel_tanque_inicio" id="nivel_tanque_inicio" class="mt-1 block w-full">
                    <option value="">Não informado</option>
                    <option value="reserva" @selected(old('nivel_tanque_inicio', $abastecimento->nivel_tanque_inicio ?? '') == 'reserva')>Na reserva</option>
                    <option value="1/4" @selected(old('nivel_tanque_inicio', $abastecimento->nivel_tanque_inicio ?? '') == '1/4')>1/4</option>
                    <option value="1/2" @selected(old('nivel_tanque_inicio', $abastecimento->nivel_tanque_inicio ?? '') == '1/2')>1/2 (Meio tanque)</option>
                    <option value="3/4" @selected(old('nivel_tanque_inicio', $abastecimento->nivel_tanque_inicio ?? '') == '3/4')>3/4</option>
                </select>
            </div>
            <div class="flex items-center pt-6">
                 <input type="checkbox" name="tanque_cheio" id="tanque_cheio" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(old('tanque_cheio', $abastecimento->tanque_cheio ?? false))>
                <label for="tanque_cheio" class="ml-2 block text-sm text-gray-900">Completou o tanque?</label>
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
    const tipoCombustivelSelect = document.getElementById('tipo_combustivel');
    const labelUnidade = document.getElementById('label_unidade');
    const labelValorUnidade = document.getElementById('label_valor_unidade');
    const avisoCapacidade = document.getElementById('avisoCapacidade');
    const inputsCalculadora = document.querySelectorAll('.calculator-input');
    let capacidadeTanque = null;

    // --- Máscaras ---
    $('#custo_total').mask('000.000.000,00', {reverse: true});
    $('#quantidade').mask('000.000,000', {reverse: true});
    $('#valor_por_unidade').mask('000.000,000', {reverse: true});

    // --- Função Principal de Atualização ---
    async function updateFormForVehicle(veiculoId) {
        if (!veiculoId) {
            resetFormState();
            return;
        }

        try {
            const response = await fetch(`/api/veiculo/${veiculoId}`);
            if (!response.ok) throw new Error('Falha ao buscar dados do veículo.');
            
            const veiculo = await response.json();
            
            capacidadeTanque = parseFloat(veiculo.capacidade_tanque);
            updateUnidadeMedida(veiculo.tipo_combustivel);
            updateTipoCombustivel(veiculo.tipo_combustivel);

        } catch (error) {
            console.error('Erro no updateFormForVehicle:', error);
            resetFormState();
        }
    }

    // --- Funções Auxiliares ---
    function updateUnidadeMedida(tipo) {
        if (tipo === 'eletrico') {
            labelUnidade.textContent = 'kWh';
            labelValorUnidade.textContent = 'kWh';
        } else {
            labelUnidade.textContent = 'Litros';
            labelValorUnidade.textContent = 'Litro';
        }
    }

    function updateTipoCombustivel(tipo) {
        tipoCombustivelSelect.innerHTML = '';
        let options = [];

        const combustivelMap = {
            'gasolina': 'Gasolina',
            'etanol': 'Etanol',
            'diesel': 'Diesel',
            'gnv': 'GNV'
        };

        if (tipo === 'flex') {
            options.push({ value: 'gasolina', text: 'Gasolina' });
            options.push({ value: 'etanol', text: 'Etanol' });
        } else if (combustivelMap[tipo]) {
            options.push({ value: tipo, text: combustivelMap[tipo] });
        }

        if (options.length > 0) {
            options.forEach(opt => tipoCombustivelSelect.add(new Option(opt.text, opt.value)));
            
            const oldCombustivel = "{{ old('tipo_combustivel', $abastecimento->tipo_combustivel ?? '') }}";
            if (oldCombustivel) {
                tipoCombustivelSelect.value = oldCombustivel;
            }

            tipoCombustivelWrapper.classList.remove('hidden');
            tipoCombustivelSelect.setAttribute('required', 'required');
        } else {
            tipoCombustivelWrapper.classList.add('hidden');
            tipoCombustivelSelect.removeAttribute('required');
        }
    }

    function resetFormState() {
        labelUnidade.textContent = 'Litros';
        labelValorUnidade.textContent = 'Litro';
        capacidadeTanque = null;
        avisoCapacidade.classList.add('hidden');
        tipoCombustivelWrapper.classList.add('hidden');
        tipoCombustivelSelect.innerHTML = '';
        tipoCombustivelSelect.removeAttribute('required');
    }

    // --- Lógica da Calculadora ---
    let lastEdited = null;
    inputsCalculadora.forEach(input => {
        input.addEventListener('focus', () => lastEdited = input.id);
        input.addEventListener('keyup', calcularValores);
    });

    function calcularValores() {
        const custoTotal = parseFloat($('#custo_total').val().replace(/\./g, '').replace(',', '.')) || 0;
        const quantidade = parseFloat($('#quantidade').val().replace(/\./g, '').replace(',', '.')) || 0;
        const valorUnidade = parseFloat($('#valor_por_unidade').val().replace(/\./g, '').replace(',', '.')) || 0;

        if (lastEdited !== 'valor_por_unidade' && custoTotal > 0 && quantidade > 0) {
            $('#valor_por_unidade').val((custoTotal / quantidade).toFixed(3).replace('.', ',')).trigger('input');
        } else if (lastEdited !== 'custo_total' && quantidade > 0 && valorUnidade > 0) {
            $('#custo_total').val((quantidade * valorUnidade).toFixed(2).replace('.', ',')).trigger('input');
        }
        
        if (capacidadeTanque && quantidade > capacidadeTanque) {
            avisoCapacidade.classList.remove('hidden');
        } else {
            avisoCapacidade.classList.add('hidden');
        }
    }
    
    // --- Inicialização ---
    idVeiculoSelect.addEventListener('change', () => updateFormForVehicle(idVeiculoSelect.value));
    
    if (idVeiculoSelect.value) {
        updateFormForVehicle(idVeiculoSelect.value);
    }
});
</script>
