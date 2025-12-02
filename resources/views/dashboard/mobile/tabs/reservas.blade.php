<div class="space-y-6">
    <div class="flex justify-between items-center px-1">
        <h3 class="font-bold text-gray-800 text-lg">Reservas</h3>
        <a href="{{ route('reservas.index') }}" class="text-sm text-blue-600 font-semibold bg-blue-50 px-3 py-1 rounded-full hover:bg-blue-100 transition-colors">Ver todas</a>
    </div>

    {{-- Status Cards --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-24">
            <div class="flex justify-between items-start">
                <div class="text-[10px] text-blue-600 font-bold uppercase tracking-wider">Ativas</div>
                <div class="bg-blue-50 p-1 rounded-full">
                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-blue-700">{{ $reservationsData['cards']['ativas'] ?? 0 }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between h-24">
            <div class="flex justify-between items-start">
                <div class="text-[10px] text-yellow-600 font-bold uppercase tracking-wider">Pendentes</div>
                <div class="bg-yellow-50 p-1 rounded-full">
                    <svg class="w-3 h-3 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-yellow-700">{{ $reservationsData['cards']['pendentes'] ?? 0 }}</div>
        </div>
    </div>

    {{-- List --}}
    <div class="space-y-3">
        <div class="px-1 text-xs font-bold text-gray-400 uppercase tracking-wider">
            Próximas / Recentes
        </div>

        @forelse($reservationsData['lista']->take(5) as $reserva)
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 active:scale-[0.99] transition-transform duration-200">
            <div class="flex justify-between mb-3">
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 border border-gray-200">
                        {{ $reserva->veiculo->vei_placa ?? 'N/A' }}
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full
                        {{ $reserva->res_status == 'aprovada' ? 'bg-green-50 text-green-600' : ($reserva->res_status == 'pendente' ? 'bg-yellow-50 text-yellow-600' : 'bg-gray-50 text-gray-500') }}">
                        {{ ucfirst($reserva->res_status) }}
                    </span>
                </div>
            </div>
            
            <div class="flex justify-between items-end">
                <div>
                    <div class="text-sm font-bold text-gray-900">{{ $reserva->motorista->mot_nome ?? 'Motorista' }}</div>
                    <div class="text-xs text-gray-500 flex items-center mt-1 font-medium">
                        <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::parse($reserva->res_data_saida)->format('d/m H:i') }}
                    </div>
                </div>
                <div class="text-right">
                     <a href="{{ route('reservas.show', $reserva->res_id) }}" class="text-blue-600 bg-blue-50 p-2 rounded-full hover:bg-blue-100 transition-colors inline-block">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                     </a>
                </div>
            </div>
        </div>
        @empty
        <div class="p-8 text-center bg-white rounded-2xl border border-gray-100 border-dashed">
            <p class="text-sm text-gray-400 font-medium">Nenhuma reserva recente.</p>
        </div>
        @endforelse
    </div>
</div>
