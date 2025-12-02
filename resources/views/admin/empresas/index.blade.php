<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">
                Gerenciar Empresas
            </h2>
            <a href="{{ route('admin.empresas.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Nova Empresa
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">

            @if (session('credentials'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md mb-4" role="alert">
                    <p class="font-bold">Credenciais do Usuário Master:</p>
                    <p><strong>Email:</strong> {{ session('credentials.email') }}</p>
                    <p><strong>Senha:</strong> {{ session('credentials.password') }}</p>
                </div>
            @endif
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">Nome Fantasia</th>
                            <th scope="col" class="px-6 py-3">CNPJ</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($empresas as $empresa)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $empresa->nome_fantasia }}</td>
                            <td class="px-6 py-4">{{ $empresa->cnpj }}</td>
                            <td class="px-6 py-4">{{ $empresa->email_contato }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.empresas.edit', $empresa) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                <form action="{{ route('admin.empresas.destroy', $empresa) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Deletar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Nenhuma empresa encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $empresas->links() }}</div>
        </div>
    </div>
</x-app-layout>