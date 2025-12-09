<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="header-title text-xl">Motoristas</h2>
            @if(Auth::user()->temPermissao('MOT002'))
            <a href="{{ route('motoristas.create') }}" class="btn-primary">
                Novo Motorista
            </a>
            @endif
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">


            <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                <form action="{{ route('motoristas.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1">
                            <label for="search" class="block font-medium text-sm text-gray-700">Buscar por Nome</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Digite o nome...">
                        </div>
                        <div>
                            <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Todos</option>
                                {{-- Adiciona todas as opções de status --}}
                                @php
                                    $statuses = [
                                        'Ativo', 'Inativo', 'Bloqueado', 'Em treinamento', 'Afastado',
                                        'Aguardando documentação', 'Suspenso', 'Rejeitado', 'Em análise'
                                    ];
                                @endphp
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" @selected(request('status') == $status)>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="btn-primary w-full md:w-auto">Filtrar</button>
                             <a href="{{ route('motoristas.index') }}" class="btn-secondary w-full md:w-auto text-center">Limpar</a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Visualização em Tabela (Desktop) --}}
            <div class="hidden md:block relative overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">Nome</th>
                            <th scope="col" class="px-6 py-3">CPF</th>
                            <th scope="col" class="px-6 py-3">Telefone</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($motoristas as $motorista)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $motorista->mot_nome }}</td>
                            <td class="px-6 py-4">{{ $motorista->mot_cpf }}</td>
                            <td class="px-6 py-4">{{ $motorista->mot_telefone1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($motorista->mot_status)
                                    @case('Ativo')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Ativo
                                        </span>
                                        @break
                                    @case('Inativo')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inativo
                                        </span>
                                        @break
                                    @case('Bloqueado')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 text-red-900">
                                            Bloqueado
                                        </span>
                                        @break
                                    @case('Em treinamento')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Em treinamento
                                        </span>
                                        @break
                                    @case('Afastado')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Afastado
                                        </span>
                                        @break
                                    @case('Aguardando documentação')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-200 text-yellow-900">
                                            Aguardando doc.
                                        </span>
                                        @break
                                    @case('Suspenso')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            Suspenso
                                        </span>
                                        @break
                                    @case('Rejeitado')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800">
                                            Rejeitado
                                        </span>
                                        @break
                                    @case('Em análise')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Em análise
                                        </span>
                                        @break
                                    @default
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $motorista->mot_status }} {{-- Exibe o status caso não mapeado --}}
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(Auth::user()->temPermissao('MOT003'))
                                <a href="{{ route('motoristas.edit', $motorista) }}" class="font-medium text-blue-600 hover:underline mr-3">Editar</a>
                                @endif
                                @if(Auth::user()->temPermissao('MOT004'))
                                <form action="{{ route('motoristas.destroy', $motorista) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:underline">Deletar</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Nenhum motorista encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Visualização em Cards (Mobile) --}}
            <div class="md:hidden space-y-4">
                @forelse ($motoristas as $motorista)
                    <div class="bg-white border rounded-lg shadow-sm p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $motorista->mot_nome }}</h3>
                            </div>
                            @switch($motorista->mot_status)
                                @case('Ativo')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Ativo</span>
                                    @break
                                @case('Inativo')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inativo</span>
                                    @break
                                @case('Bloqueado')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-200 text-red-900">Bloqueado</span>
                                    @break
                                @case('Em treinamento')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Em treinamento</span>
                                    @break
                                @case('Afastado')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Afastado</span>
                                    @break
                                @case('Aguardando documentação')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-900">Aguardando doc.</span>
                                    @break
                                @case('Suspenso')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">Suspenso</span>
                                    @break
                                @case('Rejeitado')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">Rejeitado</span>
                                    @break
                                @case('Em análise')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Em análise</span>
                                    @break
                                @default
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $motorista->mot_status }}</span>
                            @endswitch
                        </div>
                        
                        <div class="space-y-1 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">CPF:</span>
                                <span class="font-medium">{{ $motorista->mot_cpf }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Telefone:</span>
                                <span class="font-medium">{{ $motorista->mot_telefone1 }}</span>
                            </div>
                        </div>

                        <div class="flex justify-end items-center space-x-3 pt-3 border-t">
                            @if(Auth::user()->temPermissao('MOT003'))
                            <a href="{{ route('motoristas.edit', $motorista) }}" class="text-blue-600 font-medium text-sm">Editar</a>
                            @endif
                            @if(Auth::user()->temPermissao('MOT004'))
                            <form action="{{ route('motoristas.destroy', $motorista) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 font-medium text-sm">Deletar</button>
                            </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        Nenhum motorista encontrado.
                    </div>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $motoristas->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
