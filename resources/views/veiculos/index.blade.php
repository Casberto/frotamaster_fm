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
        <div class="p-6 text-gray-900">
            
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Placa</th>
                            <th scope="col" class="px-6 py-3">Fabricante/Modelo</th>
                            <th scope="col" class="px-6 py-3">Ano</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($veiculos as $veiculo)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $veiculo->vei_placa }}</td>
                                <td class="px-6 py-4">{{ $veiculo->vei_fabricante }} {{ $veiculo->vei_modelo }}</td>
                                <td class="px-6 py-4">{{ $veiculo->vei_ano_fab }}/{{ $veiculo->vei_ano_mod }}</td>
                                <td class="px-6 py-4">{{ $veiculo->status_texto }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('veiculos.edit', $veiculo->vei_id) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                    <form action="{{ route('veiculos.destroy', $veiculo->vei_id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover este veículo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:underline">Deletar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Nenhum veículo cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $veiculos->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

