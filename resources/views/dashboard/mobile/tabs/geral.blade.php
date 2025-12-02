<div class="space-y-6">
    {{-- Welcome Section --}}
    <div class="flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Olá, {{ Auth::user()->name }}</h2>
            <p class="text-xs text-gray-500 mt-1">Resumo da sua frota hoje.</p>
        </div>
        <div class="bg-blue-50 p-3 rounded-full">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
        </div>
    </div>

    {{-- Key Indicators Grid --}}
    <div class="grid grid-cols-2 gap-3">
        {{-- Veículos Ativos --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full">
            <div class="text-gray-500 text-[10px] font-bold uppercase tracking-wider">Veículos</div>
            <div class="mt-2 flex items-baseline">
                <span class="text-2xl font-extrabold text-gray-900">{{ $indicadores['veiculos_ativos'] }}</span>
                <span class="ml-1 text-xs text-gray-400 font-medium">/ {{ $indicadores['veiculos_total'] }}</span>
            </div>
        </div>

        {{-- Motoristas --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full">
            <div class="text-gray-500 text-[10px] font-bold uppercase tracking-wider">Motoristas</div>
            <div class="mt-2 flex items-baseline">
                <span class="text-2xl font-extrabold text-gray-900">{{ $indicadores['motoristas_ativos'] }}</span>
            </div>
        </div>

        {{-- Manutenções --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full {{ $indicadores['manutencoes_vencidas'] > 0 ? 'ring-2 ring-red-100 bg-red-50/30' : '' }}">
            <div class="text-gray-500 text-[10px] font-bold uppercase tracking-wider">Manutenções</div>
            <div class="mt-2">
                <span class="text-2xl font-extrabold {{ $indicadores['manutencoes_vencidas'] > 0 ? 'text-red-600' : 'text-gray-900' }}">
                    {{ $indicadores['manutencoes_vencidas'] }}
                </span>
                <span class="text-[10px] text-gray-400 block font-medium uppercase mt-1">Vencidas</span>
            </div>
        </div>

        {{-- Custo Mês --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-full">
            <div class="text-gray-500 text-[10px] font-bold uppercase tracking-wider">Custo Mês</div>
            <div class="mt-2">
                <span class="text-lg font-extrabold text-gray-900 tracking-tight">R$ {{ number_format($indicadores['custo_total_mes'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Alerts Section (if any) --}}
    @if($indicadores['manutencoes_vencidas'] > 0)
    <div class="bg-red-50 border border-red-100 p-4 rounded-2xl shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0 bg-red-100 rounded-full p-1">
                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3 w-full min-w-0">
                <h3 class="text-sm font-bold text-red-800">Atenção Necessária</h3>
                <p class="text-sm text-red-700 mt-1">
                    Você tem <span class="font-bold">{{ $indicadores['manutencoes_vencidas'] }} manutenções vencidas</span>.
                </p>
            </div>
        </div>
    </div>
    @endif

</div>
