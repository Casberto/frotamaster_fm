<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">
                Gerenciar Empresas
            </h2>
            <a href="{{ route('admin.empresas.create') }}" 
               class="shrink-0 inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                <span class="hidden md:inline">Nova Empresa</span>
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
                 <form action="{{ route('admin.empresas.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full pl-10 pr-4 py-2 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Buscar empresa (Nome, CNPJ)..."
                               onchange="this.form.submit()">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Lista --}}
            <div class="flex-1 overflow-y-auto">
                @forelse ($empresas as $emp)
                    @php
                        $isActive = (request('selected_id') == $emp->id) || (isset($selectedEmpresa) && $selectedEmpresa->id == $emp->id);
                    @endphp
                    
                    <a href="{{ route('admin.empresas.index', array_merge(request()->query(), ['selected_id' => $emp->id])) }}" 
                       class="block p-4 border-b border-gray-100 cursor-pointer transition-colors hover:bg-gray-50
                              {{ $isActive ? 'bg-blue-50 border-l-4 border-l-blue-500' : 'bg-white' }}">
                        
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-gray-900 text-sm truncate pr-2">{{ $emp->nome_fantasia }}</h3>
                            <span class="text-[10px] px-2 py-0.5 rounded-full {{ $emp->tipo == 'PJ' ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }} font-bold">
                                {{ $emp->tipo }}
                            </span>
                        </div>
                        
                        <div class="text-xs text-gray-500 mb-2 font-mono">
                            {{ $emp->cnpj }}
                        </div>

                        <div class="flex items-center gap-4 text-xs text-gray-400">
                             <span class="flex items-center gap-1" title="Usuários">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                {{ $emp->users_count }}
                            </span>
                            <span class="flex items-center gap-1" title="Veículos">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $emp->veiculos_count }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-gray-500 text-sm">
                        Nenhuma empresa encontrada.
                    </div>
                @endforelse
                
                 {{-- Paginação Simples para Lista --}}
                 @if($empresas->hasPages())
                 <div class="p-4 border-t border-gray-200">
                     {{ $empresas->appends(request()->except('page'))->links('pagination::simple-tailwind') }}
                 </div>
                 @endif
            </div>
        </div>

        {{-- COLUNA DIREITA (DETALHES) --}}
        <div class="hidden md:flex md:w-3/5 lg:w-2/3 bg-gray-100 flex-col h-full overflow-hidden relative">
            @if($selectedEmpresa)
                {{-- Conteúdo com Abas --}}
                <div class="flex-1 overflow-y-auto p-6 pb-24" x-data="{ tab: 'visao_geral' }">
                    
                    {{-- Cabeçalho Empresa --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        <div class="flex justify-between items-start border-b border-gray-100 pb-6 mb-6">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $selectedEmpresa->nome_fantasia }}</h1>
                                <p class="text-sm text-gray-500 font-mono mt-1">{{ $selectedEmpresa->cnpj }} • {{ $selectedEmpresa->razao_social }}</p>
                            </div>
                            <div class="text-right">
                                <span class="block text-xs text-gray-400 uppercase tracking-wide">Plano / Status</span>
                                @if($selectedEmpresa->activeLicense)
                                    <span class="inline-block mt-1 px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm font-bold">
                                        Licença Ativa
                                    </span>
                                @else
                                    <span class="inline-block mt-1 px-3 py-1 bg-red-100 text-red-700 rounded-lg text-sm font-bold">
                                        Sem Licença Ativa
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Navegação de Abas --}}
                        <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
                            <button @click="tab = 'visao_geral'" 
                                    :class="tab === 'visao_geral' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors">
                                Visão Geral
                            </button>
                            <button @click="tab = 'editar'" 
                                    :class="tab === 'editar' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors">
                                Editar Cadastro
                            </button>
                             <button disabled class="cursor-not-allowed border-transparent text-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                Licenças (Em Breve)
                            </button>
                        </nav>
                    </div>

                    {{-- Conteúdo das Abas --}}
                    
                    {{-- Aba: Visão Geral --}}
                    <div x-show="tab === 'visao_geral'" x-transition:enter.opacity.duration.300ms>
                        @include('admin.empresas.partials._show-details', ['empresa' => $selectedEmpresa])
                    </div>

                    {{-- Aba: Editar --}}
                    <div x-show="tab === 'editar'" style="display: none;" x-transition:enter.opacity.duration.300ms>
                         <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            @include('admin.empresas.partials._show-edit', ['empresa' => $selectedEmpresa])
                        </div>
                    </div>

                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400">
                    <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-600">Selecione uma Empresa</h3>
                    <p class="max-w-xs text-center mt-2">Clique em uma empresa na lista à esquerda para visualizar detalhes, estatísticas e opções de gerenciamento.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>