<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Registros de Abastecimento</h2>
            <a href="{{ route('abastecimentos.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Novo Abastecimento
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            {{-- Formulário de Filtros --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                <form action="{{ route('abastecimentos.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Filtro de Veículo --}}
                        <div class="md:col-span-1">
                            <label for="veiculo_id" class="block font-medium text-sm text-gray-700">Veículo</label>
                            <select name="veiculo_id" id="veiculo_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos os Veículos</option>
                                @foreach($veiculos as $veiculo)
                                    <option value="{{ $veiculo->vei_id }}" @selected(request('veiculo_id') == $veiculo->vei_id)>
                                        {{ $veiculo->placaModelo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Filtro de Data Início --}}
                        <div>
                            <label for="data_inicio" class="block font-medium text-sm text-gray-700">De</label>
                            <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        {{-- Filtro de Data Fim --}}
                        <div>
                            <label for="data_fim" class="block font-medium text-sm text-gray-700">Até</label>
                            <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <a href="{{ route('abastecimentos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Limpar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Filtrar</button>
                    </div>
                </form>
            </div>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">Veículo</th>
                            <th scope="col" class="px-6 py-3">Data</th>
                            <th scope="col" class="px-6 py-3">KM</th>
                            <th scope="col" class="px-6 py-3">Combustível</th>
                            <th scope="col" class="px-6 py-3">Custo Total</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($abastecimentos as $abastecimento)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $abastecimento->veiculo->placaModelo ?? 'Veículo não encontrado' }}</td>
                            <td class="px-6 py-4">{{ $abastecimento->aba_data->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ number_format($abastecimento->aba_km, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $abastecimento->combustivelTexto }}</td>
                            <td class="px-6 py-4">R$ {{ number_format($abastecimento->aba_vlr_tot, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('abastecimentos.edit', $abastecimento) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                <form action="{{ route('abastecimentos.destroy', $abastecimento) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Deletar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Nenhum abastecimento encontrado para os filtros selecionados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Paginação que mantém os filtros --}}
            <div class="mt-4">
                {{ $abastecimentos->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
