<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    OS #{{ $os->osv_codigo }}
                </h2>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ 
                    $os->osv_status == 'aprovado' ? 'bg-green-100 text-green-800' : 
                    ($os->osv_status == 'aguardando' ? 'bg-gray-100 text-gray-800' : 
                    ($os->osv_status == 'execucao' ? 'bg-blue-100 text-blue-800' : 
                    ($os->osv_status == 'pecas' ? 'bg-orange-100 text-orange-800' :
                    ($os->osv_status == 'pronto' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-800')))) 
                }}">
                    {{ $os->osv_status }}
                </span>
            </div>

            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                @if($os->osv_status == 'aprovado')
                    <form action="{{ route('oficina.os.iniciar_execucao', $os->osv_id) }}" method="POST" class="w-1/2 sm:w-auto flex-1 sm:flex-none">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-md font-bold hover:bg-green-700 text-sm shadow flex justify-center items-center whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Iniciar Execução
                        </button>
                    </form>
                    <form action="{{ route('oficina.os.solicitar_pecas', $os->osv_id) }}" method="POST" class="w-1/2 sm:w-auto flex-1 sm:flex-none">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md font-bold hover:bg-gray-50 text-sm shadow flex justify-center items-center whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Comprar Peças
                        </button>
                    </form>
                @endif

                @if($os->osv_status == 'pecas')
                    <form action="{{ route('oficina.os.iniciar_execucao', $os->osv_id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-md font-bold hover:bg-green-700 text-sm shadow animate-pulse flex justify-center items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Peças Chegaram / Iniciar
                        </button>
                    </form>
                @endif

                @if($os->osv_status == 'execucao')
                    <div class="w-full sm:w-auto" x-data>
                        <button type="button" @click="$dispatch('open-finalizar')" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-700 text-sm shadow flex justify-center items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Finalizar Serviço
                        </button>
                    </div>
                @endif



                {{-- Status Entregue com Garantia Vigente (ou apenas status entregue) --}}
                @if($os->osv_status == 'entregue' && $os->estaEmGarantia())
                    <div class="w-full sm:w-auto" x-data>
                        <button type="button" @click="$dispatch('open-garantia')" class="w-full sm:w-auto bg-amber-500 text-white px-4 py-2 rounded-md font-bold hover:bg-amber-600 text-sm shadow flex justify-center items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Acionar Garantia
                        </button>
                    </div>
                @endif

                @if($os->osv_status == 'entregue' && !$os->estaEmGarantia())
                     <span class="text-xs text-gray-500 font-bold border border-gray-200 px-3 py-2 rounded-md bg-gray-50">
                        Garantia Expirada
                     </span>
                @endif  

                @if($os->osv_status == 'pronto')
                    <form action="{{ route('oficina.os.whatsapp_pronto', $os->osv_id) }}" method="POST" target="_blank" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-md font-bold hover:bg-green-700 text-sm shadow flex justify-center items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            Avisar Cliente
                        </button>
                    </form>
                    <div class="w-full sm:w-auto" x-data>
                        <button type="button" @click="$dispatch('open-entrega')" class="w-full sm:w-auto bg-indigo-600 text-white px-4 py-2 rounded-md font-bold hover:bg-indigo-700 text-sm shadow flex justify-center items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Entregar Veículo
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6" x-data="{ modalItemOpen: false, tipoItem: 'peca', modalEntregaOpen: false, modalFinalizarOpen: false, modalGarantiaOpen: false, modalRemoverItemOpen: false, removerItemUrl: '' }" @open-entrega.window="modalEntregaOpen = true" @open-finalizar.window="modalFinalizarOpen = true" @open-garantia.window="modalGarantiaOpen = true">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Veículo</h3>
                        <div class="text-xl font-black text-gray-800">{{ $os->veiculo->vct_modelo }}</div>
                        <div class="text-md text-gray-600">{{ $os->veiculo->vct_marca }} - {{ $os->veiculo->vct_placa }}</div>
                        <div class="text-sm text-gray-400 mt-1">Combustível: {{ $os->veiculo->vct_combustivel }}</div>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Cliente</h3>
                        <div class="text-lg font-bold text-gray-800">{{ $os->veiculo->cliente->clo_nome }}</div>
                        <div class="flex items-center text-green-600 mt-1">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
                            <a href="https://wa.me/55{{ preg_replace('/\D/', '', $os->veiculo->cliente->clo_telefone) }}" target="_blank" class="hover:underline">
                                {{ $os->veiculo->cliente->clo_telefone }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $lucro = $os->osv_valor_total - $os->osv_valor_custo_total;
                $margem = $os->osv_valor_total > 0 ? ($lucro / $os->osv_valor_total) * 100 : 0;
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                {{-- Receitas --}}
                <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                    <span class="text-[10px] text-blue-600 font-bold uppercase tracking-wider">Receita Peças</span>
                    <div class="text-lg font-bold text-blue-800">R$ {{ number_format($os->osv_valor_pecas, 2, ',', '.') }}</div>
                </div>
                <div class="bg-orange-50 p-3 rounded-lg border border-orange-100">
                    <span class="text-[10px] text-orange-600 font-bold uppercase tracking-wider">Receita M.O.</span>
                    <div class="text-lg font-bold text-orange-800">R$ {{ number_format($os->osv_valor_mao_obra, 2, ',', '.') }}</div>
                </div>

                {{-- Custos --}}
                <div class="bg-red-50 p-3 rounded-lg border border-red-100">
                    <span class="text-[10px] text-red-600 font-bold uppercase tracking-wider">Custo Total</span>
                    <div class="text-lg font-bold text-red-800">R$ {{ number_format($os->osv_valor_custo_total, 2, ',', '.') }}</div>
                </div>

                {{-- Lucro --}}
                <div class="{{ $lucro >= 0 ? 'bg-green-50 border-green-100' : 'bg-red-100 border-red-200' }} p-3 rounded-lg border shadow-sm">
                    <span class="text-[10px] {{ $lucro >= 0 ? 'text-green-600' : 'text-red-600' }} font-bold uppercase tracking-wider">Lucro Estimado</span>
                    <div class="text-lg font-black {{ $lucro >= 0 ? 'text-green-800' : 'text-red-800' }}">
                        R$ {{ number_format($lucro, 2, ',', '.') }}
                    </div>
                </div>

                 {{-- Total Geral --}}
                <div class="bg-gray-800 p-3 rounded-lg border border-gray-700 shadow-lg">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Cliente</span>
                    <div class="text-xl font-black text-white">R$ {{ number_format($os->osv_valor_total, 2, ',', '.') }}</div>
                    <div class="text-[10px] text-gray-400 mt-1">Margem: <span class="{{ $margem >= 30 ? 'text-green-400' : ($margem >= 15 ? 'text-yellow-400' : 'text-red-400') }}">{{ number_format($margem, 1) }}%</span></div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700">Itens do Orçamento</h3>
                    <button @click="modalItemOpen = true" class="bg-blue-600 text-white px-3 py-2 rounded-md text-sm font-bold hover:bg-blue-700 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Adicionar
                    </button>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($os->itens as $item)
                        <div class="p-4 flex justify-between items-center hover:bg-gray-50 transition">
                            <div class="flex items-start">
                                <div class="p-2 rounded-lg mr-3 {{ $item->osi_tipo == 'peca' ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600' }}">
                                    @if($item->osi_tipo == 'peca')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $item->osi_descricao }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $item->osi_quantidade }}x R$ {{ number_format($item->osi_valor_venda_unit, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">R$ {{ number_format($item->osi_quantidade * $item->osi_valor_venda_unit, 2, ',', '.') }}</p>
                                
                                {{-- Dados Financeiros do Item (Visíveis apenas para a oficina) --}}
                                @if($item->osi_valor_custo_unit > 0)
                                    @php
                                        $vendaTotalItem = $item->osi_quantidade * $item->osi_valor_venda_unit;
                                        $custoTotalItem = $item->osi_quantidade * $item->osi_valor_custo_unit;
                                        $lucroItem = $vendaTotalItem - $custoTotalItem;
                                        $margemItem = $vendaTotalItem > 0 ? ($lucroItem / $vendaTotalItem) * 100 : 0;
                                    @endphp
                                    <div class="text-xs mt-1 space-y-0.5">
                                        <div class="text-red-400" title="Custo Total do Item">Custo: -R$ {{ number_format($custoTotalItem, 2, ',', '.') }}</div>
                                        <div class="{{ $lucroItem >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold" title="Lucro do Item">
                                            Lucro: R$ {{ number_format($lucroItem, 2, ',', '.') }} ({{ number_format($margemItem, 0) }}%)
                                        </div>
                                    </div>
                                @endif
                                
                                <button type="button" 
                                    data-url="{{ route('oficina.os.items.destroy', $item->osi_id) }}"
                                    @click="modalRemoverItemOpen = true; removerItemUrl = $el.dataset.url"
                                    class="text-xs text-red-500 hover:text-red-700 mt-1">
                                    Remover
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400">
                            <p>Nenhuma peça ou serviço adicionado.</p>
                            <p class="text-sm">Clique em "Adicionar" para começar.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            @if(in_array($os->osv_status, ['aprovacao', 'diagnostico']) && $os->itens->count() > 0)
            @if(in_array($os->osv_status, ['aprovacao', 'diagnostico']) && $os->itens->count() > 0)
            <div class="bg-gray-100 p-4 rounded-lg flex flex-col gap-2">
                @if($os->osv_pai_id && $os->osv_valor_total == 0)
                    {{-- Garantia Total (Custo Zero) - Aprovação Direta --}}
                    <div class="bg-green-50 p-3 rounded text-sm text-green-700 mb-2">
                        <strong>Garantia Total Identificada:</strong> O valor para o cliente é R$ 0,00. Pode iniciar a execução diretamente.
                    </div>
                    <form action="{{ route('oficina.os.iniciar_execucao', $os->osv_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg shadow hover:bg-green-700 flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Confirmar Garantia e Iniciar Execução
                        </button>
                    </form>
                @else
                    {{-- Fluxo Normal - Enviar Orçamento --}}
                    <form action="{{ route('oficina.os.whatsapp', $os->osv_id) }}" method="POST" target="_blank">
                        @csrf
                        <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 rounded-lg shadow hover:bg-green-600 flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            Enviar Orçamento no WhatsApp
                        </button>
                    </form>

                    <form action="{{ route('oficina.os.rejeitar', $os->osv_id) }}" method="POST" onsubmit="return confirm('Tem certeza que o cliente REJEITOU o orçamento? A OS será cancelada.');">
                        @csrf
                        <button type="submit" class="w-full bg-red-100 text-red-600 font-bold py-2 rounded-lg hover:bg-red-200 border border-red-200 flex justify-center items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Cliente Rejeitou / Cancelar OS
                        </button>
                    </form>
                @endif
            </div>
            @endif
            @endif

            {{-- HISTÓRICO DA OS --}}
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Histórico de Eventos</h3>
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($os->historico as $evento)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            @php
                                                $iconClass = 'bg-gray-400';
                                                $iconContent = '<svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                                                
                                                if(Str::contains($evento->osh_acao, 'Criada')) {
                                                    $iconClass = 'bg-gray-500';
                                                    $iconContent = '<svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                                                } elseif(Str::contains($evento->osh_acao, 'Diagnóstico')) {
                                                    $iconClass = 'bg-blue-500';
                                                    $iconContent = '<svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>';
                                                } elseif(Str::contains($evento->osh_acao, 'Adicionado')) {
                                                    $iconClass = 'bg-indigo-400';
                                                    $iconContent = '<svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>';
                                                } elseif(Str::contains($evento->osh_acao, 'Aprovado')) {
                                                    $iconClass = 'bg-green-500';
                                                    $iconContent = '<svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                                                } elseif(Str::contains($evento->osh_acao, 'WhatsApp')) {
                                                    $iconClass = 'bg-green-600';
                                                    $iconContent = '<svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>';
                                                } elseif(Str::contains($evento->osh_acao, 'Revertido') || Str::contains($evento->osh_acao, 'Rejeitado')) {
                                                    $iconClass = 'bg-red-500';
                                                    $iconContent = '<svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                                                } elseif(Str::contains($evento->osh_acao, 'Execução')) {
                                                    $iconClass = 'bg-blue-600';
                                                    $iconContent = '<svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>';
                                                } elseif(Str::contains($evento->osh_acao, 'Peças')) {
                                                    $iconClass = 'bg-orange-500';
                                                    $iconContent = '<svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>';
                                                } elseif(Str::contains($evento->osh_acao, 'Pronto') || Str::contains($evento->osh_acao, 'Finalizado')) {
                                                    $iconClass = 'bg-green-700';
                                                    $iconContent = '<svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                                                }
                                            @endphp

                                            <span class="h-8 w-8 rounded-full {{ $iconClass }} flex items-center justify-center ring-8 ring-white">
                                                {!! $iconContent !!}
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    <span class="font-medium text-gray-900">{{ $evento->osh_acao }}</span> 
                                                    por <span class="font-medium text-gray-900">{{ $evento->usuario ? explode(' ', $evento->usuario->name)[0] : 'Cliente/Sistema' }}</span>
                                                </p>
                                                <p class="text-xs text-gray-600 mt-1">{{ $evento->osh_descricao }}</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                <time datetime="{{ $evento->created_at }}">{{ $evento->created_at->format('d/m/Y H:i') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        <div x-show="modalItemOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="modalItemOpen" @click="modalItemOpen = false" class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form action="{{ route('oficina.os.items.store', $os->osv_id) }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Adicionar ao Orçamento</h3>
                            
                            <div class="flex space-x-4 mb-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="tipo" value="peca" x-model="tipoItem" class="text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 font-bold text-gray-700">Peça</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="tipo" value="servico" x-model="tipoItem" class="text-orange-600 focus:ring-orange-500">
                                    <span class="ml-2 font-bold text-gray-700">Serviço / Mão de Obra</span>
                                </label>
                            </div>

                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700">Descrição</label>
                                <input type="text" name="descricao" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500">Qtd</label>
                                    <input type="number" name="quantidade" value="1" min="1" class="mt-1 block w-full rounded-md border-gray-300" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500">Valor Venda (R$)</label>
                                    <input type="number" name="valor_venda" step="0.01" class="mt-1 block w-full rounded-md border-gray-300" placeholder="0.00" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-red-500">Custo Unit. (R$)</label>
                                    <input type="number" name="valor_custo" step="0.01" class="mt-1 block w-full rounded-md border-red-200 bg-red-50 text-gray-700 placeholder-red-200 focus:border-red-500 focus:ring-red-500" placeholder="Importante para Lucro">
                                </div>
                            </div>

                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Salvar
                            </button>
                            <button type="button" @click="modalItemOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>


    </div>


        {{-- MODAL DE ENTREGA (CONFIRMAÇÃO) --}}
        <div x-show="modalEntregaOpen" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                {{-- This element is to trick the browser into centering the modal contents. --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
                     @click.away="modalEntregaOpen = false">
                     
                    <form action="{{ route('oficina.os.entregar', $os->osv_id) }}" method="POST">
                        @csrf
                        {{-- Corpo do Modal --}}
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Confirmar Entrega
                                    </h3>
                                    <div class="mt-2 text-sm text-gray-500">
                                        <p>Tem certeza que deseja marcar o veículo como entregue? Isso encerrará a OS definitivamente.</p>
                                    </div>

                            <div class="mt-4 border-t pt-4">
                                <h4 class="text-sm font-bold text-gray-700 mb-3">Financeiro e Pagamento</h4>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status do Pagamento</label>
                                        <select name="status_pagamento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="pago">✅ Pago (Recebido)</option>
                                            <option value="pendente">⏳ Pendente (A Receber)</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Forma de Pagamento</label>
                                        <select name="forma_pagamento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="pix">PIX</option>
                                            <option value="dinheiro">Dinheiro</option>
                                            <option value="cartao_credito">Cartão de Crédito (D+30)</option>
                                            <option value="cartao_debito">Cartão de Débito (D+1)</option>
                                            <option value="boleto">Boleto Bancário</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 border-t pt-4">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Garantia (dias)</label>
                                <input type="number" name="dias_garantia" value="90" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ex: 90">
                                <p class="text-xs text-gray-400 mt-1">Deixe 0 se não houver garantia.</p>
                            </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rodapé do Modal --}}
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Entregar Veículo
                            </button>
                            <button type="button" @click="modalEntregaOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        {{-- MODAL DE FINALIZAR (CONFIRMAÇÃO) --}}
        <div x-show="modalFinalizarOpen" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                {{-- This element is to trick the browser into centering the modal contents. --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
                     @click.away="modalFinalizarOpen = false">
                     
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Finalizar Serviço?
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Isso mudará o status para <strong>Pronto</strong> e notificará que o veículo está disponível. <br>
                                        Confirma que todos os serviços foram executados?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form action="{{ route('oficina.os.finalizar', $os->osv_id) }}" method="POST" class="inline-block w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Sim, Finalizar
                            </button>
                        </form>
                        <button type="button" @click="modalFinalizarOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>


        {{-- MODAL DE GARANTIA (CONFIRMAÇÃO) --}}
        <div x-show="modalGarantiaOpen" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                {{-- This element is to trick the browser into centering the modal contents. --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
                     @click.away="modalGarantiaOpen = false">
                     
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Acionar Garantia?
                                </h3>
                        <div class="mt-4 text-left">
                            {{-- Content of Warranty Modal --}}
                        </div>
                    </div>
                </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('oficina.os.garantia', $os->osv_id) }}" method="POST" class="inline-block w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-amber-600 text-base font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Sim, Acionar Garantia
                        </button>
                    </form>
                    <button type="button" @click="modalGarantiaOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DE REMOVER ITEM --}}
    <div x-show="modalRemoverItemOpen" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
                    @click.away="modalRemoverItemOpen = false">
                    
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Remover Item?
                            </h3>
                            <div class="mt-2 text-sm text-gray-500">
                                <p>Tem certeza que deseja remover este item do orçamento?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form :action="removerItemUrl" method="POST" class="inline-block w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Sim, Remover
                        </button>
                    </form>
                    <button type="button" @click="modalRemoverItemOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>


    @push('modals')
        {{-- MODAL DE DIAGNÓSTICO (Apenas status 'aguardando') --}}
        @if($os->osv_status == 'aguardando')
        <div class="fixed inset-0 z-[9999] overflow-y-auto" style="background-color: rgba(0,0,0,0.5);">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6 relative">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Iniciar Diagnóstico</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Este veículo chegou com o relato: <br>
                        <span class="font-medium text-gray-800">"{{ $os->osv_problema_relatado }}"</span>.
                    </p>
                    <p class="text-sm text-gray-600 mb-6">
                        Para começar o orçamento, descreva o diagnóstico técnico do problema encontrado.
                    </p>

                    <form action="{{ route('oficina.os.diagnostico', $os->osv_id) }}" method="POST">
                        @csrf
                        <textarea name="diagnostico" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 mb-4" placeholder="Descreva aqui o problema técnico identificado..." required></textarea>
                        
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow">
                            Salvar Diagnóstico e Iniciar Orçamento
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endpush

</x-app-layout>
