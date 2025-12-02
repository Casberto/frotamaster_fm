<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Novo Abastecimento') }}
        </h2>
    </x-slot>

    <style>
        @media (max-width: 640px) {
            .mobile-stacked-force {
                display: block !important;
            }
        }
    </style>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4 sm:py-6" 
    x-data="{ 
        tab: '{{ $errors->hasAny(['aba_vei_id', 'aba_data', 'aba_km', 'aba_for_id']) ? 'geral' : ($errors->hasAny(['aba_combustivel', 'aba_vlr_tot', 'aba_qtd', 'aba_vlr_und', 'aba_tanque_inicio']) ? 'valores' : ($errors->hasAny(['aba_pneus_calibrados', 'aba_agua_verificada', 'aba_oleo_verificado', 'aba_obs']) ? 'checklist' : 'geral')) }}', 
        mobile: window.matchMedia('(max-width: 640px)').matches 
    }" 
    x-init="window.matchMedia('(max-width: 640px)').addEventListener('change', e => mobile = e.matches);"
    @invalid.capture.window="
        const target = $event.target;
        if (document.getElementById('tab-geral-content') && document.getElementById('tab-geral-content').contains(target)) {
            tab = 'geral';
        } else if (document.getElementById('tab-valores-content') && document.getElementById('tab-valores-content').contains(target)) {
            tab = 'valores';
        } else if (document.getElementById('tab-checklist-content') && document.getElementById('tab-checklist-content').contains(target)) {
            tab = 'checklist';
        }
    ">
        
        {{-- Tab Navigation --}}
        <div class="mb-6 border-b border-gray-200 overflow-x-auto overflow-y-hidden no-scrollbar hidden sm:block">
            <nav class="-mb-px flex space-x-8 min-w-max px-4 sm:px-0" aria-label="Tabs">
                <button @click="tab = 'geral'" 
                    :class="tab === 'geral' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Dados Gerais
                </button>

                <button @click="tab = 'valores'" 
                    :class="tab === 'valores' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Valores & Quantidades
                </button>

                <button @click="tab = 'checklist'" 
                    :class="tab === 'checklist' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Checklist & Observações
                </button>
            </nav>
        </div>

        <form method="POST" action="{{ route('abastecimentos.store') }}">
            @csrf
            
            {{-- ADICIONADO: Campo oculto para saber para onde voltar --}}
            @if(request('reserva_id'))
                <input type="hidden" name="reserva_id" value="{{ request('reserva_id') }}">
            @endif

            @include('abastecimentos._form', ['abastecimento' => new \App\Models\Abastecimento()])
            
            <div class="flex items-center justify-end mt-8 mb-4">
                <a href="{{ route('abastecimentos.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition shadow-sm font-medium">
                    Cancelar
                </a>
                <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm font-medium">
                    Salvar Abastecimento
                </button>
            </div>
        </form>
    </div>
</x-app-layout>