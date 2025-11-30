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
        <div class="space-y-8" x-data="{ tab: 'geral', openVeiculo: null }">

            {{-- Definição das Abas --}}
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    {{-- Aba Geral --}}
                    <button
                        @click.prevent="tab = 'geral'"
                        :class="{
                            'border-blue-500 text-blue-600': tab === 'geral',
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'geral'
                        }"
                        class="relative whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm focus:outline-none flex items-center group"
                        aria-current="page"
                    >
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                        <span>Visão Geral</span>
                    </button>

                    {{-- Aba Veículos --}}
                    <button
                        @click.prevent="tab = 'veiculos'"
                        :class="{
                            'border-blue-500 text-blue-600': tab === 'veiculos',
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'veiculos'
                        }"
                        class="relative whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm focus:outline-none flex items-center group"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            viewBox="0 0 256 256" 
                            class="w-5 h-5 mr-2 fill-current">
                            <path d="M240 112h-10.8l-27.78-62.5A16 16 0 0 0 186.8 40H69.2a16 16 0 0 0-14.62 9.5L26.8 112H16a8 8 0 0 0 0 16h8v80a16 16 0 0 0 16 16h24a16 16 0 0 0 16-16v-16h96v16a16 16 0 0 0 16 16h24a16 16 0 0 0 16-16v-80h8a8 8 0 0 0 0-16ZM69.2 56h117.6l24.89 56H44.31ZM64 208H40v-16h24Zm128 0v-16h24v16Zm24-32H40v-48h176ZM56 152a8 8 0 0 1 8-8h16a8 8 0 0 1 0 16H64a8 8 0 0 1-8-8Zm112 0a8 8 0 0 1 8-8h16a8 8 0 0 1 0 16h-16a8 8 0 0 1-8-8Z"/>
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

                    {{-- Aba Manutenções --}}
                    <button
                        @click.prevent="tab = 'manutencoes'"
                        :class="{
                            'border-blue-500 text-blue-600': tab === 'manutencoes',
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'manutencoes'
                        }"
                        class="relative whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm focus:outline-none flex items-center group"
                    >
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.703-.127 1.25.14 2.973.696 3.76 1.964l1.896-1.897L8.032 3.332l-1.897 1.896c1.268.787 1.824 2.51 1.964 3.76.061.54.037 1.153-.127 1.703m0 0l2.16 2.16a2.49 2.49 0 01-2.16 2.16m9.555-9.555l-2.16 2.16a2.49 2.49 0 012.16-2.16z" />
                        </svg>
                        <span>Manutenções</span>
                    </button>

                    {{-- Aba Abastecimentos --}}
                    <button
                        @click.prevent="tab = 'abastecimentos'"
                        :class="{
                            'border-blue-500 text-blue-600': tab === 'abastecimentos',
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'abastecimentos'
                        }"
                        class="relative whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm focus:outline-none flex items-center group"
                    >
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        <span>Abastecimentos</span>
                    </button>

                    {{-- Aba Reservas --}}
                    <button
                        @click.prevent="tab = 'reservas'"
                        :class="{
                            'border-blue-500 text-blue-600': tab === 'reservas',
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'reservas'
                        }"
                        class="relative whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm focus:outline-none flex items-center group"
                    >
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>
                        <span>Reservas</span>
                    </button>
                </nav>
            </div>

            {{-- Conteúdo da Aba Geral --}}
            <div x-show="tab === 'geral'" class="space-y-8"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                @include('dashboard.components.geral.indicators')
                @include('dashboard.components.geral.charts')
                @include('dashboard.components.geral.operational-summary')
                @include('dashboard.components.geral.highlights')
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

            {{-- Conteúdo da Aba Manutenções --}}
            <div x-show="tab === 'manutencoes'" class="space-y-8"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                @include('dashboard.components.manutencoes.content')
            </div>

            {{-- Conteúdo da Aba Abastecimentos --}}
            <div x-show="tab === 'abastecimentos'" class="space-y-8"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                @include('dashboard.components.abastecimentos.content')
            </div>

            {{-- Conteúdo da Aba Reservas --}}
            <div x-show="tab === 'reservas'" class="space-y-8"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
            >
                @include('dashboard.components.reservas.content')
            </div>


        @push('modals')
            {{-- Modais de Veículos --}}
            @include('dashboard.components.veiculos.modal-historico')
            @include('dashboard.components.veiculos.modal-analise')
            @include('dashboard.components.veiculos.modal-manutencoes-vencidas')
            @include('dashboard.components.veiculos.modal-manutencoes-em-andamento')
            @include('dashboard.components.veiculos.modal-alertas-proximos')
            @include('dashboard.components.veiculos.modal-custos-mensais')
            @include('dashboard.components.veiculos.modal-ranking-servicos')
            @include('dashboard.components.veiculos.modal-detalhes-veiculo')

            {{-- Modais de Motoristas --}}
            @include('dashboard.components.motoristas.modal-bloqueados')
            @include('dashboard.components.motoristas.modal-cnh-vencida')
            @include('dashboard.components.motoristas.modal-cnh-a-vencer')
            @include('dashboard.components.motoristas.modal-novos')
            @include('dashboard.components.motoristas.modal-novos')
            @include('dashboard.components.motoristas.modal-treinamento')
            
            {{-- Modais de Manutenções --}}
            @include('dashboard.components.manutencoes.modal-detail')
            
            {{-- Modais de Abastecimentos --}}
            @include('dashboard.components.abastecimentos.modal-detail')
            
            {{-- Modais de Reservas --}}
            @include('dashboard.components.reservas.modal-detail')
        @endpush
    @endif
</x-app-layout>

