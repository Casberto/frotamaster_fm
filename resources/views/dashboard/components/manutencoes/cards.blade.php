<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Vencidas --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Vencidas</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $maintenanceData['cards']['vencidas'] ?? 0 }}</p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Em Andamento --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Em Andamento</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $maintenanceData['cards']['em_andamento'] ?? 0 }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.703-.127 1.25.14 2.973.696 3.76 1.964l1.896-1.897L8.032 3.332l-1.897 1.896c1.268.787 1.824 2.51 1.964 3.76.061.54.037 1.153-.127 1.703m0 0l2.16 2.16a2.49 2.49 0 01-2.16 2.16m9.555-9.555l-2.16 2.16a2.49 2.49 0 012.16-2.16z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Previstas (Futuras) --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Previstas</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $maintenanceData['cards']['previstas'] ?? 0 }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Por Tipo (Resumo) --}}
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-600 mb-2">Por Tipo (Mês Atual)</p>
                <div class="text-xs space-y-1">
                    @foreach($maintenanceData['cards']['por_tipo'] as $tipo => $qtd)
                        <div class="flex justify-between">
                            <span class="capitalize truncate">{{ str_replace('_', ' ', $tipo) }}</span>
                            <span class="font-bold ml-2">{{ $qtd }}</span>
                        </div>
                    @endforeach
                    @if(empty($maintenanceData['cards']['por_tipo']))
                        <span class="text-gray-400 italic">Sem dados no mês</span>
                    @endif
                </div>
            </div>
            <div class="bg-green-100 p-3 rounded-full flex-shrink-0 ml-3">
                <svg class="w-8 h-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>
        </div>
    </div>
</div>
