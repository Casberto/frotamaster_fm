{{-- Bloco para exibir erros de validação --}}
@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6" role="alert">
        <p class="font-bold">Atenção! Verifique os erros abaixo:</p>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    // Listas de opções para os selects
    $marcas = ['Agrale', 'Aston Martin', 'Audi', 'BMW', 'BYD', 'CAOA Chery', 'Chevrolet', 'Citroën', 'Dodge', 'Ferrari', 'Fiat', 'Ford', 'GWM', 'Honda', 'Hyundai', 'Jac', 'Jaguar', 'Jeep', 'Kia', 'Lamborghini', 'Land Rover', 'Lexus', 'Maserati', 'McLaren', 'Mercedes-Benz', 'Mini', 'Mitsubishi', 'Nissan', 'Peugeot', 'Porsche', 'Ram', 'Renault', 'Rolls-Royce', 'Subaru', 'Suzuki', 'Toyota', 'Troller', 'Volkswagen', 'Volvo', 'Avelloz', 'Bajaj', 'Dafra', 'Haojue', 'Harley-Davidson', 'Kawasaki', 'KTM', 'Mottu', 'Royal Enfield', 'Shineray', 'Triumph', 'Yamaha', 'DAF', 'Iveco', 'MAN', 'Scania', 'Volvo Caminhões'];
    sort($marcas);
    $cores = ['Amarelo', 'Azul', 'Bege', 'Branco', 'Cinza', 'Dourado', 'Grená', 'Laranja', 'Marrom', 'Prata', 'Preto', 'Rosa', 'Roxo', 'Verde', 'Vermelho', 'Fantasia'];
    sort($cores);

    $tipos = [
        '6' => 'Automóvel', '13' => 'Camioneta', '14' => 'Caminhão', '17' => 'Caminhão Trator',
        '2' => 'Ciclomotor', '7' => 'Micro-ônibus', '4' => 'Motocicleta', '3' => 'Motoneta',
        '8' => 'Ônibus', '21' => 'Quadriciclo', '10' => 'Reboque', '11' => 'Semirreboque',
        '5' => 'Triciclo', '25' => 'Utilitário', '22' => 'Chassi Plataforma',
    ];

    $especies = [
        '1' => 'Passageiro', '2' => 'Carga', '3' => 'Misto',
        '4' => 'Competição', '5' => 'Tração', '6' => 'Especial', '7' => 'Coleção'
    ];

    $carrocerias = [
        '102' => 'Basculante', '107' => 'Carroceria Aberta', '108' => 'Carroceria Fechada',
        '109' => 'Chassi Porta Contêiner', '112' => 'Furgão', '118' => 'Prancha',
        '120' => 'Silo', '121' => 'Tanque', '122' => 'Trailler', '133' => 'Roll-on Roll-off',
        '143' => 'Transporte de Toras', '999' => 'Não Aplicável / Nenhuma'
    ];
@endphp

