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
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">Veículo</th>
                            <th scope="col" class="px-6 py-3">Data</th>
                            <th scope="col" class="px-6 py-3">Custo</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($manutencoes as $manutencao)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $manutencao->veiculo->vei_placa }} - {{ $manutencao->veiculo->vei_modelo }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($manutencao->man_data_inicio)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">R$ {{ number_format($manutencao->man_custo_total, 2, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ ucfirst($manutencao->man_status) }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('manutencoes.edit', $manutencao) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                <form action="{{ route('manutencoes.destroy', $manutencao) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Deletar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Nenhuma manutenção registrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $manutencoes->links() }}</div>
        </div>
    </div>
</x-app-layout>