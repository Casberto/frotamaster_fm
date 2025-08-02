<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">
                Dashboard
            </h2>
        </div>
    </x-slot>

    {{-- Verifica se o usuário tem uma empresa associada. --}}
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
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500">Veículos Ativos</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $veiculosAtivos }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-400">
                    <h3 class="text-sm font-medium text-gray-500">Alertas Próximos</h3>
                    <p class="mt-2 text-3xl font-bold text-yellow-500">{{ $alertasProximos }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                    <h3 class="text-sm font-medium text-gray-500">Manutenções Vencidas</h3>
                    <p class="mt-2 text-3xl font-bold text-red-600">{{ $manutencoesVencidas }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <h3 class="text-sm font-medium text-gray-500">Custo do Mês</h3>
                    <p class="mt-2 text-3xl font-bold text-blue-600">R$ {{ number_format($custoMensal, 2, ',', '.') }}</p>
                </div>
            </div>

            <!-- Seção de Listas -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- NOVA SEÇÃO INTERATIVA DA FROTA DE VEÍCULOS --}}
                <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Frota de Veículos</h3>
                    <div class="space-y-2">
                        @forelse ($frota as $veiculo)
                            <div x-data="{ open: false }" class="bg-white rounded-lg border border-gray-200 overflow-hidden transition-shadow hover:shadow-md">
                                {{-- Cabeçalho Clicável --}}
                                <div @click="open = !open" class="flex items-center justify-between p-3 cursor-pointer">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-2 rounded-full @if($veiculo->alerta_consumo_ativo) bg-red-100 @else bg-blue-100 @endif">
                                            <svg class="h-6 w-6 @if($veiculo->alerta_consumo_ativo) text-red-600 @else text-blue-600 @endif" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-17.25 4.5v-1.875a3.375 3.375 0 003.375-3.375h1.5a1.125 1.125 0 011.125 1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375m15.75 0v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125 1.125v-1.5c0-.621.504-1.125 1.125-1.125h1.5a3.375 3.375 0 003.375-3.375V6.375c0-1.036-.84-1.875-1.875-1.875H3.375A1.875 1.875 0 001.5 6.375v1.5c0 1.036.84 1.875 1.875 1.875h1.5c.621 0 1.125.504 1.125 1.125v1.5a1.125 1.125 0 01-1.125 1.125h-1.5a3.375 3.375 0 00-3.375 3.375V18.75c0 .621.504 1.125 1.125 1.125h1.5a1.125 1.125 0 011.125 1.125v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 003.375 3.375h3.375a3.375 3.375 0 003.375-3.375h1.5c.621 0 1.125-.504 1.125-1.125v-1.5a1.125 1.125 0 011.125-1.125h1.5Z" /></svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $veiculo->marca }} {{ $veiculo->modelo }}</p>
                                            <p class="text-sm text-gray-500">{{ $veiculo->placa }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        @if($veiculo->alerta_consumo_ativo)
                                            <span class="text-xs font-medium bg-red-100 text-red-800 px-2 py-1 rounded-full">Consumo Alto</span>
                                        @elseif($veiculo->manutencoes->where('data_manutencao', '<', now())->count() > 0)
                                            <span class="text-xs font-medium bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Pendente</span>
                                        @else
                                            <span class="text-xs font-medium bg-green-100 text-green-800 px-2 py-1 rounded-full">Em dia</span>
                                        @endif
                                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>

                                {{-- Conteúdo do Dropdown --}}
                                <div x-show="open" x-transition class="border-t border-gray-200 bg-gray-50 p-4">
                                    @if($veiculo->alerta_consumo_ativo)
                                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-3 rounded-md mb-4" role="alert">
                                            <p><span class="font-bold">Alerta de Consumo:</span> O consumo médio deste veículo ({{ number_format($veiculo->consumo_medio_atual, 2, ',') }} km/l) está abaixo do esperado. Recomendamos uma revisão.</p>
                                        </div>
                                    @endif
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-500">Consumo Atual</p>
                                            <p class="font-medium">{{ $veiculo->consumo_medio_atual ? number_format($veiculo->consumo_medio_atual, 2, ',') . ' km/l' : 'N/D' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Consumo Fábrica</p>
                                            <p class="font-medium">{{ $veiculo->consumo_medio_fabricante ? number_format($veiculo->consumo_medio_fabricante, 2, ',') . ' km/l' : 'Não informado' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">KM Atual</p>
                                            <p class="font-medium">{{ number_format($veiculo->quilometragem_atual, 0, '', '.') }} km</p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <h5 class="font-semibold text-gray-700 mb-2">Manutenções Pendentes</h5>
                                        @if($veiculo->manutencoes->count() > 0)
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach($veiculo->manutencoes as $manutencao)
                                                    <li class="text-sm text-red-600">{{ $manutencao->descricao_servico }} - Vence em: {{ \Carbon\Carbon::parse($manutencao->data_manutencao)->format('d/m/Y') }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-sm text-gray-500">Nenhuma manutenção pendente.</p>
                                        @endif
                                    </div>
                                    <div class="mt-4 flex space-x-2">
                                        <a href="{{ route('veiculos.show', $veiculo->id) }}" class="text-sm px-3 py-1 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Ver Detalhes</a>
                                        <a href="{{ route('abastecimentos.create', ['id_veiculo' => $veiculo->id]) }}" class="text-sm px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600">Abastecer</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500">Nenhum veículo ativo cadastrado.</p>
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
                                <p class="text-sm text-yellow-600 font-medium">Vence {{ \Carbon\Carbon::parse($lembrete->data_manutencao)->diffForHumans() }}</p>
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
