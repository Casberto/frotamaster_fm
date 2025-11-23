<!-- Cabeçalho com Status (Sem botões, pois estão no footer) -->
<div class="flex flex-col md:flex-row justify-between md:items-center mb-6 pb-4 border-b border-gray-100">
    
    {{-- Lado Esquerdo: Título e Solicitante --}}
    <div>
        <div class="flex items-center gap-3">
            <h3 class="text-2xl font-bold text-gray-900">
                Reserva #{{ $reserva->res_id }}
            </h3>
            
            {{-- Badge de Status --}}
            <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full
                         {{ $reserva->res_status == 'pendente' ? 'bg-yellow-100 text-yellow-800' :
                            ($reserva->res_status == 'aprovada' ? 'bg-green-100 text-green-800' :
                            ($reserva->res_status == 'em_uso' ? 'bg-blue-100 text-blue-800' :
                            ($reserva->res_status == 'rejeitada' ? 'bg-red-100 text-red-800' :
                            ($reserva->res_status == 'em_revisao' ? 'bg-purple-100 text-purple-800' :
                            ($reserva->res_status == 'encerrada' ? 'bg-gray-100 text-gray-600' :
                            ($reserva->res_status == 'cancelada' ? 'bg-gray-200 text-gray-500' :
                            ($reserva->res_status == 'pendente_ajuste' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800'))))))) }}">
                {{ ucfirst(str_replace('_', ' ', $reserva->res_status)) }}
            </span>
        </div>
        
        <div class="mt-2 flex items-center text-sm text-gray-500 gap-4">
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                Solicitado por: <span class="font-medium text-gray-700">{{ $reserva->solicitante->name ?? 'N/D' }}</span>
            </div>
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ $reserva->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

    {{-- Lado Direito: Vazio ou Informação Extra (Sem botões) --}}
    <div class="hidden md:block text-right">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Código de Controle</p>
        <p class="text-lg font-mono font-bold text-gray-700">{{ $reserva->res_codigo ?? str_pad($reserva->res_id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>
</div>