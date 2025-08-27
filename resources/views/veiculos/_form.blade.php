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

@php
    $marcas = ['Agrale', 'Aston Martin', 'Audi', 'BMW', 'BYD', 'CAOA Chery', 'Chevrolet', 'Citroën', 'Dodge', 'Ferrari', 'Fiat', 'Ford', 'GWM', 'Honda', 'Hyundai', 'Jac', 'Jaguar', 'Jeep', 'Kia', 'Lamborghini', 'Land Rover', 'Lexus', 'Maserati', 'McLaren', 'Mercedes-Benz', 'Mini', 'Mitsubishi', 'Nissan', 'Peugeot', 'Porsche', 'Ram', 'Renault', 'Rolls-Royce', 'Subaru', 'Suzuki', 'Toyota', 'Troller', 'Volkswagen', 'Volvo', 'Avelloz', 'Bajaj', 'Dafra', 'Haojue', 'Harley-Davidson', 'Kawasaki', 'KTM', 'Mottu', 'Royal Enfield', 'Shineray', 'Triumph', 'Yamaha', 'DAF', 'Iveco', 'MAN', 'Scania', 'Volvo Caminhões'];
    $marcas = array_unique($marcas);
    sort($marcas);
    $cores = ['AMARELO', 'AZUL', 'BEGE', 'BRANCA', 'CINZA', 'DOURADA', 'GRENÁ', 'LARANJA', 'MARROM', 'PRATA', 'PRETA', 'ROSA', 'ROXA', 'VERDE', 'VERMELHA', 'FANTASIA'];
@endphp

