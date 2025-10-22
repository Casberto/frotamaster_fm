<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    {{-- Veículos Ativos --}}
    <a href="{{ route('veiculos.index', ['status' => 1]) }}"
        class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition group">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-green-100 rounded-full">
                {{-- Ícone de carro (simples e limpo) --}}
                <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 13.5h19.5M5.25 13.5V9.75a2.25 2.25 0 0 1 2.25-2.25h9a2.25 2.25 0 0 1 2.25 2.25V13.5M5.25 13.5a2.25 2.25 0 1 0 4.5 0m9 0a2.25 2.25 0 1 0 4.5 0" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Veículos Ativos</h3>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $veiculosAtivosCount ?? 0 }}</p>
            </div>
        </div>
    </a>

    {{-- Manutenções Vencidas --}}
    <button type="button" @click="$dispatch('open-modal', 'manutencoes-vencidas')"
        class="w-full p-4 bg-white rounded-lg shadow hover:shadow-md transition group text-left {{ ($manutencoesVencidasCount ?? 0) > 0 ? 'ring-2 ring-red-400' : '' }}">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Manutenções Vencidas</h3>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $manutencoesVencidasCount ?? 0 }}</p>
            </div>
        </div>
    </button>

    {{-- Alertas Próximos (15 dias) --}}
    <button type="button" @click="$dispatch('open-modal', 'alertas-proximos')"
        class="w-full p-4 bg-white rounded-lg shadow hover:shadow-md transition group text-left {{ ($alertasProximosCount ?? 0) > 0 ? 'ring-2 ring-yellow-400' : '' }}">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-yellow-100 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Alertas (15d)</h3>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $alertasProximosCount ?? 0 }}</p>
            </div>
        </div>
    </button>

    {{-- Custos do Mês --}}
    <button type="button" @click="$dispatch('open-modal', 'custos-mensais')"
        class="w-full p-4 bg-white rounded-lg shadow hover:shadow-md transition group text-left">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-100 rounded-full">
                {{-- Ícone de gráfico de barras (visual e harmônico com o dashboard) --}}
                <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3v18h18M7.5 14.25v3.75m4.5-7.5v7.5m4.5-10.5v10.5" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Custos do Mês</h3>
                <p class="mt-1 text-2xl font-bold text-gray-900">
                    R$ {{ number_format($totalGastoMes ?? 0, 2, ',', '.') }}
                </p>
            </div>
        </div>
    </button>

</div>
