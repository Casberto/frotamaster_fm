<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Agendamentos
            </h2>
            <a href="{{ route('reservas.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Novo Agendamento
            </a>
        </div>
    </x-slot>

    {{-- x-data agora armazena a reserva a ser excluída/cancelada --}}
    <div class="" x-data="{ reservaParaAcao: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

             <!-- Mensagens de Sucesso -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Mensagens de Erro -->
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- NOVO: Formulário de Filtros -->
            <div class="mb-6 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                <form action="{{ route('reservas.index') }}" method="GET" x-data="{
                    veiculo_id: '{{ request('veiculo_id') }}',
                    motorista_id: '{{ request('motorista_id') }}',
                    status: '{{ request('status') }}',
                    data_inicio: '{{ request('data_inicio') }}',
                    data_fim: '{{ request('data_fim') }}',
                    limparFiltros() {
                        this.veiculo_id = '';
                        this.motorista_id = '';
                        this.status = '';
                        this.data_inicio = '';
                        this.data_fim = '';
                        $nextTick(() => {
                            this.$refs.formFiltro.submit();
                        });
                    }
                }" x-ref="formFiltro">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        
                        {{-- Filtro de Veículo --}}
                        <div>
                            <x-input-label for="veiculo_id" value="Veículo" />
                            <select name="veiculo_id" id="veiculo_id" x-model="veiculo_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">Todos os Veículos</option>
                                @foreach($veiculos as $veiculo)
                                    <option value="{{ $veiculo->vei_id }}">
                                        {{ $veiculo->vei_placa }} - {{ $veiculo->vei_modelo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filtro de Motorista --}}
                        <div>
                            <x-input-label for="motorista_id" value="Motorista" />
                            <select name="motorista_id" id="motorista_id" x-model="motorista_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">Todos os Motoristas</option>
                                @foreach($motoristas as $motorista)
                                    <option value="{{ $motorista->mot_id }}">
                                        {{ $motorista->mot_nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Filtro de Status --}}
                        <div>
                            <x-input-label for="status" value="Status" />
                            <select name="status" id="status" x-model="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">Todos os Status</option>
                                @foreach($statuse as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Filtro de Data Início --}}
                        <div>
                            <x-input-label for="data_inicio" value="Data Início De" />
                            <x-text-input type="date" name="data_inicio" id="data_inicio" x-model="data_inicio" class="mt-1 block w-full text-sm" />
                        </div>
                        
                        {{-- Filtro de Data Fim --}}
                        <div>
                            <x-input-label for="data_fim" value="Data Fim Até" />
                            <x-text-input type="date" name="data_fim" id="data_fim" x-model="data_fim" class="mt-1 block w-full text-sm" />
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <x-secondary-button type="button" @click.prevent="limparFiltros()">
                            Limpar
                        </x-secondary-button>
                        <x-primary-button type="submit">
                            Filtrar
                        </x-primary-button>
                    </div>
                </form>
            </div>


            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- NOVA: Visualização Mobile (Cards) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($reservas as $reserva)
                            <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                                {{-- Cabeçalho do Card --}}
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $reserva->veiculo->vei_placa ?? 'N/D' }}</p>
                                        <p class="text-sm text-gray-600">{{ $reserva->veiculo->vei_modelo ?? 'N/D' }}</p>
                                    </div>
                                    <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                                 {{ $reserva->res_status == 'pendente' ? 'bg-yellow-100 text-yellow-800' :
                                                    ($reserva->res_status == 'aprovada' ? 'bg-green-100 text-green-800' :
                                                    ($reserva->res_status == 'em_uso' ? 'bg-blue-100 text-blue-800' :
                                                    ($reserva->res_status == 'rejeitada' ? 'bg-red-100 text-red-800' :
                                                    ($reserva->res_status == 'em_revisao' ? 'bg-purple-100 text-purple-800' :
                                                    ($reserva->res_status == 'encerrada' ? 'bg-gray-100 text-gray-800' :
                                                    ($reserva->res_status == 'cancelada' ? 'bg-gray-400 text-gray-800' :
                                                    ($reserva->res_status == 'pendente_ajuste' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800'))))))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $reserva->res_status)) }}
                                    </span>
                                </div>
                                
                                {{-- Corpo do Card --}}
                                <div class="space-y-2 text-sm">
                                    <p><strong class="text-gray-600">Tipo:</strong> {{ ucfirst($reserva->res_tipo) }}</p>
                                    <p><strong class="text-gray-600">Motorista:</strong> {{ $reserva->motorista->mot_nome ?? ($reserva->solicitante->name ?? 'N/D') }}</p>
                                    <p><strong class="text-gray-600">Período:</strong> {{ \Carbon\Carbon::parse($reserva->res_data_inicio)->format('d/m H:i') }}
                                        <span class="text-xs">até</span>
                                        {{ \Carbon\Carbon::parse($reserva->res_data_fim)->format('d/m H:i') }}
                                    </p>
                                </div>

                                {{-- Ações do Card --}}
                                <div class="mt-4 pt-3 border-t flex items-center justify-end space-x-3">
                                    <a href="{{ route('reservas.show', $reserva) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Ver</a>

                                    @if(in_array($reserva->res_status, ['pendente', 'rejeitada', 'pendente_ajuste']))
                                        <a href="{{ route('reservas.edit', $reserva) }}" class="text-sm font-medium text-blue-600 hover:text-blue-900">
                                            {{ $reserva->res_status == 'pendente_ajuste' ? 'Ajustar' : 'Editar' }}
                                        </a>
                                    @endif

                                    @if(in_array($reserva->res_status, ['pendente', 'aprovada']))
                                        <button type="button" class="text-sm font-medium text-yellow-600 hover:text-yellow-900"
                                                @click="reservaParaAcao = {{ $reserva->res_id }}; $dispatch('open-modal', 'modal-cancelar-index').window">
                                            Cancelar
                                        </button>
                                    @endif

                                    @if(in_array($reserva->res_status, ['pendente', 'rejeitada', 'cancelada']))
                                        <button type="button" class="text-sm font-medium text-red-600 hover:text-red-900"
                                                @click="reservaParaAcao = {{ $reserva->res_id }}; $dispatch('open-modal', 'modal-excluir-index').window">
                                            Excluir
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                             <div class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Nenhuma reserva encontrada.
                            </div>
                        @endforelse
                    </div>

                    {{-- Tabela Desktop (Oculta em mobile) --}}
                    <div class="relative overflow-x-auto hidden md:block">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Veículo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motorista/Solicitante</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($reservas as $reserva)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                         {{ $reserva->res_status == 'pendente' ? 'bg-yellow-100 text-yellow-800' :
                                                            ($reserva->res_status == 'aprovada' ? 'bg-green-100 text-green-800' :
                                                            ($reserva->res_status == 'em_uso' ? 'bg-blue-100 text-blue-800' :
                                                            ($reserva->res_status == 'rejeitada' ? 'bg-red-100 text-red-800' :
                                                            ($reserva->res_status == 'em_revisao' ? 'bg-purple-100 text-purple-800' :
                                                            ($reserva->res_status == 'encerrada' ? 'bg-gray-100 text-gray-800' :
                                                            ($reserva->res_status == 'cancelada' ? 'bg-gray-400 text-gray-800' :
                                                            ($reserva->res_status == 'pendente_ajuste' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800'))))))) }}">
                                                {{ ucfirst(str_replace('_', ' ', $reserva->res_status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $reserva->veiculo->vei_placa ?? 'N/D' }}</div>
                                            <div class="text-sm text-gray-500">{{ $reserva->veiculo->vei_modelo ?? 'N/D' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($reserva->res_tipo) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($reserva->res_data_inicio)->format('d/m/y H:i') }}
                                            <span class="text-xs">até</span>
                                            {{ \Carbon\Carbon::parse($reserva->res_data_fim)->format('d/m/y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $reserva->motorista->mot_nome ?? ($reserva->solicitante->name ?? 'N/D') }}</div>
                                            <div class="text-sm text-gray-500">{{ $reserva->res_tipo == 'viagem' ? 'Motorista' : 'Solicitante' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('reservas.show', $reserva) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>

                                            {{-- Botão Ajustar/Editar --}}
                                            @if(in_array($reserva->res_status, ['pendente', 'rejeitada', 'pendente_ajuste']))
                                                <a href="{{ route('reservas.edit', $reserva) }}" class="text-blue-600 hover:text-blue-900">
                                                    {{ $reserva->res_status == 'pendente_ajuste' ? 'Ajustar' : 'Editar' }}
                                                </a>
                                            @endif

                                            {{-- Botão Cancelar (com Modal) --}}
                                            @if(in_array($reserva->res_status, ['pendente', 'aprovada']))
                                                <button type="button" class="text-yellow-600 hover:text-yellow-900"
                                                        @click="reservaParaAcao = {{ $reserva->res_id }}; $dispatch('open-modal', 'modal-cancelar-index').window">
                                                    Cancelar
                                                </button>
                                            @endif

                                            {{-- Botão Excluir (com Modal) --}}
                                            @if(in_array($reserva->res_status, ['pendente', 'rejeitada', 'cancelada']))
                                                <button type="button" class="text-red-600 hover:text-red-900"
                                                        @click="reservaParaAcao = {{ $reserva->res_id }}; $dispatch('open-modal', 'modal-excluir-index').window">
                                                    Excluir
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Nenhuma reserva encontrada para os filtros selecionados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $reservas->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAIS DE AÇÃO DA LISTAGEM --}}
        
        <!-- Modal: Cancelar Reserva (Index) -->
        <x-modal name="modal-cancelar-index" maxWidth="lg">
            <form method="post" :action="`{{ url('reservas') }}/${reservaParaAcao}/cancelar`" class="p-6 bg-white rounded-lg">
                @csrf
                <h2 class="text-lg font-medium text-gray-900">Cancelar Reserva</h2>
                <p class="mt-2 text-sm text-gray-600">Tem certeza que deseja cancelar esta reserva? Esta ação não pode ser desfeita.</p>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button type="button" @click="$dispatch('close')">Manter Reserva</x-secondary-button>
                    <x-danger-button class="ml-3 bg-yellow-500 hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:ring-yellow-500">Sim, Cancelar Reserva</x-danger-button>
                </div>
            </form>
        </x-modal>

        <!-- Modal: Excluir Reserva (Index) -->
        <x-modal name="modal-excluir-index" maxWidth="lg">
            <form method="post" :action="`{{ url('reservas') }}/${reservaParaAcao}`" class="p-6 bg-white rounded-lg">
                @csrf
                @method('DELETE')
                <h2 class="text-lg font-medium text-gray-900">Excluir Reserva</h2>
                <p class="mt-2 text-sm text-gray-600">Tem certeza que deseja excluir esta reserva permanentemente? Esta ação não pode ser desfeita.</p>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button type="button" @click="$dispatch('close')">Manter Reserva</x-secondary-button>
                    <x-danger-button class="ml-3">Sim, Excluir</x-danger-button>
                </div>
            </form>
        </x-modal>

    </div>
</x-app-layout>