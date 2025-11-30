<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Gerenciar Usuários</h2>
            <a href="{{ route('usuarios.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Novo Usuário
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- Formulário de Filtros --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                <form action="{{ route('usuarios.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Filtro de Nome --}}
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Nome</label>
                            <input type="text" name="name" id="name" value="{{ request('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Buscar por nome...">
                        </div>
                        {{-- Filtro de Email --}}
                        <div>
                            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                            <input type="text" name="email" id="email" value="{{ request('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Buscar por email...">
                        </div>
                        {{-- Filtro de Perfil --}}
                        <div>
                            <label for="role" class="block font-medium text-sm text-gray-700">Perfil</label>
                            <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos os Perfis</option>
                                <option value="master" @selected(request('role') == 'master')>Master</option>
                                <option value="usuario" @selected(request('role') == 'usuario')>Usuário</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <a href="{{ route('usuarios.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Limpar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Filtrar</button>
                    </div>
                </form>
            </div>

            {{-- Visualização em Tabela (Desktop) --}}
            <div class="hidden md:block relative overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">Nome</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Perfil</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $usuario->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $usuario->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $usuario->role == 'master' ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($usuario->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Nenhum usuário encontrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Visualização em Cards (Mobile) --}}
            <div class="md:hidden space-y-4">
                @forelse ($usuarios as $usuario)
                    <div class="bg-white border rounded-lg shadow-sm p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $usuario->name }}</h3>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $usuario->role == 'master' ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($usuario->role) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="space-y-1 mb-4">
                            <div class="flex flex-col text-sm">
                                <span class="text-gray-500">Email:</span>
                                <span class="font-medium break-all">{{ $usuario->email }}</span>
                            </div>
                        </div>

                        <div class="flex justify-end items-center space-x-3 pt-3 border-t">
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="text-blue-600 font-medium text-sm">Editar</a>
                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 font-medium text-sm">Excluir</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        Nenhum usuário encontrado.
                    </div>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $usuarios->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

