<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Gerenciar Licenças</h2>
            <a href="{{ route('admin.licencas.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Nova Licença
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">Empresa</th>
                            <th scope="col" class="px-6 py-3">Plano</th>
                            <th scope="col" class="px-6 py-3">Vencimento</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($licencas as $licenca)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $licenca->empresa->nome_fantasia }}</td>
                            <td class="px-6 py-4">{{ $licenca->plano }}</td>
                            <td class="px-6 py-4">{{ $licenca->data_vencimento->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($licenca->status == 'ativo') bg-green-100 text-green-800 @endif
                                    @if($licenca->status == 'expirado') bg-red-100 text-red-800 @endif
                                    @if($licenca->status == 'pendente') bg-yellow-100 text-yellow-800 @endif
                                    @if($licenca->status == 'cancelado') bg-gray-100 text-gray-800 @endif
                                ">
                                    {{ ucfirst($licenca->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.licencas.edit', $licenca) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Nenhuma licença encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $licencas->links() }}</div>
        </div>
    </div>
</x-app-layout>