@php
    // Para facilitar o acesso, definimos as variáveis aqui se não existirem (caso de 'create')
    $reserva = $reserva ?? new \App\Models\Reserva();
@endphp

{{-- Bloco para exibir AVISOS (conflitos pendentes) FOI REMOVIDO DAQUI --}}

{{-- Bloco para exibir ERROS (conflitos bloqueados ou outros erros) --}}
@php
    // Filtra para não mostrar o aviso na lista de erros normais
    $regularErrors = $errors->getMessages();
    unset($regularErrors['warning_pendente']); // Remove o aviso, pois ele vai para o modal
@endphp

@if (!empty($regularErrors))
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
            <p class="font-bold">Atenção! Verifique os erros abaixo:</p>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($regularErrors as $fieldErrors)
                    @foreach ($fieldErrors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    </div>
@endif


{{-- O Alpine.js agora controla a visibilidade dos campos com base no tipo --}}
<div class="space-y-8" x-data="{ tipo: '{{ old('res_tipo', $reserva->res_tipo ?? 'viagem') }}' }">
    
    {{-- Seção 1: Dados da Reserva --}}
    <div class="form-section">
        <h3 class="form-section-title">Dados da Reserva</h3>
        
        <!-- Tipo de Reserva (AJUSTADO) -->
        <div>
            <x-input-label value="Tipo de Reserva *" />
            <div class="flex items-center space-x-6 mt-2">
                <label class="flex items-center">
                    <input type="radio" name="res_tipo" value="viagem" x-model="tipo"
                        class="border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-900">Viagem</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="res_tipo" value="manutencao" x-model="tipo"
                        class="border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-900">Manutenção</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('res_tipo')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- Veículo -->
            <div>
                <x-input-label for="res_vei_id" value="Veículo *" />
                <select name="res_vei_id" id="res_vei_id" class="mt-1 block w-full" required>
                    <option value="">Selecione um veículo</option>
                    @foreach ($veiculos as $veiculo)
                        <option value="{{ $veiculo->vei_id }}"
                            {{ old('res_vei_id', $reserva->res_vei_id) == $veiculo->vei_id ? 'selected' : '' }}>
                            {{ $veiculo->vei_placa }} - {{ $veiculo->vei_modelo }}
                        </option>
                    @endforeach
                </select>
                {{-- O erro de bloqueio (res_vei_id) será exibido aqui --}}
                <x-input-error :messages="$errors->get('res_vei_id')" class="mt-2" />
            </div>

            <!-- Motorista (Condicional) -->
            <div x-show="tipo === 'viagem'">
                <x-input-label for="res_mot_id" value="Motorista" />
                <select name="res_mot_id" id="res_mot_id" class="mt-1 block w-full">
                    <option value="">Selecione um motorista (opcional)</option>
                     @foreach ($motoristas as $motorista)
                        <option value="{{ $motorista->mot_id }}"
                            {{ old('res_mot_id', $reserva->res_mot_id) == $motorista->mot_id ? 'selected' : '' }}>
                            {{ $motorista->mot_nome }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('res_mot_id')" class="mt-2" />
            </div>

            <!-- Fornecedor (Condicional) -->
            <div x-show="tipo === 'manutencao'">
                <x-input-label for="res_for_id" value="Fornecedor (Oficina)" />
                <select name="res_for_id" id="res_for_id" class="mt-1 block w-full">
                    <option value="">Selecione um fornecedor (opcional)</option>
                     @foreach ($fornecedores as $fornecedor)
                        <option value="{{ $fornecedor->for_id }}"
                            {{ old('res_for_id', $reserva->res_for_id) == $fornecedor->for_id ? 'selected' : '' }}>
                            {{ $fornecedor->for_nome_fantasia }}
                        </option>
                    @endforeach
                </select>
                 <x-input-error :messages="$errors->get('res_for_id')" class="mt-2" />
            </div>
        </div>

        <!-- Período -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <x-input-label for="res_data_inicio" value="Início da Reserva *" />
                <x-text-input type="datetime-local" name="res_data_inicio" id="res_data_inicio" class="mt-1 block w-full" 
                              :value="old('res_data_inicio', $reserva->res_data_inicio ? \Carbon\Carbon::parse($reserva->res_data_inicio)->format('Y-m-d\TH:i') : '')" required />
                <x-input-error :messages="$errors->get('res_data_inicio')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="res_data_fim" value="Fim da Reserva *" />
                <x-text-input type="datetime-local" name="res_data_fim" id="res_data_fim" class="mt-1 block w-full" 
                              :value="old('res_data_fim', $reserva->res_data_fim ? \Carbon\Carbon::parse($reserva->res_data_fim)->format('Y-m-d\TH:i') : '')" required />
                <x-input-error :messages="$errors->get('res_data_fim')" class="mt-2" />
            </div>
        </div>

        <!-- Dia Todo Checkbox -->
        <div class="mt-6">
            <label for="res_dia_todo" class="flex items-center">
                <input type="checkbox" name="res_dia_todo" id="res_dia_todo" value="1"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    {{ old('res_dia_todo', $reserva->res_dia_todo) ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-900">Reservar para o dia todo</span>
                <x-input-error :messages="$errors->get('res_dia_todo')" class="mt-2" />
            </label>
        </div>
    </div>

    {{-- Seção 2: Rota (Condicional) --}}
    <div class="form-section" x-show="tipo === 'viagem'" x-transition>
        <h3 class="form-section-title">Rota da Viagem</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="res_origem" value="Origem" />
                <x-text-input type="text" name="res_origem" id="res_origem" class="mt-1 block w-full" :value="old('res_origem', $reserva->res_origem)" />
                <x-input-error :messages="$errors->get('res_origem')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="res_destino" value="Destino" />
                <x-text-input type="text" name="res_destino" id="res_destino" class="mt-1 block w-full" :value="old('res_destino', $reserva->res_destino)" />
                <x-input-error :messages="$errors->get('res_destino')" class="mt-2" />
            </div>
        </div>
    </div>

    {{-- Seção 3: Justificativa e Observações --}}
    <div class="form-section">
        <h3 class="form-section-title">Detalhes Adicionais</h3>
        <div class="grid grid-cols-1 gap-6">
            <div>
                <x-input-label for="res_just" value="Justificativa" />
                <textarea name="res_just" id="res_just" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('res_just', $reserva->res_just) }}</textarea>
                <x-input-error :messages="$errors->get('res_just')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="res_obs" value="Observações Gerais" />
                <textarea name="res_obs" id="res_obs" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('res_obs', $reserva->res_obs) }}</textarea>
                <x-input-error :messages="$errors->get('res_obs')" class="mt-2" />
            </div>
        </div>
    </div>
