<!-- Cabeçalho com Status e Ações -->
<div class="flex flex-col md:flex-row justify-between md:items-center mb-6 pb-4 border-b">
    
    {{-- Lado Esquerdo: Título e Status --}}
    <div class="mb-4 md:mb-0">
        <div class="flex items-center space-x-3">
            <h3 class="text-2xl font-bold text-gray-900">
                Reserva #{{ $reserva->res_id }}
            </h3>
            <span class="px-3 py-0.5 inline-flex text-sm font-semibold rounded-full
                         {{ $reserva->res_status == 'pendente' ? 'bg-yellow-100 text-yellow-800' :
                            ($reserva->res_status == 'aprovada' ? 'bg-green-100 text-green-800' :
                            ($reserva->res_status == 'em_uso' ? 'bg-blue-100 text-blue-800' :
                            ($reserva->res_status == 'rejeitada' ? 'bg-red-100 text-red-800' :
                            ($reserva->res_status == 'em_revisao' ? 'bg-purple-100 text-purple-800' :
                            ($reserva->res_status == 'encerrada' ? 'bg-gray-200 text-gray-800' :
                            ($reserva->res_status == 'cancelada' ? 'bg-gray-300 text-gray-700' :
                            ($reserva->res_status == 'pendente_ajuste' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800'))))))) }}">
                {{ ucfirst(str_replace('_', ' ', $reserva->res_status)) }}
            </span>
        </div>
        <p class="mt-1 text-sm text-gray-500">
            Solicitado por: {{ $reserva->solicitante->name ?? 'N/D' }} em {{ $reserva->created_at->format('d/m/Y H:i') }}
        </p>
    </div>

    {{-- Lado Direito: Botões de Ação --}}
    <div class="flex flex-wrap items-center justify-start md:justify-end gap-2">
        {{-- Botão Voltar (Link <a>, já funciona) --}}
        <a href="{{ route('reservas.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
            {{ __('Voltar') }}
        </a>

        {{-- Botão Editar (Link <a>, já funciona) --}}
        @if(in_array($reserva->res_status, ['pendente', 'rejeitada', 'pendente_ajuste']))
            <a href="{{ route('reservas.edit', $reserva) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ $reserva->res_status == 'pendente_ajuste' ? __('Ajustar') : __('Editar') }}
            </a>
        @endif

        {{-- Botões de Workflow (usando Modais) --}}

        {{-- Botão Aprovar --}}
        @if ($reserva->res_status == 'pendente')
            <x-primary-button type="button" class="bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:ring-green-500" 
                              x-on:click.prevent="$dispatch('open-modal', 'modal-aprovar-reserva').window"> {{-- <-- CORREÇÃO: .window --}}
                {{ __('Aprovar') }}
            </x-primary-button>
        @endif
        
        {{-- Botão Rejeitar --}}
        @if ($reserva->res_status == 'pendente')
             <x-danger-button type="button" x-on:click.prevent="$dispatch('open-modal', 'modal-rejeitar-reserva').window"> {{-- <-- CORREÇÃO: .window --}}
                {{ __('Rejeitar') }}
            </x-danger-button>
        @endif
        
        {{-- Botão Iniciar Viagem --}}
        @if ($reserva->res_status == 'aprovada')
            <x-primary-button type="button" class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:ring-blue-500" 
                              x-on:click.prevent="$dispatch('open-modal', 'modal-iniciar-reserva').window"> {{-- <-- CORREÇÃO: .window --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.82m5.84-2.56a16.5 16.5 0 0 0-1.23-1.23l-1.85-1.85a1.5 1.5 0 0 0-2.12 0L3 16.5V19.5h3l8.71-8.71a1.5 1.5 0 0 0 0-2.12Z" /></svg>
                {{ $reserva->res_tipo == 'viagem' ? __('Iniciar Viagem') : __('Registrar Saída') }}
            </x-primary-button>
        @endif

        {{-- Botão Finalizar Viagem --}}
        @if ($reserva->res_status == 'em_uso')
            <x-primary-button type="button" class="bg-gray-800 hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:ring-indigo-500" 
                              x-on:click.prevent="$dispatch('open-modal', 'modal-finalizar-reserva').window"> {{-- <-- CORREÇÃO: .window --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9A2.25 2.25 0 0 0 13.5 5.25h-9a2.25 2.25 0 0 0-2.25 2.25v9A2.25 2.25 0 0 0 4.5 18.75Z" /></svg>
                {{ $reserva->res_tipo == 'viagem' ? __('Finalizar Viagem') : __('Finalizar Manutenção') }}
            </x-primary-button>
        @endif

        {{-- Botão Revisar --}}
        @if ($reserva->res_status == 'em_revisao')
            <x-primary-button type="button" class="bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:ring-indigo-500" 
                              x-on:click.prevent="$dispatch('open-modal', 'modal-revisar-reserva').window"> {{-- <-- CORREÇÃO: .window --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12" /></svg>
                {{ __('Processar Revisão') }}
            </x-primary-button>
        @endif

        {{-- Botão Cancelar --}}
        @if (in_array($reserva->res_status, ['pendente', 'aprovada']))
            <x-secondary-button type="button" class="text-yellow-600 border-yellow-300 hover:bg-yellow-50 focus:ring-yellow-500" 
                                x-on:click.prevent="$dispatch('open-modal', 'modal-cancelar-reserva').window"> {{-- <-- CORREÇÃO: .window --}}
                {{ __('Cancelar Reserva') }}
            </x-secondary-button>
        @endif
    </div>
</div>