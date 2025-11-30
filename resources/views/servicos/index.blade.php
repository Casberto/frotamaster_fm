<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Catálogo de Serviços</h2>
            <a href="{{ route('servicos.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Novo Serviço
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4" role="alert"><p>{{ session('success') }}</p></div>
            @endif
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                <form action="{{ route('servicos.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label for="search" class="block font-medium text-sm text-gray-700">Buscar por Nome</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Digite o nome do serviço...">
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition w-full md:w-auto">Filtrar</button>
                            <a href="{{ route('servicos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition w-full md:w-auto text-center">Limpar</a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Visualização em Tabela (Desktop) --}}
            <div class="hidden md:block relative overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">Código do Serviço</th>
                            <th scope="col" class="px-6 py-3">Descrição</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($servicos as $servico)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $servico->ser_nome }}</td>
                            <td class="px-6 py-4">{{ $servico->ser_descricao ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('servicos.edit', $servico) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                <form action="{{ route('servicos.destroy', $servico) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Deletar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">Nenhum serviço cadastrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Visualização em Cards (Mobile) --}}
            <div class="md:hidden space-y-4">
                @forelse ($servicos as $servico)
                    <div class="bg-white border rounded-lg shadow-sm p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $servico->ser_nome }}</h3>
                            </div>
                        </div>
                        
                        <div class="space-y-1 mb-4">
                            <div class="flex flex-col text-sm">
                                <span class="text-gray-500">Descrição:</span>
                                <span class="font-medium">{{ $servico->ser_descricao ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="flex justify-end items-center space-x-3 pt-3 border-t">
                            <a href="{{ route('servicos.edit', $servico) }}" class="text-blue-600 font-medium text-sm">Editar</a>
                            <form action="{{ route('servicos.destroy', $servico) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 font-medium text-sm">Deletar</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        Nenhum serviço encontrado.
                    </div>
                @endforelse
            </div>
            <div class="mt-4">{{ $servicos->appends(request()->query())->links() }}</div>
        </div>
    </div>
</x-app-layout>
