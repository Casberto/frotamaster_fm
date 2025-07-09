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
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4" role="alert"><p>{{ session('success') }}</p></div>
            @endif
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">Veículo</th>
                            <th scope="col" class="px-6 py-3">Data</th>
                            <th scope="col" class="px-6 py-3">Combustível</th>
                            <th scope="col" class="px-6 py-3">Custo Total</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($abastecimentos as $abastecimento)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $abastecimento->veiculo->placa }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($abastecimento->data_abastecimento)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ ucfirst($abastecimento->tipo_combustivel) }}</td>
                            <td class="px-6 py-4">R$ {{ number_format($abastecimento->custo_total, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('abastecimentos.edit', $abastecimento) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                <form action="{{ route('abastecimentos.destroy', $abastecimento) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Deletar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Nenhum abastecimento registrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $abastecimentos->links() }}</div>
        </div>
    </div>
</x-app-layout>