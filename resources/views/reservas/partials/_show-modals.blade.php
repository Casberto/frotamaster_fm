{{-- 
  Este arquivo contém TODOS os modais de ação para a tela de 'show'
  CORREÇÃO: Adicionado 'bg-white' em todos os formulários/divs internas
  para garantir o tema claro (Ponto 2).
--}}

{{-- Modal: Aprovar Reserva --}}
<x-modal name="modal-aprovar-reserva" :show="$errors->has('observacao')" maxWidth="lg">
    <form method="post" action="{{ route('reservas.aprovar', $reserva) }}" class="p-6 bg-white rounded-lg">
        @csrf
        <h2 class="text-lg font-medium text-gray-900">
            Aprovar Reserva
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Tem certeza que deseja aprovar esta reserva? Esta ação não pode ser desfeita.
        </p>
        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                {{ __('Cancelar') }}
            </x-secondary-button>
            <x-primary-button class="ml-3 bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:ring-green-500">
                {{ __('Sim, Aprovar') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>

{{-- Modal: Rejeitar Reserva --}}
<x-modal name="modal-rejeitar-reserva" :show="$errors->has('observacao')" maxWidth="lg">
    <form method="post" action="{{ route('reservas.rejeitar', $reserva) }}" class="p-6 bg-white rounded-lg">
        @csrf
        <h2 class="text-lg font-medium text-gray-900">
            Rejeitar Reserva
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Por favor, informe o motivo da rejeição. Esta observação será visível para o solicitante.
        </p>
        <div class="mt-4">
            <x-input-label for="observacao_rejeitar" value="Motivo da Rejeição" />
            <textarea id="observacao_rejeitar" name="observacao" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" required></textarea>
            <x-input-error :messages="$errors->get('observacao')" class="mt-2" />
        </div>
        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                {{ __('Cancelar') }}
            </x-secondary-button>
            <x-danger-button class="ml-3">
                {{ __('Confirmar Rejeição') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>

{{-- Modal: Cancelar Reserva --}}
<x-modal name="modal-cancelar-reserva" maxWidth="lg">
    <form method="post" action="{{ route('reservas.cancelar', $reserva) }}" class="p-6 bg-white rounded-lg">
        @csrf
        <h2 class="text-lg font-medium text-gray-900">
            Cancelar Reserva
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Tem certeza que deseja cancelar esta reserva? Esta ação não pode ser desfeita.
        </p>
        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                {{ __('Manter Reserva') }}
            </x-secondary-button>
            <x-danger-button class="ml-3 bg-yellow-500 hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:ring-yellow-500">
                {{ __('Sim, Cancelar Reserva') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>

{{-- Modal: Iniciar Viagem / Saída --}}
<x-modal name="modal-iniciar-reserva" :show="$errors->has('res_km_inicio') || $errors->has('res_comb_inicio')" maxWidth="lg">
    <form method="post" action="{{ route('reservas.iniciar', $reserva) }}" class="p-6 bg-white rounded-lg">
        @csrf
        <h2 class="text-lg font-medium text-gray-900">
            {{ $reserva->res_tipo == 'viagem' ? 'Iniciar Viagem' : 'Registrar Saída para Manutenção' }}
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Por favor, confirme a quilometragem e o nível de combustível no momento da saída.
        </p>
        
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="res_km_inicio" value="KM de Saída *" />
                <x-text-input id="res_km_inicio" name="res_km_inicio" type="number" class="mt-1 block w-full" 
                              :value="old('res_km_inicio', $reserva->veiculo->vei_km_atual ?? 0)" required />
                <x-input-error :messages="$errors->get('res_km_inicio')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="res_comb_inicio" value="Combustível na Saída *" />
                <select id="res_comb_inicio" name="res_comb_inicio" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                    <option value="cheio" @selected(old('res_comb_inicio') == 'cheio')>Cheio</option>
                    <option value="3/4" @selected(old('res_comb_inicio') == '3/4')>3/4</option>
                    <option value="1/2" @selected(old('res_comb_inicio') == '1/2')>1/2</option>
                    <option value="1/4" @selected(old('res_comb_inicio') == '1/4')>1/4</option>
                    <option value="reserva" @selected(old('res_comb_inicio') == 'reserva')>Reserva</option>
                </select>
                <x-input-error :messages="$errors->get('res_comb_inicio')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                {{ __('Cancelar') }}
            </x-secondary-button>
            <x-primary-button class="ml-3 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:ring-blue-500">
                {{ __('Confirmar Saída') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>

{{-- Modal: Finalizar Viagem / Chegada --}}
<x-modal name="modal-finalizar-reserva" :show="$errors->has('res_km_fim') || $errors->has('res_comb_fim')" maxWidth="lg">
    <form method="post" action="{{ route('reservas.finalizar', $reserva) }}" class="p-6 bg-white rounded-lg">
        @csrf
        <h2 class="text-lg font-medium text-gray-900">
            {{ $reserva->res_tipo == 'viagem' ? 'Finalizar Viagem' : 'Registrar Chegada' }}
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Informe os dados de retorno do veículo para enviar para revisão.
        </p>
        
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <x-input-label for="res_km_fim" value="KM de Chegada *" />
                <x-text-input id="res_km_fim" name="res_km_fim" type="number" class="mt-1 block w-full" 
                              :value="old('res_km_fim', $reserva->res_km_inicio ?? 0)" required />
                <x-input-error :messages="$errors->get('res_km_fim')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="res_comb_fim" value="Combustível na Chegada *" />
                <select id="res_comb_fim" name="res_comb_fim" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                    <option value="cheio" @selected(old('res_comb_fim') == 'cheio')>Cheio</option>
                    <option value="3/4" @selected(old('res_comb_fim') == '3/4')>3/4</option>
                    <option value="1/2" @selected(old('res_comb_fim') == '1/2')>1/2</option>
                    <option value="1/4" @selected(old('res_comb_fim') == '1/4')>1/4</option>
                    <option value="reserva" @selected(old('res_comb_fim') == 'reserva')>Reserva</pre>
                </select>
                <x-input-error :messages="$errors->get('res_comb_fim')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="res_hora_chegada" value="Data/Hora de Chegada *" />
                <x-text-input id="res_hora_chegada" name="res_hora_chegada" type="datetime-local" class="mt-1 block w-full" 
                              :value="old('res_hora_chegada', now()->format('Y-m-d\TH:i'))" required />
                <x-input-error :messages="$errors->get('res_hora_chegada')" class="mt-2" />
            </div>
        </div>
        <div class="mt-6">
            <x-input-label for="res_obs_finais" value="Observações Finais (Motorista)" />
            <textarea id="res_obs_finais" name="res_obs_finais" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('res_obs_finais') }}</textarea>
            <x-input-error :messages="$errors->get('res_obs_finais')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                {{ __('Cancelar') }}
            </x-secondary-button>
            <x-primary-button class="ml-3">
                {{ __('Finalizar e Enviar p/ Revisão') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>

{{-- Modal: Processar Revisão --}}
<x-modal name="modal-revisar-reserva" :show="$errors->has('acao') || $errors->has('res_obs_revisor')" maxWidth="lg">
    <form method="post" action="{{ route('reservas.revisar', $reserva) }}" class="p-6 bg-white rounded-lg" x-ref="formRevisao">
        @csrf
        <h2 class="text-lg font-medium text-gray-900">
            Processar Revisão da Reserva
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Analise os dados da reserva e escolha uma ação: "Encerrar" (se tudo estiver correto) ou "Devolver para Ajuste" (se o motorista precisar corrigir algo).
        </p>
        
        {{-- Input oculto para a ação --}}
        <input type="hidden" name="acao" x-ref="acaoRevisao">

        <div class="mt-6">
            <x-input-label for="res_obs_revisor" value="Observações do Revisor" />
            <textarea id="res_obs_revisor" name="res_obs_revisor" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('res_obs_revisor', $reserva->res_obs_revisor) }}</textarea>
            <x-input-error :messages="$errors->get('res_obs_revisor')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-between">
            <x-secondary-button type="button" @click="$dispatch('close')">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <div>
                {{-- Botão Devolver --}}
                 <x-danger-button type="submit" class="ml-3 bg-yellow-500 hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:ring-yellow-400"
                                  @click.prevent="$refs.acaoRevisao.value = 'devolver'; $refs.formRevisao.submit();">
                    {{ __('Devolver para Ajuste') }}
                </x-danger-button>

                {{-- Botão Encerrar --}}
                <x-primary-button type="submit" class="ml-3 bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:ring-indigo-500"
                                @click.prevent="$refs.acaoRevisao.value = 'encerrar'; $refs.formRevisao.submit();">
                    {{ __('Confirmar e Encerrar') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-modal>