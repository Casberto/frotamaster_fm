<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Financeiro da Oficina') }} <span class="text-sm font-normal text-gray-500">| Este Mês</span>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm font-bold uppercase mb-1">Faturamento (Entregues)</div>
                    <div class="text-2xl font-black text-gray-800">R$ {{ number_format($resumo->faturamento, 2, ',', '.') }}</div>
                    <div class="text-xs text-gray-400 mt-2">{{ $resumo->qtd_os }} serviços finalizados</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-400">
                    <div class="text-gray-500 text-sm font-bold uppercase mb-1">Custos Totais</div>
                    <div class="text-2xl font-black text-red-600">R$ {{ number_format($resumo->custos, 2, ',', '.') }}</div>
                    <div class="text-xs text-gray-400 mt-2">Peças e insumos (OS Entregues)</div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-lg sm:rounded-lg p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="text-green-100 text-xs font-bold uppercase mb-1">Lucro Líquido (Realizado)</div>
                    <div class="text-3xl font-black">R$ {{ number_format($lucroLiquido, 2, ',', '.') }}</div>
                    <div class="mt-2 flex items-center">
                        <span class="bg-green-700 bg-opacity-50 px-2 py-1 rounded text-xs font-bold">
                            Margem: {{ number_format($margem, 1) }}%
                        </span>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 overflow-hidden shadow-lg sm:rounded-lg p-6 text-white">
                    <div class="text-indigo-100 text-xs font-bold uppercase mb-1">Lucro Futuro (A Receber)</div>
                    <div class="text-3xl font-black">R$ {{ number_format($lucroFuturo, 2, ',', '.') }}</div>
                    <div class="mt-2 text-indigo-100 text-xs">
                        Faturamento Futuro: R$ {{ number_format($faturamentoFuturo, 2, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 flex flex-col justify-center items-center text-center">
                    <div class="bg-orange-100 p-4 rounded-full mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Lista de Compras do Dia</h3>
                    <p class="text-sm text-gray-500 mb-4">Veja as peças aprovadas que precisam ser compradas.</p>
                    <a href="{{ route('oficina.compras.dia') }}" class="bg-orange-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-orange-700 w-full md:w-auto">
                        Ver Lista de Peças
                    </a>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-700 font-bold mb-4 border-b pb-2">Últimas Entregas</h3>
                    <div class="space-y-3">
                        @forelse($ultimasOs as $os)
                            <div class="flex justify-between items-center text-sm">
                                <div>
                                    <span class="font-bold block text-gray-800">{{ $os->veiculo->vct_modelo }}</span>
                                    <span class="text-xs text-gray-500">{{ $os->veiculo->cliente->clo_nome }}</span>
                                </div>
                                <span class="font-bold text-green-600">+ R$ {{ number_format($os->osv_valor_total, 2, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="text-gray-400 text-sm text-center py-4">Nenhum serviço finalizado recentemente.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