</div>

{{-- Botões de Ação --}}
<div class="flex items-center justify-end mt-8 mb-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
    {{-- Botão Cancelar (Link) --}}
    <a href="{{ route('reservas.index') }}"
       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
        {{ __('Cancelar') }}
    </a>
    
    {{-- Botão Salvar (Submit) --}}
    <x-primary-button class="ml-4">
        {{ $reserva->exists ? __('Atualizar Reserva') : __('Salvar Reserva') }}
    </x-primary-button>
</div>


{{-- 
  MODAL DE AVISO DE CONFLITO PENDENTE 
--}}
<x-modal name="warning-pendente" :show="$errors->has('warning_pendente')" maxWidth="lg">
    {{-- CORREÇÃO: Adicionado 'bg-white' aqui para forçar o fundo branco --}}
    <div class="p-6 bg-white">
        <div class="flex items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                    Aviso de Conflito de Agendamento
                </h3>
                <div class="mt-2">
                    @if($errors->has('warning_pendente'))
                        <p class="text-sm text-gray-600">
                            {{ $errors->first('warning_pendente') }}
                        </p>
                    @endif
                    <p class="text-sm text-gray-600 mt-2">
                        Deseja continuar e criar a reserva mesmo assim?
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <x-secondary-button @click="$dispatch('close')">
                {{ __('Voltar e Editar') }}
            </x-secondary-button>
            
            <x-primary-button class="bg-orange-500 hover:bg-orange-600 focus:bg-orange-600 active:bg-orange-700 focus:ring-orange-500" 
                              @click.prevent="
                                let form = document.getElementById('reservaForm');
                                let forceInput = document.createElement('input');
                                forceInput.type = 'hidden';
                                forceInput.name = 'force_create';
                                forceInput.value = '1';
                                form.appendChild(forceInput);
                                form.submit();
                            ">
                {{ __('Sim, Continuar') }}
            </x-primary-button>
        </div>
    </div>
</x-modal>