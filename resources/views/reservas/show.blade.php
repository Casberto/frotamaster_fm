<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes da Reserva') }}
        </h2>
    </x-slot>

    {{-- 
      Container principal da página.
      O x-data aqui controla a aba ativa para a seção de registros.
    --}}
    <div class="" x-data="{ tab: 'abastecimentos' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Mensagens de Sucesso -->


            <!-- Mensagens de Erro/Validação -->




            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    {{-- 1. NOVO CABEÇALHO (ID, Status e TODAS as Ações) --}}
                    @include('reservas.partials._show-header')

                    {{-- 2. DETALHES PRINCIPAIS (Layout 3 Colunas) --}}
                    @include('reservas.partials._show-details')

                    {{-- 3. NOVO SUMÁRIO (Início, Fim, Revisão) --}}
                    @include('reservas.partials._show-summary')

                    {{-- 4. REGISTROS (Layout em Abas) --}}
                    @include('reservas.partials._show-registros')

                    {{-- O _show-actions-footer foi removido daqui --}}

                </div>
            </div>
        </div>

        {{-- 5. MODAIS DE AÇÃO (Agora com fundo branco) --}}
        @include('reservas.partials._show-modals')

    </div>
</x-app-layout>