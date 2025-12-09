<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">
                Apólices de Seguro
            </h2>
            @if(Auth::user()->temPermissao('SEG002'))
            <a href="{{ route('seguros.create') }}" 
               class="shrink-0 inline-flex items-center justify-center px-3 py-2 md:px-4 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs md:text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                <span class="hidden md:inline">Nova Apólice</span>
                <span class="md:hidden">&nbsp Novo</span>
            </a>
            @endif
        </div>
    </x-slot>

    {{-- Wrapper Principal Ajustado para Ocupar Altura Total --}}
    <div class="h-[calc(100vh-100px)] md:h-[calc(100vh-115px)] flex flex-col md:flex-row max-w-[1920px] mx-auto -mb-6 md:-mb-8">
        
        {{-- COLUNA ESQUERDA (LISTA) --}}
        <div class="w-full md:w-2/5 lg:w-1/3 flex flex-col border-r border-gray-200 bg-gray-50 h-full">
            
            {{-- Área de Filtros --}}
            <div class="p-4 border-b border-gray-200 bg-white">
                 <form action="{{ route('seguros.index') }}" method="GET" x-data="{
                    submit() { $refs.form.submit(); }
                }" x-ref="form">
                    <div class="grid grid-cols-1 gap-2 mb-2">
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full rounded-md border-gray-300 text-xs py-1.5" placeholder="Buscar (Número, Placa, Seguradora)...">
                        
                        <select name="status" onchange="this.form.submit()" class="w-full rounded-md border-gray-300 text-xs py-1.5">
                            <option value="">Todos os Status</option>
                            <option value="Ativo" @selected(request('status') == 'Ativo')>Ativo</option>
                            <option value="Vencida" @selected(request('status') == 'Vencida')>Vencida</option>
                            <option value="Em renovação" @selected(request('status') == 'Em renovação')>Em renovação</option>
                        </select>
                    </div>
                </form>
            </div>

            {{-- Lista de Apólices --}}
            <div class="flex-1 overflow-y-auto p-2 md:p-0 space-y-2 md:space-y-0">
                @forelse ($apolices as $apoliceItem)
                    @php
                        $isActive = (request('selected_id') == $apoliceItem->seg_id) || (isset($selectedApolice) && $selectedApolice->seg_id == $apoliceItem->seg_id);
                        
                        $statusColor = match($apoliceItem->seg_status) {
                            'Ativo' => 'bg-green-100 text-green-800 border-green-200',
                            'Vencida' => 'bg-red-100 text-red-800 border-red-200',
                            'Em renovação' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            default => 'bg-gray-100 text-gray-800'
                        };
                    @endphp

                    {{-- Card Desktop (Link de Seleção) --}}
                    <a href="{{ route('seguros.index', array_merge(request()->query(), ['selected_id' => $apoliceItem->seg_id])) }}" 
                       class="block p-4 border-b border-gray-100 cursor-pointer transition-colors hover:bg-gray-50
                              {{ $isActive ? 'bg-blue-50 border-l-4 border-l-blue-500' : 'bg-white md:hover:bg-gray-50' }}
                              md:block hidden">
                        
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                    {{ $apoliceItem->seg_numero }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $apoliceItem->veiculo->vei_placa ?? 'Sem Veículo' }} - {{ $apoliceItem->fornecedor->for_nome_fantasia ?? 'N/A' }}
                                </div>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full border {{ $statusColor }}">
                                {{ $apoliceItem->seg_status }}
                            </span>
                        </div>
                        
                        <div class="mt-2 flex justify-between items-center text-xs text-gray-500">
                             <div>
                                Vigência: {{ $apoliceItem->seg_fim ? $apoliceItem->seg_fim->format('d/m/y') : '-' }}
                            </div>
                            <div class="font-medium">
                                R$ {{ number_format($apoliceItem->seg_valor_total, 2, ',', '.') }}
                            </div>
                        </div>
                    </a>

                    {{-- Card Mobile (Link direto para Show - Mantendo comportamento original) --}}
                    <div class="md:hidden bg-white p-4 rounded-lg shadow-sm border border-gray-200" onclick="window.location='{{ route('seguros.show', $apoliceItem->seg_id) }}'">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">{{ $apoliceItem->seg_numero }}</h3>
                                <p class="text-xs text-gray-500">{{ $apoliceItem->fornecedor->for_nome_fantasia ?? 'N/A' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                {{ $apoliceItem->seg_status }}
                            </span>
                        </div>
                        <div class="space-y-1 mb-3">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Veículo:</span>
                                <span class="font-medium">{{ $apoliceItem->veiculo->vei_placa ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Vigência:</span>
                                <span class="font-medium">
                                    {{ $apoliceItem->seg_inicio ? $apoliceItem->seg_inicio->format('d/m/y') : '-' }} - 
                                    {{ $apoliceItem->seg_fim ? $apoliceItem->seg_fim->format('d/m/y') : '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-end pt-2 border-t">
                             <a href="{{ route('seguros.show', $apoliceItem->seg_id) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                                Acessar Detalhes →
                            </a>
                        </div>
                    </div>

                @empty
                    <div class="p-8 text-center text-gray-500 text-sm">
                        Nenhuma apólice encontrada.
                    </div>
                @endforelse
                
                <div class="p-4">
                    {{ $apolices->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

        {{-- COLUNA DIREITA (DETALHES - DESKTOP) --}}
        <div class="hidden md:flex md:w-3/5 lg:w-2/3 bg-gray-100 flex-col h-full overflow-hidden relative">
            
            @if(isset($selectedApolice) && $selectedApolice)
                {{-- Área de rolagem dos detalhes --}}
                <div class="flex-1 overflow-y-auto p-6 pb-24" x-data="{ tab: 'geral' }">
                    
                    {{-- Cabeçalho + Navegação (Merge) --}}
                    <div class="bg-white shadow-sm border border-gray-200 rounded-lg mb-6">
                        <div class="flex justify-between items-start pt-6 px-6 pb-2">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <h2 class="text-2xl font-bold text-gray-900">{{ $selectedApolice->seg_numero }}</h2>
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-md font-mono">{{ $selectedApolice->veiculo->vei_placa ?? 'N/A' }}</span>
                                </div>
                                <p class="text-sm text-gray-500">{{ $selectedApolice->fornecedor->for_nome_fantasia ?? 'Seguradora não informada' }}</p>
                            </div>
                        
                            @php
                                $statusColorBig = match($selectedApolice->seg_status) {
                                    'Ativo' => 'bg-green-100 text-green-800',
                                    'Vencida' => 'bg-red-100 text-red-800',
                                    'Em renovação' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="px-4 py-2 rounded-lg font-bold text-sm {{ $statusColorBig }}">
                                {{ $selectedApolice->seg_status }}
                            </span>
                        </div>

                        {{-- Navegação Interna --}}
                        <div class="mt-4 px-6 border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8">
                                <button @click="tab = 'geral'" :class="tab === 'geral' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap pb-4 px-4 border-b-2 font-medium text-sm transition-colors">
                                    Dados Gerais
                                </button>
                                <button @click="tab = 'coberturas'" :class="tab === 'coberturas' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap pb-4 px-4 border-b-2 font-medium text-sm transition-colors">
                                    Coberturas ({{ $selectedApolice->coberturas->count() }})
                                </button>
                                <button @click="tab = 'sinistros'" :class="tab === 'sinistros' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap pb-4 px-4 border-b-2 font-medium text-sm transition-colors">
                                    Sinistros ({{ $selectedApolice->sinistros->count() }})
                                </button>
                            </nav>
                        </div>
                    </div>

                    <div x-show="tab === 'geral'" x-transition:enter.opacity.duration.300ms>
                        @include('seguros.partials._show-details', ['apolice' => $selectedApolice])
                    </div>

                    <div x-show="tab === 'coberturas'" style="display: none;" x-transition:enter.opacity.duration.300ms>
                        @include('seguros.partials.coberturas', ['apolice' => $selectedApolice])
                    </div>

                    <div x-show="tab === 'sinistros'" style="display: none;" x-transition:enter.opacity.duration.300ms>
                         @include('seguros.partials.sinistros', ['apolice' => $selectedApolice])
                    </div>
                </div>

                {{-- Barra de Ações Sticky --}}
                <div class="absolute bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
                    @include('seguros.partials._show-actions-footer', ['apolice' => $selectedApolice])
                </div>

            @else
                {{-- Estado Vazio --}}
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-24 h-24 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h3 class="text-lg font-medium text-gray-600">Nenhuma apólice selecionada</h3>
                    <p class="mt-1">Selecione um item da lista à esquerda para visualizar os detalhes.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
