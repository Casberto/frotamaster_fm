<x-app-layout>
    <x-slot name="header">
        <h2 class="header-title text-xl">Logs de Atividade</h2>
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
        <h3 class="form-section-title">Filtrar Registros</h3>
        <form method="GET" action="{{ route('logs.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <!-- Campos de Filtro: Data Início, Data Fim, Usuário, Tela, Ação -->
                <div>
                    <label for="data_inicio">Data Início</label>
                    <input type="date" name="data_inicio" id="data_inicio" class="mt-1 block w-full" value="{{ request('data_inicio') }}">
                </div>
                <div>
                    <label for="data_fim">Data Fim</label>
                    <input type="date" name="data_fim" id="data_fim" class="mt-1 block w-full" value="{{ request('data_fim') }}">
                </div>
                <div>
                    <label for="user_id">Usuário</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full">
                        <option value="">Todos</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" @selected(request('user_id') == $usuario->id)>{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tela">Tela</label>
                    <select name="tela" id="tela" class="mt-1 block w-full">
                        <option value="">Todas</option>
                        <option value="Veículos" @selected(request('tela') == 'Veículos')>Veículos</option>
                        <option value="Manutenções" @selected(request('tela') == 'Manutenções')>Manutenções</option>
                        <option value="Abastecimentos" @selected(request('tela') == 'Abastecimentos')>Abastecimentos</option>
                        <option value="Veículos (via Abastecimento)" @selected(request('tela') == 'Veículos (via Abastecimento)')>Veículo (via Abastecimento)</option>
                        <option value="Veículos (via Manutenção)" @selected(request('tela') == 'Veículos (via Manutenção)')>Veículo (via Manutenção)</option>
                        @if(Auth::user()->role === 'super-admin')
                        <option value="Empresas" @selected(request('tela') == 'Empresas')>Empresas</option>
                        @endif
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Filtrar</button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3">Data/Hora</th>
                            <th scope="col" class="px-6 py-3">Usuário</th>
                            <th scope="col" class="px-6 py-3">Tela</th>
                            <th scope="col" class="px-6 py-3">Ação</th>
                            <th scope="col" class="px-6 py-3">Registro Afetado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="px-6 py-4">{{ $log->user_name }}</td>
                            <td class="px-6 py-4">{{ $log->tela }}</td>
                            <td class="px-6 py-4">{{ ucfirst($log->acao) }}</td>
                            <td class="px-6 py-4">{{ $log->registro_string }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Nenhum log encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $logs->links() }}</div>
        </div>
    </div>
</x-app-layout>