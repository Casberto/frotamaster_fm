<!-- Botões de Ação no Rodapé -->
<div class="flex items-center justify-end flex-wrap gap-3 mt-8 pt-6 border-t">
     <x-secondary-button :href="route('reservas.index')">
        {{ __('Voltar') }}
    </x-secondary-button>

    {{-- Pode editar se pendente, rejeitada, ou pendente de ajuste --}}
    @if(in_array($reserva->res_status, ['pendente', 'rejeitada', 'pendente_ajuste']))
        <x-secondary-button :href="route('reservas.edit', $reserva)">
            {{ $reserva->res_status == 'pendente_ajuste' ? 'Corrigir e Reenviar' : 'Editar Reserva' }}
        </x-secondary-button>
    @endif

    <!-- Ações do Workflow -->
    @if ($reserva->res_status == 'pendente')
        <form action="{{ route('reservas.rejeitar', $reserva) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja REJEITAR esta reserva?');">
            @csrf
            <x-danger-button type="submit"> {{ __('Rejeitar') }} </x-danger-button>
        </form>
        <form action="{{ route('reservas.aprovar', $reserva) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja APROVAR esta reserva?');">
            @csrf
            <x-primary-button type="submit" class="bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:ring-green-500"> {{ __('Aprovar') }} </x-primary-button>
        </form>
    @endif

    @if(in_array($reserva->res_status, ['pendente', 'aprovada']))
        <form action="{{ route('reservas.cancelar', $reserva) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja CANCELAR esta reserva?');">
            @csrf
            <x-danger-button type="submit" class="bg-yellow-500 hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:ring-yellow-400"> {{ __('Cancelar Reserva') }} </x-danger-button>
        </form>
    @endif

    <!-- BOTÃO: Iniciar Reserva (CORRIGIDO) -->
    @if ($reserva->res_status == 'aprovada')
        <x-primary-button
            @click.prevent="$dispatch('open-modal', 'modal-iniciar-reserva')"
            class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:ring-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.82m5.84-2.56a12.06 12.06 0 0 1 0 7.38m-5.84-7.38a12.06 12.06 0 0 0 0 7.38m5.84-7.38L18 17.11m-5.84-2.56L18 17.11m-5.84-2.56A12.06 12.06 0 0 1 4.11 5.63l-1.42 1.42m5.84 5.31A12.06 12.06 0 0 0 4.11 5.63l-1.42 1.42m5.84 5.31L4.11 5.63" /></svg>
            {{ $reserva->res_tipo == 'viagem' ? 'Iniciar Viagem' : 'Registrar Saída' }}
        </x-primary-button>
    @endif

    <!-- BOTÃO: Finalizar Reserva (CORRIGIDO) -->
    @if ($reserva->res_status == 'em_uso')
        <x-primary-button
            @click.prevent="$dispatch('open-modal', 'modal-finalizar-reserva')"
            class="bg-purple-600 hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-800 focus:ring-purple-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2"> <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /> </svg>
            Finalizar Viagem/Manutenção
        </x-primary-button>
    @endif

    <!-- BOTÃO: Revisar (Fase 7) (CORRIGIDO) -->
    @if ($reserva->res_status == 'em_revisao')
        <x-primary-button
            @click.prevent="$dispatch('open-modal', 'modal-revisar-reserva')"
            class="bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:ring-indigo-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
            Revisar Reserva
        </x-primary-button>
    @endif
</div>

