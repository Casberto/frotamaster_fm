<div class="space-y-4">
    <div class="flex justify-between items-center px-1">
        <h3 class="font-bold text-gray-800 text-lg">Visão Geral de Motoristas</h3>
        <a href="{{ route('motoristas.index') }}" class="text-sm text-blue-600 font-semibold bg-blue-50 px-3 py-1 rounded-full hover:bg-blue-100 transition-colors">Ver todos</a>
    </div>

    <div class="space-y-3">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 blur-xl"></div>
            <div class="relative z-10">
                <div class="text-gray-500 text-sm font-medium mb-1 uppercase tracking-wide">Motoristas Ativos</div>
                <div class="text-4xl font-extrabold text-gray-900">{{ $indicadores['motoristas_ativos'] }}</div>
                <div class="mt-6">
                    <a href="{{ route('motoristas.index') }}" class="block w-full py-3 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-md hover:bg-blue-700 transition-colors active:scale-95">
                        Gerenciar Motoristas
                    </a>
                </div>
            </div>
        </div>

        @if(($motoristasCnhVencidaCount ?? 0) > 0)
        <div class="bg-red-50 p-4 rounded-2xl border border-red-100 shadow-sm flex items-start space-x-3">
            <div class="flex-shrink-0 bg-red-100 p-2 rounded-full">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h4 class="text-red-800 font-bold text-sm">Atenção: CNH Vencida</h4>
                <p class="text-sm text-red-600 mt-1">{{ $motoristasCnhVencidaCount }} motoristas estão com a CNH vencida.</p>
            </div>
        </div>
        @endif

        @if(($motoristasCnhAVencerCount ?? 0) > 0)
        <div class="bg-yellow-50 p-4 rounded-2xl border border-yellow-100 shadow-sm flex items-start space-x-3">
            <div class="flex-shrink-0 bg-yellow-100 p-2 rounded-full">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h4 class="text-yellow-800 font-bold text-sm">CNH a Vencer</h4>
                <p class="text-sm text-yellow-700 mt-1">{{ $motoristasCnhAVencerCount }} motoristas com CNH a vencer em breve.</p>
            </div>
        </div>
        @endif
    </div>
</div>
