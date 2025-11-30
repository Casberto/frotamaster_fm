<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Veículos Ativos -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Veículos Ativos</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    {{ $indicadores['veiculos_ativos'] }} <span class="text-sm text-gray-400">/ {{ $indicadores['veiculos_total'] }}</span>
                </p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
                    <path fill="currentColor" d="M240 112h-10.8l-27.78-62.5A16 16 0 0 0 186.8 40H69.2a16 16 0 0 0-14.62 9.5L26.8 112H16a8 8 0 0 0 0 16h8v80a16 16 0 0 0 16 16h24a16 16 0 0 0 16-16v-16h96v16a16 16 0 0 0 16 16h24a16 16 0 0 0 16-16v-80h8a8 8 0 0 0 0-16ZM69.2 56h117.6l24.89 56H44.31ZM64 208H40v-16h24Zm128 0v-16h24v16Zm24-32H40v-48h176ZM56 152a8 8 0 0 1 8-8h16a8 8 0 0 1 0 16H64a8 8 0 0 1-8-8Zm112 0a8 8 0 0 1 8-8h16a8 8 0 0 1 0 16h-16a8 8 0 0 1-8-8Z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Motoristas Ativos -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Motoristas Ativos</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    {{ $indicadores['motoristas_ativos'] }}
                </p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Manutenções -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 {{ $indicadores['manutencoes_vencidas'] > 0 ? 'border-red-500' : 'border-yellow-500' }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Manutenções</p>
                <p class="text-2xl font-bold {{ $indicadores['manutencoes_vencidas'] > 0 ? 'text-red-600' : 'text-gray-900' }} mt-1">
                    {{ $indicadores['manutencoes_vencidas'] }} <span class="text-sm {{ $indicadores['manutencoes_vencidas'] > 0 ? 'text-red-400' : 'text-gray-400' }}">Vencidas</span>
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $indicadores['manutencoes_andamento'] }} Em andamento
                </p>
            </div>
            <div class="bg-{{ $indicadores['manutencoes_vencidas'] > 0 ? 'red' : 'yellow' }}-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-{{ $indicadores['manutencoes_vencidas'] > 0 ? 'red' : 'yellow' }}-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.703-.127 1.25.14 2.973.696 3.76 1.964l1.896-1.897L8.032 3.332l-1.897 1.896c1.268.787 1.824 2.51 1.964 3.76.061.54.037 1.153-.127 1.703m0 0l2.16 2.16a2.49 2.49 0 01-2.16 2.16m9.555-9.555l-2.16 2.16a2.49 2.49 0 012.16-2.16z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Custos do Mês -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Custo Total (Mês)</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    R$ {{ number_format($indicadores['custo_total_mes'], 2, ',', '.') }}
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Médio: R$ {{ number_format($indicadores['custo_medio_veiculo'], 2, ',', '.') }} / veic.
                </p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                </svg>
            </div>
        </div>
    </div>
</div>
