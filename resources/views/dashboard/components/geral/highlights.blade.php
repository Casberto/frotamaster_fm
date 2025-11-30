<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Veículos Mais Caros -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Top 5 Veículos Mais Custosos (Mês)
            </h3>
        </div>
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Veículo
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Custo Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($destaques['veiculos_mais_caros'] as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item['veiculo']->vei_placa }} <span class="text-gray-500 font-normal">- {{ $item['veiculo']->vei_modelo }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                            R$ {{ number_format($item['valor'], 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            Sem dados de custos neste mês.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Veículo Mais Usado -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Destaque de Uso
            </h3>
        </div>
        <div class="p-6">
            @if($operacional['veiculo_mais_usado'])
                <div class="text-center">
                    <p class="text-sm text-gray-500 uppercase tracking-wide font-semibold">Veículo que mais rodou este mês</p>
                    <h4 class="mt-2 text-3xl font-extrabold text-gray-900">
                        {{ $operacional['veiculo_mais_usado']['veiculo']->vei_placa }}
                    </h4>
                    <p class="text-lg text-gray-600">{{ $operacional['veiculo_mais_usado']['veiculo']->vei_modelo }}</p>
                    <div class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100">
                        {{ number_format($operacional['veiculo_mais_usado']['km'], 0, ',', '.') }} KM Rodados
                    </div>
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    Dados insuficientes para determinar o veículo mais usado.
                </div>
            @endif
        </div>
    </div>
</div>
