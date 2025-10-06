{{-- resources/views/dashboard/components/analysis-cards.blade.php --}}
{{-- Este componente exibe os cards secundários de análise. --}}

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
     <!-- Top Fornecedor -->
    <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
        <div class="bg-indigo-100 p-3 rounded-full">
            <svg class="w-6 h-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.25m11.25 0v-7.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21m-4.5 0H9M15 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H4.5m15 0v-7.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21m-4.5 0H12" /> </svg>
        </div>
        <div>
            <h3 class="text-sm font-medium text-gray-500">Top Fornecedor (Mês)</h3>
            <p class="mt-1 text-xl font-semibold text-gray-800 truncate">{{ $topFornecedor }}</p>
        </div>
    </div>
    <!-- Serviço Mais Frequente -->
    <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
         <div class="bg-purple-100 p-3 rounded-full">
            <svg class="w-6 h-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-1.007 1.11-1.11h2.592c.55-0.104 1.02.365 1.11 1.11l.09 1.586c.27.043.53.11.78.201l1.467-.77c.504-0.267 1.12.11 1.32.618l1.34 2.322c.2.507-.06.108-.48.134l-1.14.395c.09.262.16.533.22.811l.395 1.14c.254.524-.08.113-.59.132l-2.323 1.342c-.507.2-.618.823-.134 1.32l.77 1.467c.09.25.158.51.202.78l1.586.09c.542.09 1.008.56 1.11 1.11v2.592c.104.55-.365 1.02-1.11 1.11l-1.586.09c-.043.27-.11.53-.202.78l.77 1.467c.267.504-.11 1.12-.618 1.32l-2.323 1.34c-.507.2-.108-.06-1.34-.48l-.395-1.14a8.21 8.21 0 01-.81.22l-1.14.395c-.524.254-1.13-.08-1.32-.59l-1.342-2.323c-.2-.507.06-.618.48-1.34l1.14-.395a8.21 8.21 0 01-.22-.81l-.395-1.14c-.254-.524.08-1.13.59-1.32l2.323-1.342c.507-.2.618-.823.134-1.32l-.77-1.467c-.09-.25-.158-.51-.202-.78l-1.586-.09c-.542-.09-1.008-.56-1.11-1.11V6.65c-.104-.55.365-1.02 1.11-1.11h1.586c.27-.043.53-.11.78-.201L9.594 3.94zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" /> </svg>
        </div>
        <div>
            <h3 class="text-sm font-medium text-gray-500">Serviço Mais Frequente</h3>
            <p class="mt-1 text-xl font-semibold text-gray-800 truncate">{{ $servicoMaisFrequente }}</p>
        </div>
    </div>
     <!-- Custo Médio por KM -->
    <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
         <div class="bg-cyan-100 p-3 rounded-full">
            <svg class="w-6 h-6 text-cyan-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"> <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.75A.75.75 0 013 4.5h.75m0 0H21m-18 0h18M3 6.75h18M3 9h18m-18 2.25h18m-18 2.25h18m-18 2.25h18M3 16.5h18m-18-3.75h18m-18-3.75h18m-18-3.75h18" /> </svg>
        </div>
        <div>
            <h3 class="text-sm font-medium text-gray-500">Custo Médio por KM</h3>
            <p class="mt-1 text-xl font-semibold text-gray-800">R$ {{ number_format($custoMedioPorKm, 2, ',', '.') }}</p>
        </div>
    </div>
</div>
