{{-- 
  MODAIS DE AÇÃO
  - Inclui @method('PATCH') ou POST conforme rota
  - Observa $errors para reabrir automaticamente em caso de falha de validação
--}}

{{-- Modal: Aprovar Reserva --}}
{{-- Reabre se houver erro em: veiculo_id, motorista_id ou erro genérico do conflito (res_vei_id) --}}
<x-modal name="modal-aprovar-reserva" :show="$errors->has('veiculo_id') || $errors->has('motorista_id') || $errors->has('res_vei_id')" maxWidth="lg">
    <form method="post" action="{{ route('reservas.aprovar', $reserva) }}" class="p-6 bg-white rounded-lg">
        @csrf
        @method('PATCH')
        
        <h2 class="text-lg font-medium text-gray-900">Aprovar Reserva #{{ $reserva->res_codigo }}</h2>
        <p class="mt-2 text-sm text-gray-600">
            Confirme os recursos alocados. O veículo deve estar disponível no período.
        </p>
        
        {{-- Erro de Conflito (Retornado pelo Service) --}}
        <x-input-error :messages="$errors->get('res_vei_id')" class="mt-2 p-2 bg-red-50 rounded border border-red-200" />

        <div class="mt-4">
            <x-input-label for="aprovar_veiculo_id" value="Veículo *" />
            <select name="veiculo_id" id="aprovar_veiculo_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">Selecione...</option>
                @foreach($veiculos as $v)
                    <option value="{{ $v->vei_id }}" @selected(old('veiculo_id', $reserva->res_vei_id) == $v->vei_id)>
                        {{ $v->placaModelo }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('veiculo_id')" class="mt-1" />
        </div>

        <div class="mt-4">
            <x-input-label for="aprovar_motorista_id" value="Motorista *" />
            <select name="motorista_id" id="aprovar_motorista_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" @if($reserva->res_tipo !== 'manutencao') required @endif>
                <option value="">Selecione...</option>
                @foreach($motoristas as $m)
                    <option value="{{ $m->mot_id }}" @selected(old('motorista_id', $reserva->res_mot_id) == $m->mot_id)>
                        {{ $m->mot_nome }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('motorista_id')" class="mt-1" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancelar') }}</x-secondary-button>
            <x-primary-button class="ml-3 bg-green-600 hover:bg-green-700">{{ __('Confirmar Aprovação') }}</x-primary-button>
        </div>
    </form>
</x-modal>

{{-- Modal: Rejeitar Reserva --}}
<x-modal name="modal-rejeitar-reserva" :show="$errors->has('motivo_rejeicao')" maxWidth="lg">
    <form method="post" action="{{ route('reservas.rejeitar', $reserva) }}" class="p-6 bg-white rounded-lg">
        @csrf
        @method('PATCH')

        <h2 class="text-lg font-medium text-gray-900">Rejeitar Reserva #{{ $reserva->res_codigo }}</h2>
        <p class="mt-2 text-sm text-gray-600">Informe o motivo da rejeição para o solicitante.</p>
        
        <div class="mt-4">
            <x-input-label for="observacao_rejeitar" value="Motivo" />
            <textarea id="observacao_rejeitar" name="motivo_rejeicao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3" required>{{ old('motivo_rejeicao') }}</textarea>
            <x-input-error :messages="$errors->get('motivo_rejeicao')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancelar') }}</x-secondary-button>
            <x-danger-button class="ml-3">{{ __('Rejeitar') }}</x-danger-button>
        </div>
    </form>
</x-modal>

{{-- Modal: Cancelar Reserva --}}
<x-modal name="modal-cancelar-reserva" maxWidth="lg">
    <form method="post" action="{{ route('reservas.cancelar', $reserva) }}" class="p-6 bg-white rounded-lg">
        @csrf
        @method('PATCH') {{-- CORREÇÃO: Cancelar usa PATCH --}}
        <h2 class="text-lg font-medium text-gray-900">Cancelar Reserva #{{ $reserva->res_codigo }}</h2>
        <p class="mt-2 text-sm text-gray-600">Tem certeza? Esta ação é irreversível.</p>
        
        <div class="mt-4">
            <x-input-label for="motivo_cancelamento" value="Motivo (Opcional)" />
            <input type="text" name="motivo_cancelamento" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Voltar') }}</x-secondary-button>
            <x-danger-button class="ml-3 bg-yellow-500 hover:bg-yellow-600">{{ __('Sim, Cancelar') }}</x-danger-button>
        </div>
    </form>
</x-modal>

{{-- Modal: Iniciar Viagem --}}
<x-modal name="modal-iniciar-reserva" :show="$errors->has('res_km_inicio')" maxWidth="lg">
    <form method="post" action="{{ route('reservas.iniciar', $reserva) }}" class="p-6 bg-white rounded-lg">
        @csrf
        @method('PATCH')
        <h2 class="text-lg font-medium text-gray-900">Iniciar Viagem</h2>
        <p class="mt-2 text-sm text-gray-600">Confirme os dados de saída.</p>
        
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="res_km_inicio" value="KM Saída *" />
                <x-text-input id="res_km_inicio" name="res_km_inicio" type="number" class="mt-1 block w-full" 
                              :value="old('res_km_inicio', $reserva->veiculo->vei_km_atual ?? 0)" required />
                <x-input-error :messages="$errors->get('res_km_inicio')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="res_comb_inicio" value="Combustível *" />
                <select id="res_comb_inicio" name="res_comb_inicio" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" required>
                    <option value="cheio">Cheio</option>
                    <option value="3/4">3/4</option>
                    <option value="1/2">1/2</option>
                    <option value="1/4">1/4</option>
                    <option value="reserva">Reserva</option>
                </select>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancelar') }}</x-secondary-button>
            <x-primary-button class="ml-3 bg-blue-600 hover:bg-blue-700">{{ __('Confirmar Saída') }}</x-primary-button>
        </div>
    </form>
</x-modal>

{{-- Modal: Finalizar --}}
<x-modal name="modal-finalizar-reserva" :show="$errors->has('res_km_fim') || $errors->has('res_comb_fim')" maxWidth="lg">
    <form method="post" action="{{ route('reservas.finalizar', $reserva) }}" class="p-6 bg-white rounded-lg">
        @csrf
        @method('PATCH') {{-- Importante: Deve bater com a rota --}}
        
        <h2 class="text-lg font-medium text-gray-900">Finalizar Viagem</h2>
        <p class="mt-2 text-sm text-gray-600">Informe os dados de chegada para enviar à revisão.</p>
        
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="res_km_fim" value="KM Chegada *" />
                {{-- Name deve ser res_km_fim --}}
                <x-text-input id="res_km_fim" name="res_km_fim" type="number" class="mt-1 block w-full" 
                              :value="old('res_km_fim', $reserva->res_km_inicio)" required />
                <x-input-error :messages="$errors->get('res_km_fim')" class="mt-2" />
            </div>
             <div>
                <x-input-label for="res_comb_fim" value="Combustível *" />
                <select id="res_comb_fim" name="res_comb_fim" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" required>
                    <option value="">Selecione...</option>
                    <option value="cheio" @selected(old('res_comb_fim') == 'cheio')>Cheio</option>
                    <option value="3/4" @selected(old('res_comb_fim') == '3/4')>3/4</option>
                    <option value="1/2" @selected(old('res_comb_fim') == '1/2')>1/2</option>
                    <option value="1/4" @selected(old('res_comb_fim') == '1/4')>1/4</option>
                    <option value="reserva" @selected(old('res_comb_fim') == 'reserva')>Reserva</option>
                </select>
                <x-input-error :messages="$errors->get('res_comb_fim')" class="mt-2" />
            </div>
        </div>
        
        <div class="mt-4">
             <x-input-label for="res_hora_chegada" value="Data/Hora Chegada *" />
             <x-text-input id="res_hora_chegada" name="res_hora_chegada" type="datetime-local" class="mt-1 block w-full" 
                           :value="old('res_hora_chegada', now()->format('Y-m-d\TH:i'))" required />
             <x-input-error :messages="$errors->get('res_hora_chegada')" class="mt-2" />
        </div>

         <div class="mt-4">
            <x-input-label for="res_obs_finais" value="Obs. Finais" />
            <textarea name="res_obs_finais" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="2">{{ old('res_obs_finais') }}</textarea>
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancelar') }}</x-secondary-button>
            <x-primary-button class="ml-3 bg-purple-600 hover:bg-purple-700">{{ __('Finalizar') }}</x-primary-button>
        </div>
    </form>
</x-modal>

{{-- Modal: Revisar --}}
<x-modal name="modal-revisar-reserva" :show="$errors->has('res_obs_revisor')" maxWidth="lg">
    <form method="post" action="{{ route('reservas.revisar', $reserva) }}" class="p-6 bg-white rounded-lg" x-ref="formRevisao">
        @csrf
        {{-- POST pois envia 'acao' no body --}}
        
        <h2 class="text-lg font-medium text-gray-900">Revisão da Reserva</h2>
        <input type="hidden" name="acao" x-ref="acaoRevisao">

        <div class="mt-4">
            <x-input-label for="res_obs_revisor" value="Parecer do Revisor" />
            <textarea id="res_obs_revisor" name="res_obs_revisor" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="4">{{ old('res_obs_revisor', $reserva->res_obs_revisor) }}</textarea>
            <x-input-error :messages="$errors->get('res_obs_revisor')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-between">
            <x-secondary-button type="button" @click="$dispatch('close')">{{ __('Cancelar') }}</x-secondary-button>
            <div>
                 <x-danger-button type="submit" class="ml-2 bg-yellow-500 hover:bg-yellow-600"
                                  @click.prevent="$refs.acaoRevisao.value = 'devolver'; $refs.formRevisao.submit();">
                    {{ __('Devolver') }}
                </x-danger-button>
                <x-primary-button type="submit" class="ml-2"
                                @click.prevent="$refs.acaoRevisao.value = 'encerrar'; $refs.formRevisao.submit();">
                    {{ __('Encerrar') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-modal>