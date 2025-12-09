<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Parâmetros do Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">



                    <!-- Filtro de Parâmetros -->
                    <div class="mb-6">
                        <form action="{{ route('parametros.index') }}" method="GET">
                            <label for="search" class="block text-sm font-medium text-gray-700">Buscar Parâmetro</label>
                            <div class="flex items-center space-x-2 mt-1">
                                <input type="text" name="search" id="search" class="block w-full md:w-1/3 border-gray-300 rounded-md shadow-sm" placeholder="Digite o nome ou chave do parâmetro..." value="{{ request('search') }}">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Buscar</button>
                                <a href="{{ route('parametros.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300">Limpar</a>
                            </div>
                        </form>
                    </div>


                    @if($configuracoesAgrupadas->isEmpty())
                        <p class="text-center text-gray-500">
                            @if(request('search'))
                                Nenhum parâmetro encontrado para a busca "{{ request('search') }}".
                            @else
                                Nenhuma configuração disponível para esta empresa.
                            @endif
                        </p>
                    @else
                        <form action="{{ route('parametros.update') }}" method="POST">
                            @csrf
                            <div x-data="{ activeTab: '{{ $configuracoesAgrupadas->keys()->first() ?? '' }}' }">
                                <!-- Abas de Navegação -->
                                <div class="border-b border-gray-200">
                                    <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                                        @foreach ($configuracoesAgrupadas->keys() as $modulo)
                                            <button type="button"
                                                    @click="activeTab = '{{ $modulo }}'"
                                                    :class="{
                                                        'border-indigo-500 text-indigo-600': activeTab === '{{ $modulo }}',
                                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== '{{ $modulo }}'
                                                    }"
                                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                                {{ ucfirst($modulo) }}
                                            </button>
                                        @endforeach
                                    </nav>
                                </div>

                                <!-- Conteúdo das Abas -->
                                <div class="mt-6">
                                    @foreach ($configuracoesAgrupadas as $modulo => $configuracoes)
                                        <div x-show="activeTab === '{{ $modulo }}'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6">
                                            @foreach ($configuracoes as $config)
                                                <div class="break-inside-avoid">
                                                    <label for="config-{{ $config->cfe_id }}" class="block text-sm font-medium text-gray-700">
                                                        {{ $config->configuracaoPadrao->cfp_descricao ?? $config->cfe_chave }}
                                                    </label>
                                                    
                                                    @if ($config->configuracaoPadrao->cfp_tipo === 'boolean')
                                                        <select name="config[{{ $config->cfe_id }}]" id="config-{{ $config->cfe_id }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                            <option value="1" @selected($config->cfe_valor == '1' || $config->cfe_valor === true)>Sim</option>
                                                            <option value="0" @selected($config->cfe_valor == '0' || $config->cfe_valor === false)>Não</option>
                                                        </select>
                                                    @elseif ($config->configuracaoPadrao->cfp_tipo === 'int')
                                                        <input type="number" name="config[{{ $config->cfe_id }}]" id="config-{{ $config->cfe_id }}" value="{{ $config->cfe_valor }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                    @else
                                                        <input type="text" name="config[{{ $config->cfe_id }}]" id="config-{{ $config->cfe_id }}" value="{{ $config->cfe_valor }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="mt-8 pt-5 border-t border-gray-200">
                                @if(Auth::user()->temPermissao('PAR003'))
                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Salvar Alterações
                                    </button>
                                </div>
                                @endif
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

