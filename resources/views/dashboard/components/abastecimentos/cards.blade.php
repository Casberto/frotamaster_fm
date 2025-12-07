{{-- Filter Section --}}
<div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
    <div class="flex items-center space-x-2">
        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
        <span class="text-sm font-bold text-gray-700 uppercase">Filtrar Período</span>
    </div>
    <form action="{{ route('dashboard') }}" method="GET" class="flex items-center space-x-4">
        <input type="hidden" name="tab" value="abastecimentos">
        <div class="flex items-center space-x-2">
            <label class="text-xs text-gray-500 font-bold uppercase">De</label>
            <input type="date" name="start_date" value="{{ $filterStartDate ?? '' }}" class="text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div class="flex items-center space-x-2">
            <label class="text-xs text-gray-500 font-bold uppercase">Até</label>
            <input type="date" name="end_date" value="{{ $filterEndDate ?? '' }}" class="text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <button type="submit" class="bg-blue-600 text-white text-sm font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">Aplicar</button>
        @if(request('start_date') || request('end_date'))
            <a href="{{ route('dashboard', ['tab' => 'abastecimentos']) }}" class="text-gray-500 hover:text-red-600 text-sm flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Limpar
            </a>
        @endif
    </form>
</div>

{{-- 4.1 - Cards de Indicadores --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    
    {{-- 1. Custo Total do Mês --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Custo Total do Mês</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    R$ {{ number_format($fuelingData['indicadores']['custo_total_mes'] ?? 0, 2, ',', '.') }}
                </p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- 2. Quantidade Abastecida --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Quantidade Abastecida</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    {{ number_format($fuelingData['indicadores']['quantidade_abastecida'] ?? 0, 2, ',', '.') }} L
                </p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- 3. Média Geral (km/L) --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Média Geral</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    {{ number_format($fuelingData['indicadores']['media_geral_consumo'] ?? 0, 2, ',', '.') }} km/L
                </p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- 4. Veículo Mais Gastador --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-600 mb-1">Veículo Mais Gastador</p>
                @if(isset($fuelingData['indicadores']['veiculo_mais_gastador']['veiculo']))
                    <p class="text-lg font-bold text-gray-900 truncate">
                        {{ $fuelingData['indicadores']['veiculo_mais_gastador']['veiculo']->vei_placa }}
                    </p>
                    <p class="text-sm text-gray-500">
                        R$ {{ number_format($fuelingData['indicadores']['veiculo_mais_gastador']['valor'], 2, ',', '.') }}
                    </p>
                @else
                    <p class="text-sm text-gray-500">Sem dados</p>
                @endif
            </div>
            <div class="bg-red-100 p-3 rounded-full flex-shrink-0">
                <svg class="w-8 h-8 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
                    <path fill="currentColor" d="M240 112h-10.8l-27.78-62.5A16 16 0 0 0 186.8 40H69.2a16 16 0 0 0-14.62 9.5L26.8 112H16a8 8 0 0 0 0 16h8v80a16 16 0 0 0 16 16h24a16 16 0 0 0 16-16v-16h96v16a16 16 0 0 0 16 16h24a16 16 0 0 0 16-16v-80h8a8 8 0 0 0 0-16ZM69.2 56h117.6l24.89 56H44.31ZM64 208H40v-16h24Zm128 0v-16h24v16Zm24-32H40v-48h176ZM56 152a8 8 0 0 1 8-8h16a8 8 0 0 1 0 16H64a8 8 0 0 1-8-8Zm112 0a8 8 0 0 1 8-8h16a8 8 0 0 1 0 16h-16a8 8 0 0 1-8-8Z"/>
                </svg>
            </div>
        </div>
    </div>

</div>
