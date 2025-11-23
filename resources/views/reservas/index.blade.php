<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">
                Reservas
            </h2>
            
            @if(Auth::user()->temPermissaoId(34)) {{-- Permissão Criar --}}
            <a href="{{ route('reservas.create') }}" 
               class="shrink-0 inline-flex items-center justify-center px-3 py-2 md:px-4 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs md:text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 md:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                {{-- Texto visível apenas em Desktop --}}
                <span class="hidden md:inline">Nova Reserva</span>
                {{-- Texto visível apenas em Mobile --}}
                <span class="md:hidden">&nbsp Novo</span>
            </a>
            @endif
        </div>
    </x-slot>

    {{-- Wrapper Principal Ajustado para Ocupar Altura Total --}}
    {{-- 
        Ajuste de Altura:
        - Desktop: 100vh - (Header ~75px + Padding Top ~32px) = ~110px de desconto.
        - Mobile: 100vh - (Header ~70px + Padding Top ~24px) = ~95px de desconto.
        - Margens Negativas (-mb-6 e -mb-8): Removem o padding inferior padrão do layout para encostar no fundo.
    --}}
    <div class="h-[calc(100vh-100px)] md:h-[calc(100vh-115px)] flex flex-col md:flex-row max-w-[1920px] mx-auto -mb-6 md:-mb-8">
        
        {{-- COLUNA ESQUERDA (LISTA) --}}
        <div class="w-full md:w-2/5 lg:w-1/3 flex flex-col border-r border-gray-200 bg-gray-50 h-full">
            
            {{-- Área de Filtros --}}
            <div class="p-4 border-b border-gray-200 bg-white">
                 <form action="{{ route('reservas.index') }}" method="GET" x-data="{
                    veiculo_id: '{{ request('veiculo_id') }}',
                    status: '{{ request('status') }}',
                    motorista_id: '{{ request('motorista_id') }}',
                    data_inicio: '{{ request('data_inicio') }}',
                    submit() { $refs.form.submit(); }
                }" x-ref="form">
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <select name="veiculo_id" x-model="veiculo_id" @change="submit()" class="w-full rounded-md border-gray-300 text-xs py-1.5">
                            <option value="">Todos os Veículos</option>
                            @foreach($veiculos as $v) <option value="{{ $v->vei_id }}">{{ $v->vei_placa }} - {{ $v->vei_modelo }}</option> @endforeach
                        </select>
                        
                        <select name="status" x-model="status" @change="submit()" class="w-full rounded-md border-gray-300 text-xs py-1.5">
                            <option value="">Todos os Status</option>
                            @foreach($statuse as $key => $val) <option value="{{ $key }}">{{ $val }}</option> @endforeach
                        </select>
                    </div>
                    
                    {{-- Filtros Avançados --}}
                    <div x-data="{ expanded: false }">
                        <button type="button" @click="expanded = !expanded" class="text-xs text-blue-600 hover:text-blue-800 flex items-center">
                            <span x-text="expanded ? 'Menos filtros' : 'Mais filtros'"></span>
                            <svg class="w-3 h-3 ml-1 transform transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="expanded" x-collapse class="mt-2 space-y-2">
                             <select name="motorista_id" x-model="motorista_id" @change="submit()" class="w-full rounded-md border-gray-300 text-xs py-1.5">
                                <option value="">Todos os Motoristas</option>
                                @foreach($motoristas as $m) <option value="{{ $m->mot_id }}">{{ $m->mot_nome }}</option> @endforeach
                            </select>
                             <input type="date" name="data_inicio" x-model="data_inicio" @change="submit()" class="w-full rounded-md border-gray-300 text-xs py-1.5">
                             <a href="{{ route('reservas.index') }}" class="block text-center text-xs text-gray-500 hover:text-gray-800 mt-2 border border-gray-300 rounded py-1">Limpar Filtros</a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Lista de Reservas --}}
            <div class="flex-1 overflow-y-auto p-2 md:p-0 space-y-2 md:space-y-0">
                @forelse ($reservas as $reservaItem)
                    @php
                        $isActive = (request('selected_id') == $reservaItem->res_id) || (isset($selectedReserva) && $selectedReserva->res_id == $reservaItem->res_id);
                        
                        $statusColor = match($reservaItem->res_status) {
                            'pendente' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'aprovada' => 'bg-green-100 text-green-800 border-green-200',
                            'em_uso' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'rejeitada' => 'bg-red-100 text-red-800 border-red-200',
                            'encerrada', 'cancelada' => 'bg-gray-100 text-gray-600 border-gray-200',
                            'em_revisao' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'pendente_ajuste' => 'bg-orange-100 text-orange-800 border-orange-200',
                            default => 'bg-gray-100 text-gray-800'
                        };
                    @endphp

                    {{-- Card Desktop (Link de Seleção) --}}
                    <a href="{{ route('reservas.index', array_merge(request()->query(), ['selected_id' => $reservaItem->res_id])) }}" 
                       class="block p-4 border-b border-gray-100 cursor-pointer transition-colors hover:bg-gray-50
                              {{ $isActive ? 'bg-blue-50 border-l-4 border-l-blue-500' : 'bg-white md:hover:bg-gray-50' }}
                              md:block hidden">
                        
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                    <span class="text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">#{{ $reservaItem->res_codigo }}</span>
                                    {{ $reservaItem->veiculo->vei_placa ?? 'A DEFINIR' }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $reservaItem->veiculo->vei_modelo ?? 'Modelo não informado' }}
                                </div>
                            </div>
                            <span class="text-xs font-mono text-gray-500">
                                {{ \Carbon\Carbon::parse($reservaItem->res_data_inicio)->format('H:i') }}
                            </span>
                        </div>
                        
                        <div class="mt-2 flex justify-between items-center">
                            <div class="text-xs text-gray-600">
                                {{ ucfirst($reservaItem->res_tipo) }}
                            </div>
                             <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full border {{ $statusColor }}">
                                {{ ucfirst(str_replace('_', ' ', $reservaItem->res_status)) }}
                            </span>
                        </div>
                    </a>

                    {{-- Card Mobile (Link direto para Show) --}}
                    <div class="md:hidden bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded mr-2">#{{ $reservaItem->res_codigo }}</span>
                                <span class="font-bold text-gray-800">{{ $reservaItem->veiculo->vei_placa ?? 'A DEFINIR' }}</span>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $statusColor }}">
                                {{ ucfirst($reservaItem->res_status) }}
                            </span>
                        </div>
                        <div class="mt-2 text-sm text-gray-600 space-y-1">
                             <p class="text-xs">{{ $reservaItem->veiculo->vei_modelo ?? '' }}</p>
                             <p><strong>Motorista:</strong> {{ $reservaItem->motorista->mot_nome ?? ($reservaItem->solicitante->name ?? '-') }}</p>
                            <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($reservaItem->res_data_inicio)->format('d/m H:i') }} até {{ \Carbon\Carbon::parse($reservaItem->res_data_fim)->format('d/m H:i') }}</p>
                        </div>
                        <div class="mt-3 text-right border-t pt-2">
                            <a href="{{ route('reservas.show', $reservaItem) }}" class="text-sm font-bold text-blue-600 hover:text-blue-800">
                                Acessar Detalhes →
                            </a>
                        </div>
                    </div>

                @empty
                    <div class="p-8 text-center text-gray-500 text-sm">
                        Nenhuma reserva encontrada.
                    </div>
                @endforelse
                
                <div class="p-4">
                    {{ $reservas->links() }}
                </div>
            </div>
        </div>

        {{-- COLUNA DIREITA (DETALHES - DESKTOP) --}}
        <div class="hidden md:flex md:w-3/5 lg:w-2/3 bg-gray-100 flex-col h-full overflow-hidden relative">
            
            @if($selectedReserva)
                {{-- Área de rolagem dos detalhes --}}
                <div class="flex-1 overflow-y-auto p-6 pb-24" x-data="{ tab: 'detalhes' }">
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        
                        {{-- Cabeçalho Interno --}}
                        <div class="flex justify-between items-start mb-6 border-b pb-4">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <h2 class="text-2xl font-bold text-gray-900">{{ $selectedReserva->solicitante->name }}</h2>
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-md font-mono">#{{ $selectedReserva->res_codigo }}</span>
                                </div>
                                <p class="text-sm text-gray-500">Solicitante • Criado em {{ $selectedReserva->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            {{-- Status Grande --}}
                            @php
                                $statusColorBig = match($selectedReserva->res_status) {
                                    'pendente' => 'bg-yellow-100 text-yellow-800',
                                    'aprovada' => 'bg-green-100 text-green-800',
                                    'em_uso' => 'bg-blue-100 text-blue-800',
                                    'rejeitada' => 'bg-red-100 text-red-800',
                                    'em_revisao' => 'bg-purple-100 text-purple-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="px-4 py-2 rounded-lg font-bold text-sm {{ $statusColorBig }}">
                                {{ ucfirst(str_replace('_', ' ', $selectedReserva->res_status)) }}
                            </span>
                        </div>

                        {{-- Informações Rápidas --}}
                        <div class="mb-8">
                             <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2">
                                {{ $selectedReserva->veiculo->vei_placa ?? 'A DEFINIR' }}
                                @if($selectedReserva->veiculo)
                                    <span class="font-normal text-gray-600 text-sm">({{ $selectedReserva->veiculo->vei_modelo }})</span>
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                <span class="font-medium">Período:</span> 
                                {{ \Carbon\Carbon::parse($selectedReserva->res_data_inicio)->format('d/m/Y H:i') }} 
                                até 
                                {{ \Carbon\Carbon::parse($selectedReserva->res_data_fim)->format('d/m/Y H:i') }}
                                @if($selectedReserva->res_dia_todo) <span class="text-blue-600 text-xs font-bold ml-1">(Dia Todo)</span> @endif
                            </p>
                        </div>

                        {{-- Barra de Progresso Visual --}}
                        <div class="relative mt-4 mb-10 px-2">
                            <div class="flex items-center justify-between text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                                <span class="{{ in_array($selectedReserva->res_status, ['pendente', 'rejeitada']) ? 'text-blue-600' : '' }}">Solicitado</span>
                                <span class="{{ $selectedReserva->res_status == 'aprovada' ? 'text-blue-600' : '' }}">Aprovado</span>
                                <span class="{{ $selectedReserva->res_status == 'em_uso' ? 'text-blue-600' : '' }}">Em Andamento</span>
                                <span class="{{ in_array($selectedReserva->res_status, ['em_revisao', 'encerrada']) ? 'text-blue-600' : '' }}">Concluído</span>
                            </div>
                            <div class="overflow-hidden h-1.5 text-xs flex rounded bg-gray-100">
                                @php
                                    $width = match($selectedReserva->res_status) {
                                        'pendente', 'rejeitada', 'cancelada' => '25%',
                                        'aprovada' => '50%',
                                        'em_uso' => '75%',
                                        'em_revisao', 'encerrada' => '100%',
                                        default => '5%'
                                    };
                                    $barColor = in_array($selectedReserva->res_status, ['rejeitada', 'cancelada']) ? 'bg-red-500' : 'bg-blue-500';
                                @endphp
                                <div style="width: {{ $width }}" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $barColor }} transition-all duration-500"></div>
                            </div>
                        </div>

                        {{-- Navegação Interna --}}
                        <div class="border-b border-gray-200 mb-6">
                            <nav class="-mb-px flex space-x-8">
                                <button @click="tab = 'detalhes'" :class="tab === 'detalhes' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                    Detalhes
                                </button>
                                <button @click="tab = 'registros'" :class="tab === 'registros' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                    Histórico / Registros
                                </button>
                            </nav>
                        </div>

                        <div x-show="tab === 'detalhes'" x-transition:enter.opacity.duration.300ms>
                            @include('reservas.partials._show-details', ['reserva' => $selectedReserva])
                        </div>

                        <div x-show="tab === 'registros'" style="display: none;" x-transition:enter.opacity.duration.300ms>
                            @include('reservas.partials._show-summary', ['reserva' => $selectedReserva])
                            @include('reservas.partials._show-registros', ['reserva' => $selectedReserva])
                        </div>
                    </div>
                </div>

                {{-- Barra de Ações Sticky (Corrigido sobreposição) --}}
                <div class="absolute bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
                    @include('reservas.partials._show-actions-footer', ['reserva' => $selectedReserva])
                </div>

                {{-- Inclusão dos Modais (Uma vez por ciclo, usando o selectedReserva) --}}
                @include('reservas.partials._show-modals', ['reserva' => $selectedReserva])

            @else
                {{-- Estado Vazio --}}
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-24 h-24 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <h3 class="text-lg font-medium text-gray-600">Nenhuma reserva selecionada</h3>
                    <p class="mt-1">Selecione um item da lista à esquerda para visualizar os detalhes e realizar ações.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>