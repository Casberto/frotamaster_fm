<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orçamento #{{ $os->osv_codigo }} - Frotamaster</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0 bg-gray-100">
        
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg relative">
            
            <div class="text-center border-b pb-4 mb-4">
                <h1 class="text-xl font-black text-gray-800">{{ $os->empresa->nome_empresa ?? 'Oficina Especializada' }}</h1>
                <p class="text-xs text-gray-500">Orçamento de Serviço #{{ $os->osv_codigo }}</p>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-100">
                <h2 class="text-xs font-bold text-blue-500 uppercase mb-1">Veículo</h2>
                <p class="text-lg font-bold text-blue-900">{{ $os->veiculo->vct_modelo }} <span class="text-sm font-normal">({{ $os->veiculo->vct_placa }})</span></p>
                <div class="mt-3 pt-3 border-t border-blue-200">
                    <p class="text-xs font-bold text-blue-500 uppercase">Diagnóstico Técnico</p>
                    <p class="text-sm text-blue-800 italic">"{{ $os->osv_problema_relatado }}"</p>
                </div>
            </div>

            @php
                $aprovados = $os->itens->where('osi_aprovado', 1);
                $pendentes = $os->itens->where('osi_aprovado', 0);
            @endphp

            @if($aprovados->count() > 0)
            <div class="mb-6 opacity-75">
                <h3 class="text-sm font-bold text-gray-500 mb-3 uppercase tracking-wider flex items-center">
                    <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Já Aprovados
                </h3>
                <div class="space-y-3">
                    @foreach($aprovados as $item)
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-start">
                            <span class="font-bold text-gray-400 mr-2">{{ $item->osi_quantidade }}x</span>
                            <span class="text-gray-600 truncate max-w-[200px]">{{ $item->osi_descricao }}</span>
                        </div>
                        <span class="font-bold text-gray-600">R$ {{ number_format($item->osi_quantidade * $item->osi_valor_venda_unit, 2, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($pendentes->count() > 0)
            <div class="mb-6 bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                <h3 class="text-sm font-black text-yellow-700 mb-3 uppercase tracking-wider flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Novos Itens para Aprovação
                </h3>
                <div class="space-y-3">
                    @foreach($pendentes as $item)
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-start">
                            <span class="font-bold text-gray-400 mr-2">{{ $item->osi_quantidade }}x</span>
                            <span class="font-bold text-gray-800">{{ $item->osi_descricao }}</span>
                        </div>
                        <span class="font-bold text-gray-900">R$ {{ number_format($item->osi_quantidade * $item->osi_valor_venda_unit, 2, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                
                <div class="border-t border-dashed border-yellow-300 mt-4 pt-4 flex justify-between items-end">
                    <span class="text-yellow-700 font-medium">Adicional a Aprovar</span>
                    <span class="text-2xl font-black text-yellow-900">R$ {{ number_format($pendentes->sum(fn($i) => $i->osi_quantidade * $i->osi_valor_venda_unit), 2, ',', '.') }}</span>
                </div>
            </div>
            @else
            {{-- Se nao tem pendentes, mostra só o total geral dos aprovados --}}
             <div class="border-t border-dashed border-gray-300 mt-4 pt-4 flex justify-between items-end">
                <span class="text-gray-500 font-medium">Total Estimado</span>
                <span class="text-3xl font-black text-gray-800">R$ {{ number_format($os->osv_valor_total, 2, ',', '.') }}</span>
            </div>
            @endif

            <form action="{{ route('oficina.os.public.aceitar', $os->osv_token_acesso) }}" method="POST" class="space-y-3">
                @csrf
                
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-black py-4 rounded-xl shadow-lg transform transition hover:scale-[1.02] flex justify-center items-center text-lg">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    APROVAR ORÇAMENTO
                </button>

                <a href="https://wa.me/55{{ preg_replace('/\D/', '', $os->empresa->telefone ?? '') }}" class="block w-full text-center bg-white border-2 border-gray-200 text-gray-600 font-bold py-3 rounded-xl hover:bg-gray-50">
                    Tenho uma dúvida
                </a>
            </form>

            <div class="mt-6 text-center text-xs text-gray-400">
                <p>Ao aprovar, você concorda com a execução dos serviços acima listados.</p>
                <p class="mt-1">Frotamaster Service &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
