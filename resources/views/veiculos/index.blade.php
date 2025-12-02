<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Meus Veículos
            </h2>
            <a href="{{ route('veiculos.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Novo Veículo
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">


            {{-- Formulário de Filtros --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                <form action="{{ route('veiculos.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Filtro de Busca --}}
                        <div class="md:col-span-2">
                            <label for="search" class="block font-medium text-sm text-gray-700">Buscar por Placa ou Modelo</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Digite para buscar...">
                        </div>
                        {{-- Filtro de Tipo --}}
                        <div>
                            <label for="tipo" class="block font-medium text-sm text-gray-700">Tipo</label>
                            <select name="tipo" id="tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Todos os Tipos</option>
                                @foreach($tipos as $codigo => $descricao)
                                    <option value="{{ $codigo }}" @selected(request('tipo') == $codigo)>{{ $descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Filtro de Status --}}
                        <div>
                            <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Todos os Status</option>
                                <option value="1" @selected(request('status') == '1')>Ativo</option>
                                <option value="0" @selected(request('status') == '0')>Inativo</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <a href="{{ route('veiculos.index') }}" class="btn-secondary">Limpar</a>
                        <button type="submit" class="btn-primary">Filtrar</button>
                    </div>
                </form>
            </div>

            {{-- Visualização em Tabela (Desktop) --}}
            <div class="hidden md:block relative overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">Placa</th>
                            <th scope="col" class="px-6 py-3">Marca / Modelo</th>
                            <th scope="col" class="px-6 py-3">Ano</th>
                            <th scope="col" class="px-6 py-3">KM Atual</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($veiculos as $veiculo)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $veiculo->vei_placa }}</td>
                            <td class="px-6 py-4">{{ $veiculo->vei_fabricante }} / {{ $veiculo->vei_modelo }}</td>
                            <td class="px-6 py-4">{{ $veiculo->vei_ano_fab }} / {{ $veiculo->vei_ano_mod }}</td>
                            <td class="px-6 py-4">{{ number_format($veiculo->vei_km_atual, 0, ',', '.') }} km</td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $veiculo->vei_status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $veiculo->vei_status == 1 ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('veiculos.edit', $veiculo) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                <form action="{{ route('veiculos.destroy', $veiculo) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Deletar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Nenhum veículo encontrado para os filtros selecionados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Visualização em Cards (Mobile) --}}
            <div class="md:hidden space-y-4">
                @forelse ($veiculos as $veiculo)
                    <div class="bg-white border rounded-lg shadow-sm p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $veiculo->vei_placa }}</h3>
                                <p class="text-sm text-gray-500">{{ $veiculo->vei_fabricante }} / {{ $veiculo->vei_modelo }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $veiculo->vei_status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $veiculo->vei_status == 1 ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                        
                        <div class="space-y-1 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Ano:</span>
                                <span class="font-medium">{{ $veiculo->vei_ano_fab }} / {{ $veiculo->vei_ano_mod }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">KM Atual:</span>
                                <span class="font-medium">{{ number_format($veiculo->vei_km_atual, 0, ',', '.') }} km</span>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-3 border-t">
                            <a href="{{ route('veiculos.edit', $veiculo) }}" class="text-blue-600 font-medium text-sm">Editar</a>
                            <form action="{{ route('veiculos.destroy', $veiculo) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 font-medium text-sm">Deletar</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        Nenhum veículo encontrado.
                    </div>
                @endforelse
            </div>
            {{-- Paginação que mantém os filtros --}}
            <div class="mt-4">
                {{ $veiculos->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
