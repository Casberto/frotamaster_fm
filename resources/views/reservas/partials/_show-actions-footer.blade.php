<div class="flex items-center justify-between w-full gap-4">
    
    {{-- Lado Esquerdo (Opcional: Status ou Info Rápida) --}}
    <div class="hidden md:block text-xs text-gray-500">
        <span class="font-semibold">Status:</span> {{ ucfirst(str_replace('_', ' ', $reserva->res_status)) }}
    </div>

    {{-- Lado Direito: Botões --}}
    <div class="flex items-center justify-end flex-1 gap-2">
        
        {{-- Botão Voltar (Transformado em Link <a>) --}}
        <a href="{{ route('reservas.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
            {{ __('Voltar') }}
        </a>

        {{-- Editar: Status Compatível + Permissão Editar (Transformado em Link <a>) --}}
        @if(in_array($reserva->res_status, ['pendente', 'rejeitada', 'pendente_ajuste']) && Auth::user()->hasPermission('Reservas', 'Editar'))
            <a href="{{ route('reservas.edit', $reserva) }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                {{ $reserva->res_status == 'pendente_ajuste' ? 'Corrigir' : 'Editar' }}
            </a>
        @endif

        {{-- Cancelar (Pendente/Aprovada) - Mantém como Button pois abre Modal --}}
        @if(in_array($reserva->res_status, ['pendente', 'aprovada']) && Auth::user()->hasPermission('Reservas', 'Excluir'))
            <x-danger-button type="button" class="bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-400 text-white"
                            x-on:click.prevent="$dispatch('open-modal', 'modal-cancelar-reserva')">
                {{ __('Cancelar') }}
            </x-danger-button>
        @endif

        {{-- Rejeitar (Pendente) - Mantém como Button pois abre Modal --}}
        @if ($reserva->res_status == 'pendente' && Auth::user()->hasPermission('Reservas', 'Reprovar'))
            <x-danger-button type="button" x-on:click.prevent="$dispatch('open-modal', 'modal-rejeitar-reserva')">
                {{ __('Rejeitar') }}
            </x-danger-button>
        @endif

        {{-- Aprovar (Pendente) - Mantém como Button pois abre Modal --}}
        @if ($reserva->res_status == 'pendente' && Auth::user()->hasPermission('Reservas', 'Aprovar'))
            <x-primary-button type="button" class="bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white"
                            x-on:click.prevent="$dispatch('open-modal', 'modal-aprovar-reserva')">
                {{ __('Aprovar') }}
            </x-primary-button>
        @endif

        {{-- Iniciar (Aprovada) - Mantém como Button pois abre Modal --}}
        @if ($reserva->res_status == 'aprovada' && Auth::user()->hasPermission('Reservas', 'Registrar Saída'))
            <x-primary-button
                @click.prevent="$dispatch('open-modal', 'modal-iniciar-reserva')"
                class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.82m5.84-2.56a12.06 12.06 0 0 1 0 7.38m-5.84-7.38a12.06 12.06 0 0 0 0 7.38m5.84-7.38L18 17.11m-5.84-2.56L18 17.11m-5.84-2.56A12.06 12.06 0 0 1 4.11 5.63l-1.42 1.42m5.84 5.31A12.06 12.06 0 0 0 4.11 5.63l-1.42 1.42m5.84 5.31L4.11 5.63" /></svg>
                {{ $reserva->res_tipo == 'viagem' ? 'Iniciar Viagem' : 'Saída' }}
            </x-primary-button>
        @endif

        {{-- Finalizar (Em Uso) - Mantém como Button pois abre Modal --}}
        @if ($reserva->res_status == 'em_uso' && Auth::user()->hasPermission('Reservas', 'Finalizar'))
            <x-primary-button
                @click.prevent="$dispatch('open-modal', 'modal-finalizar-reserva')"
                class="bg-purple-600 hover:bg-purple-700 focus:ring-purple-500 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2"> <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /> </svg>
                {{ $reserva->res_tipo == 'viagem' ? 'Finalizar' : 'Concluir' }}
            </x-primary-button>
        @endif

        {{-- Revisar (Em Revisão) - Mantém como Button pois abre Modal --}}
        @if ($reserva->res_status == 'em_revisao' && Auth::user()->hasPermission('Reservas', 'Encerrar'))
            <x-primary-button
                @click.prevent="$dispatch('open-modal', 'modal-revisar-reserva')"
                class="bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
                Revisar
            </x-primary-button>
        @endif
    </div>
</div>