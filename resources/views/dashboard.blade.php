<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
                            {{-- Lógica para definir ícone e cor com base no tipo de veículo --}}
                            @php
                                $iconConfig = [
                                    'carro' => ['icon' => 'heroicon-s-truck', 'bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                                    'caminhao' => ['icon' => 'heroicon-s-truck', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
                                    'van' => ['icon' => 'heroicon-s-truck', 'bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                                    'moto' => ['icon' => 'heroicon-s-fire', 'bg' => 'bg-green-100', 'text' => 'text-green-600'],
                                    'outro' => ['icon' => 'heroicon-s-question-mark-circle', 'bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                                ];
                                $config = $iconConfig[strtolower($veiculo->tipo_veiculo)] ?? $iconConfig['outro'];
                            @endphp

                            <div x-data="{ open: false }" class="bg-white rounded-lg border border-gray-200 overflow-hidden transition-shadow hover:shadow-md">
                                {{-- Cabeçalho Clicável --}}
                                <div @click="open = !open" class="flex items-center justify-between p-3 cursor-pointer">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-2 rounded-full {{ $config['bg'] }}">
                                            {{-- SVG do Heroicons. Adapte os ícones se necessário. --}}
                                            @if($veiculo->tipo_veiculo == 'moto')
                                                <svg class="h-6 w-6 {{ $config['text'] }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M5.25 7.5A2.25 2.25 0 0 0 3 9.75v2.5A2.25 2.25 0 0 0 5.25 14.5h.5a2.25 2.25 0 0 0 2.25-2.25v-2.5A2.25 2.25 0 0 0 5.75 7.5h-.5Zm8.5 0A2.25 2.25 0 0 0 11.5 9.75v2.5a2.25 2.25 0 0 0 2.25 2.25h.5a2.25 2.25 0 0 0 2.25-2.25v-2.5a2.25 2.25 0 0 0-2.25-2.25h-.5Z" /><path fill-rule="evenodd" d="M6.161 3.694a.75.75 0 0 1 .656.24l1.36 1.633a.75.75 0 0 1-1.152.96l-1.36-1.633a.75.75 0 0 1 .496-1.2Z" clip-rule="evenodd" /><path fill-rule="evenodd" d="M14.339 3.694a.75.75 0 0 0-.656.24l-1.36 1.633a.75.75 0 0 0 1.152.96l1.36-1.633a.75.75 0 0 0-.496-1.2Z" clip-rule="evenodd" /></svg>
                                            @elseif($veiculo->tipo_veiculo == 'caminhao')
                                                <svg class="h-6 w-6 {{ $config['text'] }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.707 2.293a1 1 0 0 0-1.414 0l-7 7a1 1 0 0 0 1.414 1.414L4 10.414V17a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5V17a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-6.586l.293.293a1 1 0 0 0 1.414-1.414l-7-7Z" clip-rule="evenodd" /></svg>
                                            @else {{-- Carro, Van, Outro --}}
                                                <svg class="h-6 w-6 {{ $config['text'] }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-17.25 4.5v-1.875a3.375 3.375 0 003.375-3.375h1.5a1.125 1.125 0 011.125 1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375m15.75 0v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125 1.125v-1.5c0-.621.504-1.125 1.125-1.125h1.5a3.375 3.375 0 003.375-3.375V6.375c0-1.036-.84-1.875-1.875-1.875H3.375A1.875 1.875 0 001.5 6.375v1.5c0 1.036.84 1.875 1.875 1.875h1.5c.621 0 1.125.504 1.125 1.125v1.5a1.125 1.125 0 01-1.125 1.125h-1.5a3.375 3.375 0 00-3.375 3.375V18.75c0 .621.504 1.125 1.125 1.125h1.5a1.125 1.125 0 011.125 1.125v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 003.375 3.375h3.375a3.375 3.375 0 003.375-3.375h1.5c.621 0 1.125-.504 1.125-1.125v-1.5a1.125 1.125 0 011.125-1.125h1.5Z" /></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $veiculo->marca }} {{ $veiculo->modelo }}</p>
                                            <p class="text-sm text-gray-500">{{ $veiculo->placa }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        @if($veiculo->manutencoes->where('data_manutencao', '<', now())->where('status', '!=', 'concluida')->count() > 0)
                                            <span class="text-xs font-medium bg-red-100 text-red-800 px-2 py-1 rounded-full">Pendente</span>
                                        @else
                                            <span class="text-xs font-medium bg-green-100 text-green-800 px-2 py-1 rounded-full">Em dia</span>
                                        @endif
                                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>

                                {{-- Conteúdo do Dropdown --}}
                                <div x-show="open" x-transition class="border-t border-gray-200 bg-gray-50 p-4">
                                    {{-- Grid de Detalhes --}}
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 text-sm mb-4">
                                        <div>
                                            <p class="text-gray-500">Cor</p>
                                            <p class="font-medium">{{ ucfirst($veiculo->cor) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Marca</p>
                                            <p class="font-medium">{{ $veiculo->marca }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Ano Fabricação</p>
                                            <p class="font-medium">{{ $veiculo->ano_fabricacao }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Combustível</p>
                                            <p class="font-medium">{{ ucfirst($veiculo->tipo_combustivel) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Data de Aquisição</p>
                                            <p class="font-medium">{{ \Carbon\Carbon::parse($veiculo->data_aquisicao)->format('d/m/Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">KM Atual</p>
                                            <p class="font-medium">{{ number_format($veiculo->quilometragem_atual, 0, '', '.') }} km</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">KM Total Rodado</p>
                                            {{-- Nota: O campo 'quilometragem_inicial' precisa ser criado na tabela 'veiculos' para este cálculo funcionar --}}
                                            <p class="font-medium">{{ number_format($veiculo->quilometragem_atual - ($veiculo->quilometragem_inicial ?? 0), 0, '', '.') }} km</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Último Abastecimento</p>
                                            {{-- Nota: A relação 'ultimoAbastecimento' deve ser definida no Model Veiculo como: public function ultimoAbastecimento() { return $this->hasOne(Abastecimento::class)->latestOfMany('data_abastecimento'); } --}}
                                            <p class="font-medium">{{ $veiculo->ultimoAbastecimento ? \Carbon\Carbon::parse($veiculo->ultimoAbastecimento->data_abastecimento)->format('d/m/Y') : 'Nenhum registro' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Último Combustível</p>
                                            @if ($veiculo->ultimoAbastecimento && $veiculo->ultimoAbastecimento->tipo_combustivel)
                                                <p class="font-bold text-teal-600">{{ ucfirst($veiculo->ultimoAbastecimento->tipo_combustivel) }}</p>
                                            @else
                                                <p class="font-medium">N/D</p>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Custo Abastec. (Mês)</p>
                                            {{-- CORREÇÃO: Usando a variável correta 'custo_mensal_abastecimento' definida no Controller --}}
                                            <p class="font-medium text-blue-600">R$ {{ number_format($veiculo->custo_mensal_abastecimento, 2, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Custo Manut. (Mês)</p>
                                            {{-- CORREÇÃO: Usando a variável correta 'custo_mensal_manutencao' definida no Controller --}}
                                            <p class="font-medium text-yellow-600">R$ {{ number_format($veiculo->custo_mensal_manutencao, 2, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Custo Total (Mês)</p>
                                            {{-- CORREÇÃO: Usando as variáveis corretas para a soma --}}
                                            <p class="font-bold text-gray-800">R$ {{ number_format($veiculo->custo_total_mensal, 2, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    {{-- Manutenções Pendentes --}}
                                    <div class="mt-4">
                                        <h5 class="font-semibold text-gray-700 mb-2">Manutenções Pendentes</h5>
                                        @php
                                            $manutencoesPendentes = $veiculo->manutencoes->where('data_manutencao', '<', now())->where('status', '!=', 'concluida');
                                        @endphp
                                        @if($manutencoesPendentes->count() > 0)
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach($manutencoesPendentes as $manutencao)
                                                    <li class="text-sm text-red-600">{{ $manutencao->descricao_servico }} - Venceu em: {{ \Carbon\Carbon::parse($manutencao->data_manutencao)->format('d/m/Y') }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-sm text-gray-500">Nenhuma manutenção pendente.</p>
                                        @endif
                                    </div>

                                    {{-- Botões de Ação --}}
                                    <div class="mt-4 flex space-x-2">
                                        <button class="text-sm px-3 py-1 bg-gray-300 text-gray-500 rounded-md cursor-not-allowed" disabled>Ver Detalhes</button>
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
