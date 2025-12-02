<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Parâmetros do Sistema') }}
            </h2>
            <a href="{{ route('admin.configuracoes-padrao.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Nova Configuração
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Módulo</th>
                                    <th scope="col" class="px-6 py-3">Chave</th>
                                    <th scope="col" class="px-6 py-3">Valor Padrão</th>
                                    <th scope="col" class="px-6 py-3">Tipo</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Ações</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($configuracoes as $config)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $config->cfp_modulo }}
                                    </th>
                                    <td class="px-6 py-4">{{ $config->cfp_chave }}</td>
                                    <td class="px-6 py-4">{{ Str::limit($config->cfp_valor, 30) }}</td>
                                    <td class="px-6 py-4">{{ ucfirst($config->cfp_tipo) }}</td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <a href="{{ route('admin.configuracoes-padrao.edit', $config) }}" class="font-medium text-blue-600 hover:underline">Editar</a>
                                        <form action="{{ route('admin.configuracoes-padrao.destroy', $config) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:underline">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="5" class="px-6 py-4 text-center">
                                        Nenhuma configuração padrão encontrada.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $configuracoes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

