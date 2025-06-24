<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">
                Meus Veículos
            </h2>
            <a href="{{ route('veiculos.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Novo Veículo
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
                            <th scope="col" class="px-6 py-3">Placa</th>
                            <th scope="col" class="px-6 py-3">Marca/Modelo</th>
                            <th scope="col" class="px-6 py-3">Ano</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($veiculos as $veiculo)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $veiculo->placa }}</td>
                            <td class="px-6 py-4">{{ $veiculo->marca }} {{ $veiculo->modelo }}</td>
                            <td class="px-6 py-4">{{ $veiculo->ano_fabricacao }}/{{ $veiculo->ano_modelo }}</td>
                            <td class="px-6 py-4">{{ ucfirst($veiculo->status) }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('veiculos.edit', $veiculo) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                <form action="{{ route('veiculos.destroy', $veiculo) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Deletar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Nenhum veículo cadastrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $veiculos->links() }}</div>
        </div>
    </div>
</x-app-layout>