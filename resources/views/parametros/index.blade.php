<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Parâmetros do Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- CORREÇÃO: Ação do formulário aponta para a rota correta --}}
            <form action="{{ route('parametros.update') }}" method="POST">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        @if (session('success'))
                            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                         @if (session('error'))
                            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($configuracoesAgrupadas->isEmpty())
                            <p class="text-center text-gray-500">Nenhuma configuração disponível para esta empresa.</p>
                        @else
                            <div x-data="{ activeTab: '{{ $configuracoesAgrupadas->keys()->first() }}' }">
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
                                        <div x-show="activeTab === '{{ $modulo }}'" class="space-y-8">
                                            @foreach ($configuracoes as $config)
                                                <div>
                                                    <label for="config-{{ $config->cfe_id }}" class="block text-sm font-medium text-gray-700">
                                                        {{ $config->configuracaoPadrao->cfp_descricao ?? $config->cfe_chave }}
                                                    </label>
                                                    
                                                    {{-- CORREÇÃO: Nome do input ajustado para 'config[id]' --}}
                                                    @if ($config->configuracaoPadrao->cfp_tipo === 'boolean')
                                                        <select name="config[{{ $config->cfe_id }}]" id="config-{{ $config->cfe_id }}" class="mt-1 block w-full md:w-1/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                            <option value="1" @selected($config->cfe_valor == '1' || $config->cfe_valor === true)>Sim</option>
                                                            <option value="0" @selected($config->cfe_valor == '0' || $config->cfe_valor === false)>Não</option>
                                                        </select>
                                                    @elseif ($config->configuracaoPadrao->cfp_tipo === 'int')
                                                        <input type="number" name="config[{{ $config->cfe_id }}]" id="config-{{ $config->cfe_id }}" value="{{ $config->cfe_valor }}" class="mt-1 block w-full md:w-1/3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                    @else
                                                        <input type="text" name="config[{{ $config->cfe_id }}]" id="config-{{ $config->cfe_id }}" value="{{ $config->cfe_valor }}" class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @if(!$configuracoesAgrupadas->isEmpty())
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 sm:rounded-b-lg">
                         <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Salvar Alterações
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-app-layout>
