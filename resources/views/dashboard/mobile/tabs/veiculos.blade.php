<div class="space-y-4">
    <div class="flex justify-between items-center px-1">
        <h3 class="font-bold text-gray-800 text-lg">Veículos Recentes</h3>
        <a href="{{ route('veiculos.index') }}" class="text-sm text-blue-600 font-semibold bg-blue-50 px-3 py-1 rounded-full hover:bg-blue-100 transition-colors">Ver todos</a>
    </div>

    {{-- Simplified List --}}
    <div class="space-y-3">
        @foreach($frota->take(5) as $veiculo)
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center active:scale-[0.99] transition-transform duration-200">
            <div class="flex items-center space-x-4 min-w-0">
                <div class="h-12 w-12 rounded-2xl bg-gray-50 flex-shrink-0 flex items-center justify-center text-gray-600 font-bold text-sm border border-gray-100 shadow-inner">
                    {{ substr($veiculo->vei_placa, 0, 3) }}
                </div>
                <div class="min-w-0">
                    <div class="font-bold text-gray-900 truncate text-base">{{ $veiculo->vei_placa }}</div>
                    <div class="text-xs text-gray-500 truncate font-medium">{{ $veiculo->vei_modelo }}</div>
                </div>
            </div>
            <div class="text-right pl-2">
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $veiculo->vei_status == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $veiculo->vei_status == 1 ? 'Ativo' : 'Inativo' }}
                </div>
                <div class="text-xs text-gray-400 mt-1 font-medium">{{ number_format($veiculo->vei_km_atual, 0, ',', '.') }} km</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
