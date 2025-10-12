<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center w-full">
            <h2 class="header-title text-xl mb-4 sm:mb-0">Cadastro de Fornecedores</h2>
            <a href="{{ route('fornecedores.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition self-end sm:self-center">
                Novo Fornecedor
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4" role="alert"><p>{{ session('success') }}</p></div>
            @endif
            
            <!-- Filtros de Busca -->
            <form method="GET" action="{{ route('fornecedores.index') }}" class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" name="search" id="search" placeholder="Buscar por nome" value="{{ request('search') }}" class="mt-1 block w-full">
                    </div>
                    <div>
                        <label for="tipo" class="text-sm font-medium text-gray-700">Tipo</label>
                        <select name="tipo" id="tipo" class="mt-1 block w-full">
                            <option value="">Todos</option>
                            <option value="oficina" @selected(request('tipo') == 'oficina')>Oficina Mecânica</option>
                            <option value="posto" @selected(request('tipo') == 'posto')>Posto de Combustível</option>
                            <option value="ambos" @selected(request('tipo') == 'ambos')>Ambos</option>
                            <option value="outro" @selected(request('tipo') == 'outro')>Outro</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full">
                            <option value="">Todos</option>
                            <option value="1" @selected(request('status') == '1')>Ativo</option>
                            <option value="0" @selected(request('status') == '0')>Inativo</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Filtrar</button>
                        <a href="{{ route('fornecedores.index') }}" class="ml-2 w-full sm:w-auto px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition text-center">Limpar</a>
                    </div>
                </div>
            </form>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">Nome Fantasia</th>
                            <th scope="col" class="px-6 py-3">Tipo</th>
                            <th scope="col" class="px-6 py-3">Contato</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fornecedores as $fornecedor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $fornecedor->for_nome_fantasia }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $fornecedor->tipo_formatado }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span>{{ $fornecedor->for_contato_telefone ?? '-' }}</span>
                                    <span class="text-xs text-gray-500">{{ $fornecedor->for_contato_email }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($fornecedor->for_status == '1')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Ativo
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inativo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('fornecedores.edit', $fornecedor) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                <form action="{{ route('fornecedores.destroy', $fornecedor) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este fornecedor?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Nenhum fornecedor encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $fornecedores->links() }}</div>
        </div>
    </div>
</x-app-layout>
