<div class="space-y-6">
    <div class="flex justify-between items-center px-1">
        <h3 class="font-bold text-gray-800 text-lg">Status de Manutenções</h3>
        <a href="{{ route('manutencoes.index') }}" class="text-sm text-blue-600 font-semibold bg-blue-50 px-3 py-1 rounded-full hover:bg-blue-100 transition-colors">Ver todas</a>
    </div>

    {{-- Status Cards --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-red-50 p-4 rounded-2xl border border-red-100 shadow-sm flex flex-col justify-between h-24">
            <div class="flex justify-between items-start">
                <div class="text-[10px] text-red-600 font-bold uppercase tracking-wider">Vencidas</div>
                <div class="bg-red-100 p-1 rounded-full">
                    <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-red-700">{{ $maintenanceData['cards']['vencidas'] ?? 0 }}</div>
        </div>
        <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 shadow-sm flex flex-col justify-between h-24">
            <div class="flex justify-between items-start">
                <div class="text-[10px] text-blue-600 font-bold uppercase tracking-wider">Em Andamento</div>
                <div class="bg-blue-100 p-1 rounded-full">
                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-blue-700">{{ $maintenanceData['cards']['em_andamento'] ?? 0 }}</div>
        </div>
    </div>

    {{-- List --}}
    <div class="space-y-3">
        <div class="px-1 text-xs font-bold text-gray-400 uppercase tracking-wider">
            Últimas / Próximas
        </div>
        
        @forelse($maintenanceData['lista']->take(5) as $manutencao)
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center active:scale-[0.99] transition-transform duration-200">
            <div class="min-w-0 pr-4">
                <div class="flex items-center space-x-2 mb-1">
                    <span class="font-bold text-gray-900 text-base">{{ $manutencao->veiculo->vei_placa ?? 'N/A' }}</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wide
                        {{ $manutencao->man_tipo == 'preventiva' ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600' }}">
                        {{ substr($manutencao->man_tipo, 0, 1) }}
                    </span>
                </div>
                <div class="text-xs text-gray-500 capitalize font-medium truncate">{{ str_replace('_', ' ', $manutencao->man_tipo) }}</div>
            </div>
            <div class="text-right flex-shrink-0 flex flex-col items-end">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide mb-1
                    {{ $manutencao->man_status === 'Concluída' ? 'bg-green-100 text-green-700' : 
                       ($manutencao->man_status === 'em_andamento' ? 'bg-blue-100 text-blue-700' : 
                       ($manutencao->man_status === 'Agendada' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700')) }}">
                    {{ ucfirst(str_replace('_', ' ', $manutencao->man_status)) }}
                </span>
                <div class="text-xs text-gray-400 font-medium">{{ \Carbon\Carbon::parse($manutencao->man_data_inicio)->format('d/m') }}</div>
            </div>
        </div>
        @empty
        <div class="p-8 text-center bg-white rounded-2xl border border-gray-100 border-dashed">
            <p class="text-sm text-gray-400 font-medium">Nenhuma manutenção recente.</p>
        </div>
        @endforelse
    </div>
</div>
