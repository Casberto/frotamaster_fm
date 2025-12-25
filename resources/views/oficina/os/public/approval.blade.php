<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
        
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            
            <div class="text-center mb-6">
                <h2 class="text-2xl font-black text-gray-800">{{ $os->empresa->name ?? 'Oficina' }}</h2>
                <p class="text-sm text-gray-500">Ordem de Serviço #{{ $os->osv_codigo }}</p>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Sucesso!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <div class="mt-2 text-sm">
                        Nossa equipe começará o trabalho em breve.
                    </div>
                </div>
            @elseif(session('status') == 'already_approved' || $os->osv_status == 'aprovado')
                 <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Aprovado!</strong>
                    <span class="block sm:inline">Este orçamento já foi aprovado.</span>
                </div>
            @endif

            <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-100">
                <h3 class="text-xs font-bold text-gray-500 uppercase mb-2">Veículo</h3>
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-lg font-bold text-gray-800">{{ $os->veiculo->vct_modelo }}</span>
                        <div class="text-sm text-gray-600">{{ $os->veiculo->vct_placa }}</div>
                    </div>
                    <div class="bg-white p-2 rounded shadow-sm">
                        🚗
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-xs font-bold text-gray-500 uppercase mb-2">Itens do Orçamento</h3>
                <ul class="divide-y divide-gray-100">
                    @foreach($os->itens as $item)
                        <li class="py-3 flex justify-between">
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $item->osi_descricao }}</p>
                                <p class="text-xs text-gray-500">{{ $item->osi_quantidade }}x R$ {{ number_format($item->osi_valor_venda_unit, 2, ',', '.') }}</p>
                            </div>
                            <span class="text-sm font-bold text-gray-900">R$ {{ number_format($item->osi_quantidade * $item->osi_valor_venda_unit, 2, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="border-t border-gray-200 mt-4 pt-4 flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-900">Total</span>
                    <span class="text-2xl font-black text-blue-600">R$ {{ number_format($os->osv_valor_total, 2, ',', '.') }}</span>
                </div>
            </div>

            @if($os->osv_status != 'aprovado' && !session('success'))
                <form action="{{ route('os.public.approve', $os->osv_token_acesso) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-lg shadow-lg transform transition hover:scale-105 mb-3">
                        APROVAR ORÇAMENTO
                    </button>
                </form>

                <div class="text-center">
                    <a href="https://wa.me/?text=Tenho uma dúvida sobre a OS {{ $os->osv_codigo }}" class="text-sm text-gray-500 hover:underline">
                        Tenho dúvidas / Falar com a oficina
                    </a>
                </div>
            @endif

        </div>
        
        <div class="text-center mt-6 text-xs text-gray-400">
            Powered by FrotaMaster
        </div>
    </div>
</body>
</html>