<div class="space-y-8">
    {{-- Seção 1: Identificação e Classificação --}}
    <div class="p-6 bg-white border-b border-gray-200 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6">1. Identificação e Classificação</h3>
        <p class="text-sm text-gray-600 mb-6">Dados principais para identificação única do veículo.</p>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            {{-- Linha 1 --}}
            <div class="md:col-span-2">
                <label for="vei_placa" class="block font-medium text-sm text-gray-700">Placa *</label>
                <input type="text" name="vei_placa" id="vei_placa" class="mt-1 block w-full uppercase" value="{{ old('vei_placa', optional($veiculo)->vei_placa) }}" required maxlength="8">
            </div>
            <div class="md:col-span-3">
                <label for="vei_chassi" class="block font-medium text-sm text-gray-700">Chassi (VIN)</label>
                <input type="text" name="vei_chassi" id="vei_chassi" class="mt-1 block w-full uppercase" value="{{ old('vei_chassi', optional($veiculo)->vei_chassi) }}" maxlength="17">
            </div>
            <div class="md:col-span-3">
                <label for="vei_renavam" class="block font-medium text-sm text-gray-700">Renavam</label>
                <input type="text" name="vei_renavam" id="vei_renavam" class="mt-1 block w-full" value="{{ old('vei_renavam', optional($veiculo)->vei_renavam) }}" maxlength="11">
            </div>
            <div class="md:col-span-4">
                <label for="vei_fabricante" class="block font-medium text-sm text-gray-700">Fabricante *</label>
                <input list="marcas-list" name="vei_fabricante" id="vei_fabricante" class="mt-1 block w-full" value="{{ old('vei_fabricante', optional($veiculo)->vei_fabricante) }}" required>
                <datalist id="marcas-list">
                    @foreach($marcas as $marcaOption)
                        <option value="{{ $marcaOption }}">
                    @endforeach
                </datalist>
            </div>

            {{-- Linha 2 --}}
            
            <div class="md:col-span-5">
                <label for="vei_modelo" class="block font-medium text-sm text-gray-700">Modelo *</label>
                <input type="text" name="vei_modelo" id="vei_modelo" class="mt-1 block w-full" value="{{ old('vei_modelo', optional($veiculo)->vei_modelo) }}" required>
            </div>
            <div class="md:col-span-3">
                 <label for="vei_cor_predominante" class="block font-medium text-sm text-gray-700">Cor Predominante *</label>
                <select name="vei_cor_predominante" id="vei_cor_predominante" class="mt-1 block w-full" required>
                    <option value="">Selecione...</option>
                    @foreach($cores as $corOption)
                        <option value="{{ $corOption }}" @selected(old('vei_cor_predominante', optional($veiculo)->vei_cor_predominante) == $corOption)>{{ $corOption }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Linha 3 --}}
            <div class="md:col-span-2">
                <label for="vei_ano_fab" class="block font-medium text-sm text-gray-700">Ano Fabricação *</label>
                <input type="number" name="vei_ano_fab" id="vei_ano_fab" class="mt-1 block w-full" value="{{ old('vei_ano_fab', optional($veiculo)->vei_ano_fab) }}" required min="1940" max="{{ date('Y') + 1 }}">
            </div>
            <div class="md:col-span-2">
                <label for="vei_ano_mod" class="block font-medium text-sm text-gray-700">Ano Modelo *</label>
                <input type="number" name="vei_ano_mod" id="vei_ano_mod" class="mt-1 block w-full" value="{{ old('vei_ano_mod', optional($veiculo)->vei_ano_mod) }}" required min="1940" max="{{ date('Y') + 1 }}">
            </div>
            
            {{-- Linha 4 - Classificação CONTRAN --}}
            <div class="md:col-span-4">
                <label for="vei_tipo" class="block font-medium text-sm text-gray-700">Tipo (CONTRAN) *</label>
                <select name="vei_tipo" id="vei_tipo" class="mt-1 block w-full" required>
                    <option value="">Selecione...</option>
                    @foreach($tipos as $key => $value)
                        <option value="{{ $key }}" @selected(old('vei_tipo', optional($veiculo)->vei_tipo) == $key)>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4">
                <label for="vei_especie" class="block font-medium text-sm text-gray-700">Espécie (CONTRAN) *</label>
                <select name="vei_especie" id="vei_especie" class="mt-1 block w-full" required>
                    <option value="">Selecione...</option>
                    @foreach($especies as $key => $value)
                         <option value="{{ $key }}" @selected(old('vei_especie', optional($veiculo)->vei_especie) == $key)>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4">
                <label for="vei_carroceria" class="block font-medium text-sm text-gray-700">Carroceria (CONTRAN) *</label>
                <select name="vei_carroceria" id="vei_carroceria" class="mt-1 block w-full" required>
                    <option value="">Selecione...</option>
                    @foreach($carrocerias as $key => $value)
                         <option value="{{ $key }}" @selected(old('vei_carroceria', optional($veiculo)->vei_carroceria) == $key)>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Campo oculto para o segmento --}}
            <input type="hidden" name="vei_segmento" value="1"> {{-- Valor Padrão para Particular, ajuste conforme a lógica do seu sistema --}}

        </div>
    </div>

    {{-- Seção 2: Detalhes Operacionais e Motor --}}
    <div class="p-6 bg-white border-b border-gray-200 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6">2. Detalhes Operacionais e Motor</h3>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            {{-- Linha 1 --}}
            <div class="md:col-span-3">
                <label for="vei_km_inicial" class="block font-medium text-sm text-gray-700">KM Inicial *</label>
                <input type="number" name="vei_km_inicial" id="vei_km_inicial" class="mt-1 block w-full" value="{{ old('vei_km_inicial', optional($veiculo)->vei_km_inicial ?? '0') }}" required max="9999999">
            </div>
            <div class="md:col-span-3">
                <label for="vei_km_atual" class="block font-medium text-sm text-gray-700">KM Atual *</label>
                <input type="number" name="vei_km_atual" id="vei_km_atual" class="mt-1 block w-full" value="{{ old('vei_km_atual', optional($veiculo)->vei_km_atual ?? '0') }}" required max="9999999">
            </div>
            <div class="md:col-span-3">
                <label for="vei_combustivel" class="block font-medium text-sm text-gray-700">Combustível *</label>
                <select name="vei_combustivel" id="vei_combustivel" class="mt-1 block w-full" required>
                    <option value="1" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 1)>Gasolina</option>
                    <option value="2" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 2)>Álcool/Etanol</option>
                    <option value="3" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 3)>Diesel</option>
                    <option value="6" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 6)>Flex (Gasolina/Álcool)</option>
                    <option value="4" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 4)>GNV</option>
                    <option value="5" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 5)>Elétrico</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <label for="vei_potencia" class="block font-medium text-sm text-gray-700">Potência (CV)</label>
                <input type="text" name="vei_potencia" id="vei_potencia" class="mt-1 block w-full" value="{{ old('vei_potencia', optional($veiculo)->vei_potencia) }}">
            </div>
            <div class="md:col-span-4">
                <label for="vei_num_motor" class="block font-medium text-sm text-gray-700">Número do Motor</label>
                <input type="text" name="vei_num_motor" id="vei_num_motor" class="mt-1 block w-full" value="{{ old('vei_num_motor', optional($veiculo)->vei_num_motor) }}">
            </div>

            {{-- Linha 2 --}}
            <div class="md:col-span-4">
                <label for="vei_cap_tanque" class="block font-medium text-sm text-gray-700">Capacidade Tanque/Bateria</label>
                <input type="text" name="vei_cap_tanque" id="vei_cap_tanque" class="mt-1 block w-full" placeholder="Ex: 50L ou 75kWh" value="{{ old('vei_cap_tanque', optional($veiculo)->vei_cap_tanque) }}">
            </div>
             <div class="md:col-span-4">
                <label for="vei_cilindradas" class="block font-medium text-sm text-gray-700">Cilindradas (CC)</label>
                <input type="text" name="vei_cilindradas" id="vei_cilindradas" class="mt-1 block w-full" value="{{ old('vei_cilindradas', optional($veiculo)->vei_cilindradas) }}">
            </div>
        </div>
    </div>

    {{-- Seção 3: Documentação e Controle (Frota) --}}
     <div class="p-6 bg-white border-b border-gray-200 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6">3. Documentação e Controle (Frota)</h3>
         <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="md:col-span-4">
                <label for="vei_crv" class="block font-medium text-sm text-gray-700">Nº CRV</label>
                <input type="text" name="vei_crv" id="vei_crv" class="mt-1 block w-full" value="{{ old('vei_crv', optional($veiculo)->vei_crv) }}">
            </div>
            <div class="md:col-span-4">
                <label for="vei_data_licenciamento" class="block font-medium text-sm text-gray-700">Data Último Licenciamento</label>
                <input type="date" name="vei_data_licenciamento" id="vei_data_licenciamento" class="mt-1 block w-full" value="{{ old('vei_data_licenciamento', optional(optional($veiculo)->vei_data_licenciamento)->format('Y-m-d')) }}">
            </div>
            <div class="md:col-span-4">
                <label for="vei_antt" class="block font-medium text-sm text-gray-700">Cód. ANTT</label>
                <input type="text" name="vei_antt" id="vei_antt" class="mt-1 block w-full" value="{{ old('vei_antt', optional($veiculo)->vei_antt) }}">
            </div>
            <div class="md:col-span-4">
                <label for="vei_tara" class="block font-medium text-sm text-gray-700">Tara (kg)</label>
                <input type="number" name="vei_tara" id="vei_tara" class="mt-1 block w-full" value="{{ old('vei_tara', optional($veiculo)->vei_tara) }}">
            </div>
            <div class="md:col-span-4">
                <label for="vei_lotacao" class="block font-medium text-sm text-gray-700">Lotação (kg)</label>
                <input type="number" name="vei_lotacao" id="vei_lotacao" class="mt-1 block w-full" value="{{ old('vei_lotacao', optional($veiculo)->vei_lotacao) }}">
            </div>
            <div class="md:col-span-4">
                <label for="vei_pbt" class="block font-medium text-sm text-gray-700">PBT (kg)</label>
                <input type="number" name="vei_pbt" id="vei_pbt" class="mt-1 block w-full" value="{{ old('vei_pbt', optional($veiculo)->vei_pbt) }}">
            </div>
         </div>
    </div>


    {{-- Seção 4: Status e Ciclo de Vida --}}
     <div class="p-6 bg-white border-b border-gray-200 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6">4. Status e Ciclo de Vida</h3>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="md:col-span-3">
                <label for="vei_data_aquisicao" class="block font-medium text-sm text-gray-700">Data de Aquisição *</label>
                <input type="date" name="vei_data_aquisicao" id="vei_data_aquisicao" class="mt-1 block w-full" value="{{ old('vei_data_aquisicao', optional(optional($veiculo)->vei_data_aquisicao)->format('Y-m-d')) }}" required>
            </div>
             <div class="md:col-span-3">
                <label for="vei_valor_aquisicao" class="block font-medium text-sm text-gray-700">Valor de Aquisição</label>
                <input type="number" step="0.01" name="vei_valor_aquisicao" id="vei_valor_aquisicao" class="mt-1 block w-full" value="{{ old('vei_valor_aquisicao', optional($veiculo)->vei_valor_aquisicao) }}">
            </div>
            <div class="md:col-span-3">
                <label for="vei_data_venda" class="block font-medium text-sm text-gray-700">Data da Venda</label>
                <input type="date" name="vei_data_venda" id="vei_data_venda" class="mt-1 block w-full" value="{{ old('vei_data_venda', optional(optional($veiculo)->vei_data_venda)->format('Y-m-d')) }}">
            </div>
            <div class="md:col-span-3">
                <label for="vei_valor_venda" class="block font-medium text-sm text-gray-700">Valor de Venda</label>
                <input type="number" step="0.01" name="vei_valor_venda" id="vei_valor_venda" class="mt-1 block w-full" value="{{ old('vei_valor_venda', optional($veiculo)->vei_valor_venda) }}">
            </div>
            <div class="md:col-span-12">
                <label for="vei_status" class="block font-medium text-sm text-gray-700">Status *</label>
                <select name="vei_status" id="vei_status" class="mt-1 block w-full" required>
                    <option value="1" @selected(old('vei_status', optional($veiculo)->vei_status ?? 1) == 1)>Ativo</option>
                    <option value="2" @selected(old('vei_status', optional($veiculo)->vei_status) == 2)>Inativo</option>
                    <option value="3" @selected(old('vei_status', optional($veiculo)->vei_status) == 3)>Em Manutenção</option>
                    <option value="4" @selected(old('vei_status', optional($veiculo)->vei_status) == 4)>Vendido</option>
                </select>
            </div>
            <div class="md:col-span-12">
                <label for="vei_obs" class="block font-medium text-sm text-gray-700">Observações</label>
                <textarea name="vei_obs" id="vei_obs" rows="3" class="mt-1 block w-full">{{ old('vei_obs', optional($veiculo)->vei_obs) }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-8 mb-4 mr-4">
    <a href="{{ route('veiculos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
        Cancelar
    </a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
        Salvar Veículo
    </button>
</div>

