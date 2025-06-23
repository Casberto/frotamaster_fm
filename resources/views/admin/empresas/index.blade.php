<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Gerenciar Empresas
            </h2>
            <a href="{{ route('admin.empresas.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Nova Empresa
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Mensagem de sucesso --}}
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded-md mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Exibir credenciais do usuário master recém-criado --}}
                    @if (session('credentials'))
                        <div class="bg-blue-200 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md mb-4">
                            <p class="font-bold">Credenciais do Usuário Master:</p>
                            <p><strong>Email:</strong> {{ session('credentials.email') }}</p>
                            <p><strong>Senha:</strong> {{ session('credentials.password') }}</p>
                            <p class="mt-2 text-sm">Por favor, anote e envie estas credenciais para o responsável da empresa.</p>
                        </div>
                    @endif
                    
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nome Fantasia</th>
                                    <th scope="col" class="px-6 py-3">CNPJ</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($empresas as $empresa)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $empresa->nome_fantasia }}
                                    </td>
                                    <td class="px-6 py-4">{{ $empresa->cnpj }}</td>
                                    <td class="px-6 py-4">{{ $empresa->email_contato }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.empresas.edit', $empresa) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">Editar</a>
                                        <form action="{{ route('admin.empresas.destroy', $empresa) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover esta empresa? Todos os dados vinculados (usuários, veículos, etc) serão perdidos permanentemente.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Deletar</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">Nenhuma empresa encontrada.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $empresas->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
