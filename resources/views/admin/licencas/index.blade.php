<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">
                Gerenciar Licenças
            </h2>
            <a href="{{ route('admin.licencas.create') }}" 
               class="shrink-0 inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                <span class="hidden md:inline">Nova Licença</span>
                <span class="md:hidden">Novo</span>
            </a>
        </div>
    </x-slot>

    {{-- Layout Dividido: Lista (Esq) vs Detalhes (Dir) --}}
    <div class="h-[calc(100vh-100px)] md:h-[calc(100vh-115px)] flex flex-col md:flex-row max-w-[1920px] mx-auto -mb-6 md:-mb-8">
        
        {{-- COLUNA ESQUERDA (LISTA) --}}
        <div class="w-full md:w-2/5 lg:w-1/3 flex flex-col border-r border-gray-200 bg-gray-50 h-full">
            
            {{-- Filtros --}}
            <div class="p-4 border-b border-gray-200 bg-white">
                 <form action="{{ route('admin.licencas.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full pl-10 pr-4 py-2 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Buscar por Empresa ou CNPJ..."
                               onchange="this.form.submit()">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Lista --}}
            <div class="flex-1 overflow-y-auto">
                @forelse ($licencas as $lic)
                    @php
                        $isActive = (request('selected_id') == $lic->id) || (isset($selectedLicenca) && $selectedLicenca->id == $lic->id);
                        $statusColor = match($lic->status) {
                            'ativo' => 'bg-green-100 text-green-800',
                            'expirado' => 'bg-red-100 text-red-800',
                            'pendente' => 'bg-yellow-100 text-yellow-800',
                             default => 'bg-gray-100 text-gray-800'
                        };
                    @endphp
                    
                    <a href="{{ route('admin.licencas.index', array_merge(request()->query(), ['selected_id' => $lic->id])) }}" 
                       class="block p-4 border-b border-gray-100 cursor-pointer transition-colors hover:bg-gray-50
                              {{ $isActive ? 'bg-blue-50 border-l-4 border-l-blue-500' : 'bg-white' }}">
                        
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-gray-900 text-sm truncate pr-2">{{ $lic->empresa->nome_fantasia }}</h3>
                            <span class="text-[10px] px-2 py-0.5 rounded-full {{ $statusColor }} font-bold uppercase">
                                {{ $lic->status }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-end mt-2">
                             <div class="text-xs text-gray-500">
                                <span class="block text-gray-400 text-[10px] uppercase">Plano</span>
                                {{ $lic->plano }}
                            </div>
                            <div class="text-xs text-gray-500 text-right">
                                <span class="block text-gray-400 text-[10px] uppercase">Vencimento</span>
                                {{ $lic->data_vencimento->format('d/m/Y') }}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-gray-500 text-sm">
                        Nenhuma licença encontrada.
                    </div>
                @endforelse
                
                 {{-- Paginação --}}
                 @if($licencas->hasPages())
                 <div class="p-4 border-t border-gray-200">
                     {{ $licencas->appends(request()->except('page'))->links('pagination::simple-tailwind') }}
                 </div>
                 @endif
            </div>
        </div>

        {{-- COLUNA DIREITA (DETALHES) --}}
        <div class="hidden md:flex md:w-3/5 lg:w-2/3 bg-gray-100 flex-col h-full overflow-hidden relative">
            @if($selectedLicenca)
                {{-- Conteúdo com Abas --}}
                <div class="flex-1 overflow-y-auto p-6 pb-24" x-data="{ tab: 'visao_geral' }">
                    
                    {{-- Cabeçalho --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        <div class="flex justify-between items-start border-b border-gray-100 pb-6 mb-6">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $selectedLicenca->empresa->nome_fantasia }}</h1>
                                <p class="text-sm text-gray-500 font-mono mt-1">CNPJ: {{ $selectedLicenca->empresa->cnpj }}</p>
                            </div>
                        </div>

                        {{-- Navegação de Abas --}}
                        <nav class="-mb-px flex space-x-6">
                            <button @click="tab = 'visao_geral'" 
                                    :class="tab === 'visao_geral' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors">
                                Visão Geral
                            </button>
                            <button @click="tab = 'editar'" 
                                    :class="tab === 'editar' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors">
                                Editar Licença
                            </button>
                        </nav>
                    </div>

                    {{-- Conteúdo das Abas --}}
                    
                    {{-- Aba: Visão Geral --}}
                    <div x-show="tab === 'visao_geral'" x-transition:enter.opacity.duration.300ms>
                        @include('admin.licencas.partials._show-details', ['licenca' => $selectedLicenca])
                    </div>

                    {{-- Aba: Editar --}}
                    <div x-show="tab === 'editar'" style="display: none;" x-transition:enter.opacity.duration.300ms>
                         <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            @include('admin.licencas.partials._show-edit', ['licenca' => $selectedLicenca, 'empresas' => $empresas])
                        </div>
                    </div>

                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400">
                    <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-600">Selecione uma Licença</h3>
                    <p class="max-w-xs text-center mt-2">Clique em um item na lista à esquerda para visualizar detalhes e gerenciar a licença.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>