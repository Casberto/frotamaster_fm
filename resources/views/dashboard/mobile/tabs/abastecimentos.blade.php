<div class="space-y-6">
    <div class="flex justify-between items-center px-1">
        <h3 class="font-bold text-gray-800 text-lg">Abastecimentos</h3>
        <a href="{{ route('abastecimentos.index') }}" class="text-sm text-blue-600 font-semibold bg-blue-50 px-3 py-1 rounded-full hover:bg-blue-100 transition-colors">Ver todos</a>
    </div>

    {{-- Summary Card --}}
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white p-6 rounded-2xl shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
        <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-blue-400 opacity-20 rounded-full blur-lg"></div>
        
        <div class="relative z-10">
            <div class="text-blue-100 text-xs font-bold uppercase tracking-wider mb-1">Custo Total (Mês)</div>
            <div class="text-3xl font-extrabold tracking-tight">R$ {{ number_format($fuelingData['indicadores']['custo_total_mes'] ?? 0, 2, ',', '.') }}</div>
            
            <div class="mt-6 grid grid-cols-2 gap-4 border-t border-blue-500/30 pt-4">
                <div>
                    <div class="text-[10px] text-blue-200 uppercase font-bold">Volume</div>
                    <div class="text-lg font-bold">{{ number_format($fuelingData['indicadores']['quantidade_abastecida'] ?? 0, 0, ',', '.') }} <span class="text-xs font-normal text-blue-200">L</span></div>
                </div>
                <div>
                    <div class="text-[10px] text-blue-200 uppercase font-bold">Eficiência</div>
                    <div class="text-lg font-bold">{{ number_format($fuelingData['indicadores']['media_geral_consumo'] ?? 0, 1, ',', '.') }} <span class="text-xs font-normal text-blue-200">km/L</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- List --}}
    <div class="space-y-3">
        <div class="px-1 text-xs font-bold text-gray-400 uppercase tracking-wider">
            Recentes
        </div>

        @forelse(($fuelingData['lista'] ?? [])->take(5) as $abastecimento)
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center active:scale-[0.99] transition-transform duration-200">
            <div class="flex items-center space-x-4 min-w-0">
                <div class="bg-blue-50 p-2.5 rounded-xl flex-shrink-0 text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <div class="min-w-0">
                    <div class="font-bold text-gray-900 truncate text-base">{{ $abastecimento->veiculo->vei_placa ?? 'N/A' }}</div>
                    <div class="text-xs text-gray-500 truncate font-medium">{{ $abastecimento->fornecedor->for_nome_fantasia ?? 'Posto' }}</div>
                </div>
            </div>
            <div class="text-right flex-shrink-0 ml-2">
                <div class="font-bold text-gray-900 text-sm">R$ {{ number_format($abastecimento->aba_vlr_tot, 2, ',', '.') }}</div>
                <div class="text-xs text-gray-400 font-medium">{{ $abastecimento->aba_data->format('d/m') }}</div>
            </div>
        </div>
        @empty
        <div class="p-8 text-center bg-white rounded-2xl border border-gray-100 border-dashed">
            <p class="text-sm text-gray-400 font-medium">Nenhum abastecimento recente.</p>
        </div>
        @endforelse
    </div>
</div>
