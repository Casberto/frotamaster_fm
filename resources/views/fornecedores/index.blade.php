<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Cadastro de Fornecedores</h2>
            <a href="{{ route('fornecedores.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Novo Fornecedor
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
                            <th scope="col" class="px-6 py-3">Nome Fantasia</th>
                            <th scope="col" class="px-6 py-3">Telefone</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fornecedores as $fornecedor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $fornecedor->for_nome_fantasia }}</td>
                            <td class="px-6 py-4">{{ $fornecedor->for_contato_telefone ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $fornecedor->for_contato_email ?? '-' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('fornecedores.edit', $fornecedor) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                <form action="{{ route('fornecedores.destroy', $fornecedor) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Deletar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Nenhum fornecedor cadastrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $fornecedores->links() }}</div>
        </div>
    </div>
</x-app-layout>
