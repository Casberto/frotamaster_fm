<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">
                Gerenciar Permissões
            </h2>
            <a href="{{ route('admin.permissoes.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Nova Permissão
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">Módulo</th>
                            <th scope="col" class="px-6 py-3">Ação</th>
                            <th scope="col" class="px-6 py-3">Descrição</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permissoes as $permissao)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $permissao->prm_modulo }}</td>
                            <td class="px-6 py-4">{{ $permissao->prm_acao }}</td>
                            <td class="px-6 py-4">{{ $permissao->prm_descricao }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.permissoes.edit', $permissao) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                <form action="{{ route('admin.permissoes.destroy', $permissao) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Deletar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Nenhuma permissão encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $permissoes->links() }}</div>
        </div>
    </div>
</x-app-layout>
