{{-- resources/views/dashboard/components/summary-cards.blade.php --}}
{{-- Este componente exibe os principais KPIs em cards interativos. --}}

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    
    <!-- Veículos Ativos -->
    <a href="{{ route('veiculos.index', ['status' => 1]) }}" class="block transition duration-300 ease-in-out hover:shadow-lg hover:-translate-y-1">
        <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4 h-full">
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h6m-6 4h6m-6 4h6"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Veículos Ativos</h3>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $veiculosAtivos }}</p>
            </div>
        </div>
    </a>

    <!-- Manutenções Vencidas -->
    <a href="{{ route('manutencoes.index', ['status' => 'vencida']) }}" class="block transition duration-300 ease-in-out hover:shadow-lg hover:-translate-y-1">
        <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4 h-full">
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Manutenções Vencidas</h3>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $manutencoesVencidas }}</p>
            </div>
        </div>
    </a>

    <!-- Alertas Próximos (7 dias) -->
    <a href="{{ route('manutencoes.index', ['status' => 'agendada']) }}" class="block transition duration-300 ease-in-out hover:shadow-lg hover:-translate-y-1">
        <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4 h-full">
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /> </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Alertas Próximos</h3>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $alertasProximos }}</p>
            </div>
        </div>
    </a>

    <!-- Custo Total do Mês -->
    <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
        <div class="bg-green-100 p-3 rounded-full">
            <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /> </svg>
        </div>
        <div>
            <h3 class="text-sm font-medium text-gray-500">Custo Total do Mês</h3>
            <p class="mt-1 text-3xl font-bold text-gray-900">R$ {{ number_format($custoTotalMensal, 2, ',', '.') }}</p>
        </div>
    </div>
</div>

