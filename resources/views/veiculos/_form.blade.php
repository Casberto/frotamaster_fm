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

<div class="space-y-6">
    
    {{-- TAB 1: DADOS GERAIS --}}
    <div x-show="tab === 'geral' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                Identificação Principal
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-6">
                {{-- Placa --}}
                <div class="md:col-span-3">
                    <label for="vei_placa" class="block font-medium text-sm text-gray-700">Placa *</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text" name="vei_placa" id="vei_placa" class="block w-full uppercase border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm pl-3" value="{{ old('vei_placa', optional($veiculo)->vei_placa) }}" required maxlength="8" placeholder="ABC1D23">
                    </div>
                </div>

                {{-- Fabricante --}}
                <div class="md:col-span-3">
                    <label for="vei_fabricante" class="block font-medium text-sm text-gray-700">Fabricante *</label>
                    <input list="marcas-list" name="vei_fabricante" id="vei_fabricante" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_fabricante', optional($veiculo)->vei_fabricante) }}" required placeholder="Selecione ou digite">
                    <datalist id="marcas-list">
                        @foreach($marcas as $marcaOption)
                            <option value="{{ $marcaOption }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Modelo --}}
                <div class="md:col-span-6">
                    <label for="vei_modelo" class="block font-medium text-sm text-gray-700">Modelo *</label>
                    <input type="text" name="vei_modelo" id="vei_modelo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_modelo', optional($veiculo)->vei_modelo) }}" required placeholder="Ex: Gol 1.6 MSI">
                </div>

                {{-- Ano Fab/Mod --}}
                <div class="md:col-span-3">
                    <label for="vei_ano_fab" class="block font-medium text-sm text-gray-700">Ano Fabricação *</label>
                    <input type="number" name="vei_ano_fab" id="vei_ano_fab" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_ano_fab', optional($veiculo)->vei_ano_fab) }}" required min="1940" max="{{ date('Y') + 1 }}">
                </div>
                <div class="md:col-span-3">
                    <label for="vei_ano_mod" class="block font-medium text-sm text-gray-700">Ano Modelo *</label>
                    <input type="number" name="vei_ano_mod" id="vei_ano_mod" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_ano_mod', optional($veiculo)->vei_ano_mod) }}" required min="1940" max="{{ date('Y') + 1 }}">
                </div>

                {{-- Cor --}}
                <div class="md:col-span-3">
                     <label for="vei_cor_predominante" class="block font-medium text-sm text-gray-700">Cor Predominante *</label>
                    <select name="vei_cor_predominante" id="vei_cor_predominante" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Selecione...</option>
                        @foreach($cores as $corOption)
                            <option value="{{ $corOption }}" @selected(old('vei_cor_predominante', optional($veiculo)->vei_cor_predominante) == $corOption)>{{ $corOption }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div class="md:col-span-3">
                    <label for="vei_status" class="block font-medium text-sm text-gray-700">Status *</label>
                    <select name="vei_status" id="vei_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="1" @selected(old('vei_status', optional($veiculo)->vei_status ?? 1) == 1)>Ativo</option>
                        <option value="2" @selected(old('vei_status', optional($veiculo)->vei_status) == 2)>Inativo</option>
                        <option value="3" @selected(old('vei_status', optional($veiculo)->vei_status) == 3)>Em Manutenção</option>
                        <option value="4" @selected(old('vei_status', optional($veiculo)->vei_status) == 4)>Vendido</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Classificação CONTRAN
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-4">
                    <label for="vei_tipo" class="block font-medium text-sm text-gray-700">Tipo *</label>
                    <select name="vei_tipo" id="vei_tipo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Selecione...</option>
                        @foreach($tipos as $key => $value)
                            <option value="{{ $key }}" @selected(old('vei_tipo', optional($veiculo)->vei_tipo) == $key)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label for="vei_especie" class="block font-medium text-sm text-gray-700">Espécie *</label>
                    <select name="vei_especie" id="vei_especie" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Selecione...</option>
                        @foreach($especies as $key => $value)
                             <option value="{{ $key }}" @selected(old('vei_especie', optional($veiculo)->vei_especie) == $key)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label for="vei_carroceria" class="block font-medium text-sm text-gray-700">Carroceria *</label>
                    <select name="vei_carroceria" id="vei_carroceria" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Selecione...</option>
                        @foreach($carrocerias as $key => $value)
                             <option value="{{ $key }}" @selected(old('vei_carroceria', optional($veiculo)->vei_carroceria) == $key)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 2: DETALHES TÉCNICOS --}}
    <div x-show="tab === 'tecnico' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force" style="display: none;">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                Motorização e Combustível
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-6">
                <div class="md:col-span-4">
                    <label for="vei_combustivel" class="block font-medium text-sm text-gray-700">Combustível *</label>
                    <select name="vei_combustivel" id="vei_combustivel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="1" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 1)>Gasolina</option>
                        <option value="2" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 2)>Álcool/Etanol</option>
                        <option value="3" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 3)>Diesel</option>
                        <option value="6" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 6)>Flex (Gasolina/Álcool)</option>
                        <option value="4" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 4)>GNV</option>
                        <option value="5" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 5)>Elétrico</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label for="vei_potencia" class="block font-medium text-sm text-gray-700">Potência (CV)</label>
                    <input type="text" name="vei_potencia" id="vei_potencia" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_potencia', optional($veiculo)->vei_potencia) }}">
                </div>
                <div class="md:col-span-4">
                    <label for="vei_cilindradas" class="block font-medium text-sm text-gray-700">Cilindradas (CC)</label>
                    <input type="text" name="vei_cilindradas" id="vei_cilindradas" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_cilindradas', optional($veiculo)->vei_cilindradas) }}">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_num_motor" class="block font-medium text-sm text-gray-700">Número do Motor</label>
                    <input type="text" name="vei_num_motor" id="vei_num_motor" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_num_motor', optional($veiculo)->vei_num_motor) }}">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_cap_tanque" class="block font-medium text-sm text-gray-700">Capacidade Tanque/Bateria</label>
                    <input type="text" name="vei_cap_tanque" id="vei_cap_tanque" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Ex: 50L ou 75kWh" value="{{ old('vei_cap_tanque', optional($veiculo)->vei_cap_tanque) }}">
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Quilometragem
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <label for="vei_km_inicial" class="block font-medium text-sm text-gray-700">KM Inicial *</label>
                    <input type="number" name="vei_km_inicial" id="vei_km_inicial" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_km_inicial', optional($veiculo)->vei_km_inicial ?? '0') }}" required max="9999999">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_km_atual" class="block font-medium text-sm text-gray-700">KM Atual *</label>
                    <input type="number" name="vei_km_atual" id="vei_km_atual" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_km_atual', optional($veiculo)->vei_km_atual ?? '0') }}" required max="9999999">
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 3: DOCUMENTAÇÃO E PESOS --}}
    <div x-show="tab === 'docs' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force" style="display: none;">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Documentação
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-6">
                <div class="md:col-span-4">
                    <label for="vei_renavam" class="block font-medium text-sm text-gray-700">Renavam</label>
                    <input type="text" name="vei_renavam" id="vei_renavam" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_renavam', optional($veiculo)->vei_renavam) }}" maxlength="11">
                </div>
                <div class="md:col-span-4">
                    <label for="vei_chassi" class="block font-medium text-sm text-gray-700">Chassi (VIN)</label>
                    <input type="text" name="vei_chassi" id="vei_chassi" class="mt-1 block w-full uppercase border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_chassi', optional($veiculo)->vei_chassi) }}" maxlength="17">
                </div>
                <div class="md:col-span-4">
                    <label for="vei_crv" class="block font-medium text-sm text-gray-700">Nº CRV</label>
                    <input type="text" name="vei_crv" id="vei_crv" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_crv', optional($veiculo)->vei_crv) }}">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_data_licenciamento" class="block font-medium text-sm text-gray-700">Data Último Licenciamento</label>
                    <input type="date" name="vei_data_licenciamento" id="vei_data_licenciamento" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_data_licenciamento', optional(optional($veiculo)->vei_data_licenciamento)->format('Y-m-d')) }}">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_antt" class="block font-medium text-sm text-gray-700">Cód. ANTT</label>
                    <input type="text" name="vei_antt" id="vei_antt" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_antt', optional($veiculo)->vei_antt) }}">
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                Pesos e Capacidades
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-4">
                    <label for="vei_tara" class="block font-medium text-sm text-gray-700">Tara (kg)</label>
                    <input type="number" name="vei_tara" id="vei_tara" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_tara', optional($veiculo)->vei_tara) }}">
                </div>
                <div class="md:col-span-4">
                    <label for="vei_lotacao" class="block font-medium text-sm text-gray-700">Lotação (kg)</label>
                    <input type="number" name="vei_lotacao" id="vei_lotacao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_lotacao', optional($veiculo)->vei_lotacao) }}">
                </div>
                <div class="md:col-span-4">
                    <label for="vei_pbt" class="block font-medium text-sm text-gray-700">PBT (kg)</label>
                    <input type="number" name="vei_pbt" id="vei_pbt" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_pbt', optional($veiculo)->vei_pbt) }}">
                </div>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Histórico de Aquisição
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-6">
                <div class="md:col-span-6">
                    <label for="vei_data_aquisicao" class="block font-medium text-sm text-gray-700">Data de Aquisição *</label>
                    <input type="date" name="vei_data_aquisicao" id="vei_data_aquisicao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_data_aquisicao', optional(optional($veiculo)->vei_data_aquisicao)->format('Y-m-d')) }}" required>
                </div>
                 <div class="md:col-span-6">
                    <label for="vei_valor_aquisicao" class="block font-medium text-sm text-gray-700">Valor de Aquisição</label>
                    <input type="number" step="0.01" name="vei_valor_aquisicao" id="vei_valor_aquisicao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_valor_aquisicao', optional($veiculo)->vei_valor_aquisicao) }}">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_data_venda" class="block font-medium text-sm text-gray-700">Data da Venda</label>
                    <input type="date" name="vei_data_venda" id="vei_data_venda" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_data_venda', optional(optional($veiculo)->vei_data_venda)->format('Y-m-d')) }}">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_valor_venda" class="block font-medium text-sm text-gray-700">Valor de Venda</label>
                    <input type="number" step="0.01" name="vei_valor_venda" id="vei_valor_venda" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_valor_venda', optional($veiculo)->vei_valor_venda) }}">
                </div>
                <div class="md:col-span-12">
                    <label for="vei_obs" class="block font-medium text-sm text-gray-700">Observações</label>
                    <textarea name="vei_obs" id="vei_obs" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('vei_obs', optional($veiculo)->vei_obs) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Campo oculto para o segmento --}}
    <input type="hidden" name="vei_segmento" value="1">
</div>
