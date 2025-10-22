<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </div>
    </x-slot>

    {{-- Exibe mensagem de erro se houver --}}
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg max-w-7xl mx-auto sm:px-6 lg:px-8" role="alert">
            {{ session('error') }}
        </div>
    @endif


    @if (!Auth::user()->id_empresa)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{ __("You're logged in!") }}
            </div>
        </div>
    @else
        {{-- 
            Container principal do Dashboard com Alpine.js para controlar as abas.
            'tab' armazena a aba ativa.
        --}}
        <div class="space-y-8" x-data="{ tab: 'veiculos', openVeiculo: null }">

            {{-- Definição das Abas --}}
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    {{-- Aba Veículos --}}
                    <button
                        @click.prevent="tab = 'veiculos'"
                        :class="{
                            'border-blue-500 text-blue-600': tab === 'veiculos',
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'veiculos'
                        }"
                        class="relative whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm focus:outline-none flex items-center group"
                        aria-current="page"
                    >
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-17.25 4.5h12.75m0 0H4.125M6 18.75h3.875m0 0h1.5M12 9.75V10.5m-2.063.146a3.75 3.75 0 0 1 5.275 0l.556.477a11.251 11.251 0 0 1 4.5 8.683c0 .34-.02.676-.058 1.012H5.558a11.318 11.318 0 0 1-.058-1.012c0-3.32 1.64-6.336 4.5-8.683l.556-.477Z" />
                        </svg>
                        <span>Veículos</span>

                        {{-- Notificação para Veículos --}}
                        @if(($manutencoesVencidasCount ?? 0) > 0 || ($alertasProximosCount ?? 0) > 0)
                            <span class="{{ ($manutencoesVencidasCount ?? 0) > 0 ? 'bg-red-500' : 'bg-yellow-500' }} ml-2 w-3 h-3 rounded-full absolute top-2 right-[-8px] group-hover:animate-pulse"></span>
                        @endif
                    </button>

                    {{-- Aba Motoristas --}}
                    <button
                        @click.prevent="tab = 'motoristas'"
                        :class="{
                            'border-blue-500 text-blue-600': tab === 'motoristas',
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'motoristas'
                        }"
                        class="relative whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm focus:outline-none flex items-center group"
                    >
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        <span>Motoristas</span>
                        
                        {{-- Notificação para Motoristas --}}
                         @if(($motoristasCnhVencidaCount ?? 0) > 0 || ($motoristasCnhAVencerCount ?? 0) > 0)
                            <span class="{{ ($motoristasCnhVencidaCount ?? 0) > 0 ? 'bg-red-500' : 'bg-yellow-500' }} ml-2 w-3 h-3 rounded-full absolute top-2 right-[-8px] group-hover:animate-pulse"></span>
                        @endif
                    </button>
                </nav>
            </div>


            {{-- Conteúdo da Aba Veículos --}}
            <div x-show="tab === 'veiculos'" class="space-y-8"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                @include('dashboard.components.veiculos.summary-cards')
                @include('dashboard.components.veiculos.analysis-cards')
                @include('dashboard.components.veiculos.charts')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 lg:items-start">
                    @include('dashboard.components.veiculos.fleet-list')
                    @include('dashboard.components.veiculos.upcoming-reminders')
                </div>
            </div>

            {{-- Conteúdo da Aba Motoristas --}}
            <div x-show="tab === 'motoristas'" class="space-y-8"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                 @include('dashboard.components.motoristas.cards')
                
                 {{-- Placeholder para gráficos futuros --}}
                 <div class="p-6 bg-white rounded-lg shadow text-center text-gray-500 min-h-[150px] flex items-center justify-center">
                    <p>(Área reservada para futuros gráficos e análises de motoristas)</p>
                </div>
            </div>

        </div>

        @push('modals')
            {{-- Modais de Veículos --}}
            @include('dashboard.components.veiculos.modal-historico')
            @include('dashboard.components.veiculos.modal-analise')
            @include('dashboard.components.veiculos.modal-manutencoes-vencidas')
            @include('dashboard.components.veiculos.modal-alertas-proximos')
            @include('dashboard.components.veiculos.modal-custos-mensais')
            @include('dashboard.components.veiculos.modal-ranking-servicos')

            {{-- Modais de Motoristas --}}
            @include('dashboard.components.motoristas.modal-bloqueados')
            @include('dashboard.components.motoristas.modal-cnh-vencida')
            @include('dashboard.components.motoristas.modal-cnh-a-vencer')
            @include('dashboard.components.motoristas.modal-novos')
            @include('dashboard.components.motoristas.modal-treinamento')
        @endpush
    @endif
</x-app-layout>

