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
                                    'carro' => [
                                        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M5 17a2 2 0 1 0 4 0a2 2 0 1 0-4 0m10 0a2 2 0 1 0 4 0a2 2 0 1 0-4 0"/><path d="M5 17H3v-6l2-5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0H9m-6-6h15m-6 0V6"/></g></svg>',
                                        'bg' => 'bg-blue-100',
                                        'text' => 'text-blue-600'
                                    ],
                                    'caminhao' => [
                                        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 512 448"><path fill="currentColor" d="M405 85h-42V43q0-18-13-30.5T320 0H43Q25 0 12.5 12.5T0 43v256q0 17 12.5 29.5T43 341h4q6 19 22.5 31t37.5 12q20 0 36.5-12t22.5-31h180q6 19 22.5 31t36.5 12q21 0 37.5-12t22.5-31h4q18 0 30.5-12.5T512 299v-77q0-17-13-30zM107 341q-8 0-15-6.5T85 320t7-14.5t15-6.5t14.5 6.5T128 320t-6.5 14.5T107 341zm213-42H166q-6-19-22.5-31T107 256q-21 0-37.5 12T47 299h-4v-86h277v86zm0-214v86H43V43h277v42zm85 256q-8 0-14.5-6.5T384 320t6.5-14.5T405 299t15 6.5t7 14.5t-7 14.5t-15 6.5zm64-42h-4q-6-19-22.5-31T405 256q-27 0-42 17V128h12l94 94v77z"/></svg>',
                                        'bg' => 'bg-yellow-100',
                                        'text' => 'text-yellow-600'
                                    ],
                                    'van' => [
                                        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 256 256"><path fill="currentColor" d="m254.07 114.79l-45.54-53.06A16 16 0 0 0 196.26 56H32a16 16 0 0 0-16 16v112a16 16 0 0 0 16 16h17a32 32 0 0 0 62 0h50a32 32 0 0 0 62 0h17a16 16 0 0 0 16-16v-64a8 8 0 0 0-1.93-5.21ZM230.59 112H176V72h20.26ZM104 112V72h56v40ZM88 72v40H32V72Zm-8 136a16 16 0 1 1 16-16a16 16 0 0 1-16 16Zm112 0a16 16 0 1 1 16-16a16 16 0 0 1-16 16Zm31-24a32 32 0 0 0-62 0h-50a32 32 0 0 0-62 0H32v-56h208v56Z"/></svg>',
                                        'bg' => 'bg-amber-100',
                                        'text' => 'text-amber-700'
                                    ],
                                    'moto' => [
                                        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><circle cx="19.5" cy="16.5" r="2.5"/><circle cx="4.5" cy="16.5" r="2.5"/><path d="M20.235 7.87c1.281 1.559 1.727 3.042 1.764 3.826a5.3 5.3 0 0 0-2.217-.479c-2.445 0-4.64 1.626-5.164 3.792c-.126.518-.188.777-.324.884s-.356.107-.795.107h-2.878c-.443 0-.664 0-.8-.108c-.137-.11-.197-.367-.316-.883c-.496-2.138-2.508-3.997-4.603-3.84c-.211.017-.317.025-.39.008c-.071-.016-.144-.057-.29-.14c-.421-.237-.851-.463-1.264-.714A2 2 0 0 1 2 8.683c-.013-.384.207-.764.652-.66l6.42 1.511c.483.114.724.17.931.132s.462-.212.97-.56c1.288-.88 3.33-1.713 5.365-.978c.557.201.836.302.994.307c.16.005.392-.063.857-.198a9.5 9.5 0 0 1 2.045-.367m0 0c-.802-.978-1.934-1.985-3.5-2.87"/></g></svg>',
                                        'bg' => 'bg-green-100',
                                        'text' => 'text-green-600'
                                    ],
                                    'outro' => [
                                        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>',
                                        'bg' => 'bg-gray-100',
                                        'text' => 'text-gray-600'
                                    ],
                                ];
                                $tipo = strtolower($veiculo->tipo_veiculo);
                                $config = $iconConfig[$tipo] ?? $iconConfig['outro'];
                            @endphp


                            <div x-data="{ open: false }" class="bg-white rounded-lg border border-gray-200 overflow-hidden transition-shadow hover:shadow-md">
                                {{-- Cabeçalho Clicável --}}
                                <div @click="open = !open" class="flex items-center justify-between p-3 cursor-pointer">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-2 rounded-full {{ $config['bg'] }}">
                                            <span class="{{ $config['text'] }}">
                                                {!! $config['svg'] !!}
                                            </span>
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
