<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">
                Dashboard
            </h2>
        </div>
    </x-slot>

    {{-- Verifica se o usuário tem uma empresa associada. Se não, mostra uma mensagem simples. --}}
    @if (!Auth::user()->id_empresa)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{ __("You're logged in!") }}
            </div>
        </div>
    @else
        {{-- Conteúdo principal do Dashboard --}}
        <div class="space-y-8">
            <!-- Cards de Resumo -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Card Veículos Ativos --}}
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500">Veículos Ativos</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $veiculosAtivos }}</p>
                </div>

                {{-- Card Alertas Próximos --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-400">
                    <h3 class="text-sm font-medium text-gray-500">Alertas Próximos</h3>
                    <p class="mt-2 text-3xl font-bold text-yellow-500">{{ $alertasProximos }}</p>
                </div>

                {{-- Card Manutenções Vencidas --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                    <h3 class="text-sm font-medium text-gray-500">Manutenções Vencidas</h3>
                    <p class="mt-2 text-3xl font-bold text-red-600">{{ $manutencoesVencidas }}</p>
                </div>

                {{-- Card Custo Mensal --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <h3 class="text-sm font-medium text-gray-500">Custo do Mês</h3>
                    <p class="mt-2 text-3xl font-bold text-blue-600">R$ {{ number_format($custoMensal, 2, ',', '.') }}</p>
                </div>
            </div>

            <!-- Seção de Listas -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Coluna Frota de Veículos (ocupa 2 colunas em telas grandes) --}}
                <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Frota de Veículos</h3>
                    <div class="space-y-4">
                        @forelse ($frota as $veiculo)
                            <div class="flex items-center justify-between p-3 rounded-md hover:bg-gray-50">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-blue-100 p-2 rounded-full">
                                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-17.25 4.5v-1.875a3.375 3.375 0 003.375-3.375h1.5a1.125 1.125 0 011.125 1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375m15.75 0v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125 1.125v-1.5c0-.621.504-1.125 1.125-1.125h1.5a3.375 3.375 0 003.375-3.375V6.375c0-1.036-.84-1.875-1.875-1.875H3.375A1.875 1.875 0 001.5 6.375v1.5c0 1.036.84 1.875 1.875 1.875h1.5c.621 0 1.125.504 1.125 1.125v1.5a1.125 1.125 0 01-1.125 1.125h-1.5a3.375 3.375 0 00-3.375 3.375V18.75c0 .621.504 1.125 1.125 1.125h1.5a1.125 1.125 0 011.125 1.125v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 003.375 3.375h3.375a3.375 3.375 0 003.375-3.375h1.5c.621 0 1.125-.504 1.125-1.125v-1.5a1.125 1.125 0 011.125-1.125h1.5Z" /></svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $veiculo->marca }} {{ $veiculo->modelo }}</p>
                                        <p class="text-sm text-gray-500">{{ $veiculo->placa }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-medium bg-green-100 text-green-800 px-2 py-1 rounded-full">Em dia</span>
                            </div>
                        @empty
                            <p class="text-center text-gray-500">Nenhum veículo ativo.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Coluna Próximos Lembretes --}}
                <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Próximos Lembretes</h3>
                    <div class="space-y-4">
                        @forelse ($proximosLembretes as $lembrete)
                            <div class="border-l-4 border-yellow-400 pl-4 py-2">
                                <p class="font-semibold text-gray-800">{{ $lembrete->descricao_servico }}</p>
                                <p class="text-sm text-gray-600">{{ $lembrete->veiculo->placa }} - {{ $lembrete->veiculo->modelo }}</p>
                                <p class="text-sm text-yellow-600 font-medium">Vence em {{ \Carbon\Carbon::parse($lembrete->proxima_revisao_data)->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-center text-gray-500">Nenhum lembrete futuro.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
