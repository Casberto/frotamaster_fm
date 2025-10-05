<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Registros de Manutenção</h2>
            <a href="{{ route('manutencoes.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Nova Manutenção
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4" role="alert"><p>{{ session('success') }}</p></div>
            @endif

            {{-- Formulário de Filtros --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                <form action="{{ route('manutencoes.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Filtro de Veículo --}}
                        <div>
                            <label for="veiculo_id" class="block font-medium text-sm text-gray-700">Veículo</label>
                            <select name="veiculo_id" id="veiculo_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos os Veículos</option>
                                @foreach($veiculos as $veiculo)
                                    <option value="{{ $veiculo->vei_id }}" @selected(request('veiculo_id') == $veiculo->vei_id)>
                                        {{ $veiculo->vei_placa }} - {{ $veiculo->vei_modelo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Filtro de Status --}}
                        <div>
                            <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos os Status</option>
                                <option value="agendada" @selected(request('status') == 'agendada')>Agendada</option>
                                <option value="em_andamento" @selected(request('status') == 'em_andamento')>Em Andamento</option>
                                <option value="concluida" @selected(request('status') == 'concluida')>Concluída</option>
                                <option value="cancelada" @selected(request('status') == 'cancelada')>Cancelada</option>
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
                        <a href="{{ route('manutencoes.index') }}" class="btn-secondary">Limpar</a>
                        <button type="submit" class="btn-primary">Filtrar</button>
                    </div>
                </form>
            </div>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">Veículo</th>
                            <th scope="col" class="px-6 py-3">Serviços</th>
                            <th scope="col" class="px-6 py-3">Data</th>
                            <th scope="col" class="px-6 py-3">Custo</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($manutencoes as $manutencao)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $manutencao->veiculo->vei_placa ?? 'Veículo não encontrado' }}</td>
                            <td class="px-6 py-4">
                                @forelse($manutencao->servicos as $servico)
                                    <span class="block">{{ $servico->ser_nome }}</span>
                                @empty
                                    <span>N/A</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($manutencao->man_data_inicio)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">R$ {{ number_format($manutencao->man_custo_total, 2, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ ucfirst(str_replace('_', ' de ', $manutencao->man_status)) }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('manutencoes.edit', $manutencao) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                <form action="{{ route('manutencoes.destroy', $manutencao) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Deletar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Nenhuma manutenção encontrada para os filtros selecionados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Paginação que mantém os filtros --}}
            <div class="mt-4">
                {{ $manutencoes->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

