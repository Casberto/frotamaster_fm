{{-- 
    Lógica do Alpine.js para controle de estado do formulário.
    CORREÇÃO: Alterado 'veiculoDefinido' para 'veiculoIndefinido' para evitar negação no x-model.
--}}
<div x-data="{ 
    tipo: '{{ old('res_tipo', $reserva->res_tipo ?? 'viagem') }}',
    diaTodo: {{ old('res_dia_todo', $reserva->res_dia_todo ?? 0) ? 'true' : 'false' }},
    {{-- Se tiver ID, não é indefinido (false). Se não tiver ID, é indefinido (true) --}}
    veiculoIndefinido: {{ old('res_vei_id', $reserva->res_vei_id) ? 'false' : 'true' }}
}">

    {{-- Definição de Permissões via ID --}}
    @php
        // ID 42: Permite criar reservas do tipo Manutenção
        $podeCriarManutencao = auth()->user()->temPermissaoId(42);
        
        // ID 39: Permite Aprovar (Critério para definir quem pode selecionar motoristas livremente)
        $podeSelecionarMotorista = auth()->user()->temPermissaoId(39);
    @endphp

    {{-- TIPO DE RESERVA (Visível apenas se tiver permissão 42) --}}
    @if($podeCriarManutencao)
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Reserva</label>
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" name="res_tipo" value="viagem" x-model="tipo" class="peer sr-only">
                    <div class="rounded-lg border border-gray-300 bg-white p-4 text-center hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all">
                        <div class="block font-semibold">Viagem</div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="res_tipo" value="manutencao" x-model="tipo" class="peer sr-only">
                    <div class="rounded-lg border border-gray-300 bg-white p-4 text-center hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all">
                        <div class="block font-semibold">Manutenção</div>
                    </div>
                </label>
            </div>
        </div>
    @else
        {{-- Se não tiver permissão 42, força tipo 'viagem' --}}
        <input type="hidden" name="res_tipo" value="viagem">
    @endif

    {{-- SELEÇÃO DE VEÍCULO --}}
    <div class="mb-6">
        <label for="res_vei_id" class="block text-sm font-medium text-gray-700 mb-1">Veículo</label>
        
        {{-- Opção "A Definir" apenas para Viagem e se permitido --}}
        <div class="flex items-center mb-2" x-show="tipo === 'viagem'">
            {{-- CORREÇÃO: x-model aponta diretamente para a variável booleana --}}
            <input type="checkbox" id="veiculo_indefinido" x-model="veiculoIndefinido" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
            <label for="veiculo_indefinido" class="ml-2 text-sm text-gray-600">Veículo a definir (Aprovação pendente)</label>
        </div>

        {{-- 
            CORREÇÃO LÓGICA: 
            - Disabled se estiver marcado como indefinido E for viagem.
            - Required se NÃO for indefinido OU se for manutenção (manutenção sempre exige veículo).
        --}}
        <select name="res_vei_id" id="res_vei_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-3"
            :disabled="veiculoIndefinido && tipo === 'viagem'"
            :required="!veiculoIndefinido || tipo === 'manutencao'">
            <option value="">Selecione um veículo...</option>
            @foreach($veiculos as $veiculo)
                <option value="{{ $veiculo->vei_id }}" {{ old('res_vei_id', $reserva->res_vei_id) == $veiculo->vei_id ? 'selected' : '' }}>
                    {{ $veiculo->vei_placa }} - {{ $veiculo->vei_modelo }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('res_vei_id')" class="mt-1" />
    </div>

    {{-- DATAS E HORÁRIOS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label for="res_data_inicio" class="block text-sm font-medium text-gray-700 mb-1">Data Início</label>
            <input type="datetime-local" name="res_data_inicio" id="res_data_inicio" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-3" 
                   value="{{ old('res_data_inicio', $reserva->res_data_inicio ? \Carbon\Carbon::parse($reserva->res_data_inicio)->format('Y-m-d\TH:i') : '') }}" required>
            <x-input-error :messages="$errors->get('res_data_inicio')" class="mt-1" />
        </div>

        <div>
            <label for="res_data_fim" class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
            <input type="datetime-local" name="res_data_fim" id="res_data_fim" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-3" 
                   value="{{ old('res_data_fim', $reserva->res_data_fim ? \Carbon\Carbon::parse($reserva->res_data_fim)->format('Y-m-d\TH:i') : '') }}" required>
            <x-input-error :messages="$errors->get('res_data_fim')" class="mt-1" />
        </div>
    </div>

    <div class="mb-6">
        <div class="flex items-center">
            <input type="checkbox" name="res_dia_todo" id="res_dia_todo" value="1" x-model="diaTodo" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 h-5 w-5">
            <label for="res_dia_todo" class="ml-2 text-sm text-gray-700">Reserva para o dia todo (Ignora horários)</label>
        </div>
    </div>

    {{-- CAMPOS ESPECÍFICOS DE VIAGEM --}}
    <div x-show="tipo === 'viagem'" x-transition>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="res_origem" class="block text-sm font-medium text-gray-700 mb-1">Origem</label>
                <input type="text" name="res_origem" id="res_origem" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-3" placeholder="Ex: Sede da Empresa" value="{{ old('res_origem', $reserva->res_origem) }}">
            </div>
            <div>
                <label for="res_destino" class="block text-sm font-medium text-gray-700 mb-1">Destino</label>
                <input type="text" name="res_destino" id="res_destino" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-3" placeholder="Ex: Cliente X / Cidade Y" value="{{ old('res_destino', $reserva->res_destino) }}">
            </div>
        </div>

        {{-- Motorista (Exibido apenas se tiver permissão de aprovação ID 39) --}}
        @if($podeSelecionarMotorista)
        <div class="mb-6">
            <label for="res_mot_id" class="block text-sm font-medium text-gray-700 mb-1">Motorista</label>
            <select name="res_mot_id" id="res_mot_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-3">
                <option value="">Selecione o motorista...</option>
                @foreach($motoristas as $motorista)
                    <option value="{{ $motorista->mot_id }}" {{ old('res_mot_id', $reserva->res_mot_id) == $motorista->mot_id ? 'selected' : '' }}>
                        {{ $motorista->mot_nome }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    {{-- CAMPOS ESPECÍFICOS DE MANUTENÇÃO --}}
    <div x-show="tipo === 'manutencao'" x-transition x-cloak>
        <div class="mb-6">
            <label for="res_for_id" class="block text-sm font-medium text-gray-700 mb-1">Oficina / Fornecedor *</label>
            <select name="res_for_id" id="res_for_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-3" :required="tipo === 'manutencao'">
                <option value="">Selecione a oficina...</option>
                @foreach($fornecedores as $fornecedor)
                    <option value="{{ $fornecedor->for_id }}" {{ old('res_for_id', $reserva->res_for_id) == $fornecedor->for_id ? 'selected' : '' }}>
                        {{ $fornecedor->for_nome_fantasia }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('res_for_id')" class="mt-1" />
        </div>
    </div>

    <div class="mb-6">
        <label for="res_just" class="block text-sm font-medium text-gray-700 mb-1">Justificativa / Motivo *</label>
        <textarea name="res_just" id="res_just" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required placeholder="Descreva o motivo da viagem ou manutenção...">{{ old('res_just', $reserva->res_just) }}</textarea>
        <x-input-error :messages="$errors->get('res_just')" class="mt-1" />
    </div>

    <div class="mt-8 pt-5 border-t border-gray-200">
        <div class="flex justify-end">
            <a href="{{ route('reservas.index') }}" class="bg-white py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                Cancelar
            </a>
            <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto">
                {{ $reserva->exists ? 'Atualizar Reserva' : 'Solicitar Reserva' }}
            </button>
        </div>
    </div>
</div>