<div class="space-y-8">
    {{-- Seção 1: Identificação do Veículo --}}
    <div class="form-section">
        <h3 class="form-section-title">1. Identificação do Veículo</h3>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="md:col-span-3">
                <label for="placa" class="block font-medium text-sm text-gray-700">Placa*</label>
                <input type="text" name="placa" id="placa" class="mt-1 block w-full uppercase" value="{{ old('placa', $veiculo->placa ?? '') }}" required maxlength="8">
            </div>
            <div class="md:col-span-3">
                <label for="marca" class="block font-medium text-sm text-gray-700">Marca*</label>
                <input list="marcas-list" name="marca" id="marca" class="mt-1 block w-full" value="{{ old('marca', $veiculo->marca ?? '') }}" required>
                <datalist id="marcas-list">
                    @foreach($marcas as $marcaOption)
                        <option value="{{ $marcaOption }}">
                    @endforeach
                </datalist>
            </div>
            <div class="md:col-span-3">
                <label for="modelo" class="block font-medium text-sm text-gray-700">Modelo*</label>
                <input type="text" name="modelo" id="modelo" class="mt-1 block w-full" value="{{ old('modelo', $veiculo->modelo ?? '') }}" required>
            </div>
             <div class="md:col-span-3">
                <label for="cor" class="block font-medium text-sm text-gray-700">Cor*</label>
                <select name="cor" id="cor" class="mt-1 block w-full" required>
                    <option value="">Selecione a cor</option>
                    @foreach($cores as $corOption)
                        @php $corDisplay = mb_convert_case($corOption, MB_CASE_TITLE, 'UTF-8'); @endphp
                        <option value="{{ $corDisplay }}" @selected(old('cor', $veiculo->cor ?? '') == $corDisplay)>{{ $corDisplay }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label for="ano_fabricacao" class="block font-medium text-sm text-gray-700">Ano Fabricação*</label>
                <input type="number" name="ano_fabricacao" id="ano_fabricacao" class="mt-1 block w-full" value="{{ old('ano_fabricacao', $veiculo->ano_fabricacao ?? '') }}" required min="1940" max="{{ date('Y') + 1 }}">
            </div>
            <div class="md:col-span-2">
                <label for="ano_modelo" class="block font-medium text-sm text-gray-700">Ano Modelo*</label>
                <input type="number" name="ano_modelo" id="ano_modelo" class="mt-1 block w-full" value="{{ old('ano_modelo', $veiculo->ano_modelo ?? '') }}" required min="1940" max="{{ date('Y') + 1 }}">
            </div>
            <div class="md:col-span-4">
                <label for="tipo_veiculo" class="block font-medium text-sm text-gray-700">Tipo de Veículo*</label>
                <select name="tipo_veiculo" id="tipo_veiculo" class="mt-1 block w-full" required>
                    <option value="carro" @selected(old('tipo_veiculo', $veiculo->tipo_veiculo ?? '') == 'carro')>Carro</option>
                    <option value="moto" @selected(old('tipo_veiculo', $veiculo->tipo_veiculo ?? '') == 'moto')>Moto</option>
                    <option value="caminhao" @selected(old('tipo_veiculo', $veiculo->tipo_veiculo ?? '') == 'caminhao')>Caminhão</option>
                    <option value="van" @selected(old('tipo_veiculo', $veiculo->tipo_veiculo ?? '') == 'van')>Van</option>
                    <option value="outro" @selected(old('tipo_veiculo', $veiculo->tipo_veiculo ?? '') == 'outro')>Outro</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Seção 2: Documentação --}}
    <div class="form-section">
        <h3 class="form-section-title">2. Documentação</h3>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="md:col-span-4">
                <label for="chassi" class="block font-medium text-sm text-gray-700">Chassi</label>
                <input type="text" name="chassi" id="chassi" class="mt-1 block w-full uppercase" value="{{ old('chassi', $veiculo->chassi ?? '') }}" maxlength="17">
            </div>
            <div class="md:col-span-4">
                <label for="renavam" class="block font-medium text-sm text-gray-700">Renavam</label>
                <input type="text" name="renavam" id="renavam" class="mt-1 block w-full" value="{{ old('renavam', $veiculo->renavam ?? '') }}" maxlength="11">
            </div>
             <div class="md:col-span-4">
                <label for="vencimento_licenciamento" class="block font-medium text-sm text-gray-700">Vencimento do Licenciamento</label>
                <input type="date" name="vencimento_licenciamento" id="vencimento_licenciamento" class="mt-1 block w-full" value="{{ old('vencimento_licenciamento', isset($veiculo->vencimento_licenciamento) ? \Carbon\Carbon::parse($veiculo->vencimento_licenciamento)->format('Y-m-d') : '') }}">
            </div>
        </div>
    </div>

    {{-- Seção 3: Detalhes Operacionais --}}
    <div class="form-section">
        <h3 class="form-section-title">3. Detalhes Operacionais</h3>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="md:col-span-3">
                <label for="quilometragem_inicial" class="block font-medium text-sm text-gray-700">KM Inicial*</label>
                <input type="number" name="quilometragem_inicial" id="quilometragem_inicial" class="mt-1 block w-full" value="{{ old('quilometragem_inicial', $veiculo->quilometragem_inicial ?? '0') }}" required max="9999999">
            </div>
            <div class="md:col-span-3">
                <label for="quilometragem_atual" class="block font-medium text-sm text-gray-700">KM Atual*</label>
                <input type="number" name="quilometragem_atual" id="quilometragem_atual" class="mt-1 block w-full" value="{{ old('quilometragem_atual', $veiculo->quilometragem_atual ?? '0') }}" required max="9999999">
            </div>
            <div class="md:col-span-3">
                <label for="tipo_combustivel" class="block font-medium text-sm text-gray-700">Tipo de Combustível*</label>
                <select name="tipo_combustivel" id="tipo_combustivel" class="mt-1 block w-full" required>
                    <option value="gasolina" @selected(old('tipo_combustivel', $veiculo->tipo_combustivel ?? '') == 'gasolina')>Gasolina</option>
                    <option value="etanol" @selected(old('tipo_combustivel', $veiculo->tipo_combustivel ?? '') == 'etanol')>Etanol</option>
                    <option value="diesel" @selected(old('tipo_combustivel', $veiculo->tipo_combustivel ?? '') == 'diesel')>Diesel</option>
                    <option value="flex" @selected(old('tipo_combustivel', $veiculo->tipo_combustivel ?? '') == 'flex')>Flex</option>
                    <option value="gnv" @selected(old('tipo_combustivel', $veiculo->tipo_combustivel ?? '') == 'gnv')>GNV</option>
                    <option value="eletrico" @selected(old('tipo_combustivel', $veiculo->tipo_combustivel ?? '') == 'eletrico')>Elétrico</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <label for="capacidade_tanque" class="block font-medium text-sm text-gray-700">Capacidade Tanque/Bateria</label>
                <input type="text" name="capacidade_tanque" id="capacidade_tanque" class="mt-1 block w-full" placeholder="Litros ou kWh" value="{{ old('capacidade_tanque', $veiculo->capacidade_tanque ?? '') }}">
            </div>
        </div>
    </div>

    {{-- Seção 4: Seguro e Pneus --}}
    <div class="form-section">
        <h3 class="form-section-title">4. Seguro e Pneus</h3>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="md:col-span-4">
                <label for="seguradora" class="block font-medium text-sm text-gray-700">Seguradora</label>
                <input type="text" name="seguradora" id="seguradora" class="mt-1 block w-full" value="{{ old('seguradora', $veiculo->seguradora ?? '') }}">
            </div>
            <div class="md:col-span-4">
                <label for="apolice_seguro" class="block font-medium text-sm text-gray-700">Nº da Apólice</label>
                <input type="text" name="apolice_seguro" id="apolice_seguro" class="mt-1 block w-full" value="{{ old('apolice_seguro', $veiculo->apolice_seguro ?? '') }}">
            </div>
            <div class="md:col-span-4">
                <label for="vencimento_apolice" class="block font-medium text-sm text-gray-700">Vencimento da Apólice</label>
                <input type="date" name="vencimento_apolice" id="vencimento_apolice" class="mt-1 block w-full" value="{{ old('vencimento_apolice', isset($veiculo->vencimento_apolice) ? \Carbon\Carbon::parse($veiculo->vencimento_apolice)->format('Y-m-d') : '') }}">
            </div>
            <div class="md:col-span-4">
                <label for="km_troca_pneus" class="block font-medium text-sm text-gray-700">KM da Última Troca de Pneus</label>
                <input type="number" name="km_troca_pneus" id="km_troca_pneus" class="mt-1 block w-full" value="{{ old('km_troca_pneus', $veiculo->km_troca_pneus ?? '') }}">
            </div>
            <div class="md:col-span-4">
                <label for="data_troca_pneus" class="block font-medium text-sm text-gray-700">Data da Última Troca de Pneus</label>
                <input type="date" name="data_troca_pneus" id="data_troca_pneus" class="mt-1 block w-full" value="{{ old('data_troca_pneus', isset($veiculo->data_troca_pneus) ? \Carbon\Carbon::parse($veiculo->data_troca_pneus)->format('Y-m-d') : '') }}">
            </div>
        </div>
    </div>

    {{-- Seção 5: Status e Aquisição --}}
    <div class="form-section">
        <h3 class="form-section-title">5. Status e Aquisição</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="data_aquisicao" class="block font-medium text-sm text-gray-700">Data de Aquisição</label>
                <input type="date" name="data_aquisicao" id="data_aquisicao" class="mt-1 block w-full" value="{{ old('data_aquisicao', isset($veiculo->data_aquisicao) ? \Carbon\Carbon::parse($veiculo->data_aquisicao)->format('Y-m-d') : '') }}">
            </div>
            <div>
                <label for="status" class="block font-medium text-sm text-gray-700">Status*</label>
                <select name="status" id="status" class="mt-1 block w-full" required>
                    <option value="ativo" @selected(old('status', $veiculo->status ?? 'ativo') == 'ativo')>Ativo</option>
                    <option value="inativo" @selected(old('status', $veiculo->status ?? '') == 'inativo')>Inativo</option>
                    <option value="em_manutencao" @selected(old('status', $veiculo->status ?? '') == 'em_manutencao')>Em Manutenção</option>
                    <option value="vendido" @selected(old('status', $veiculo->status ?? '') == 'vendido')>Vendido</option>
                </select>
            </div>
             <div class="md:col-span-2">
                <label for="observacoes" class="block font-medium text-sm text-gray-700">Observações</label>
                <textarea name="observacoes" id="observacoes" rows="3" class="mt-1 block w-full">{{ old('observacoes', $veiculo->observacoes ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('veiculos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
        Cancelar
    </a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
        Salvar
    </button>
</div>
