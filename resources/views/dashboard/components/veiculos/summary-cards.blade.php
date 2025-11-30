<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    {{-- Veículos Ativos --}}
    <a href="{{ route('veiculos.index', ['status' => 1]) }}"
        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Veículos Ativos</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $veiculosAtivosCount ?? 0 }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
                    <path fill="currentColor" d="M240 112h-10.8l-27.78-62.5A16 16 0 0 0 186.8 40H69.2a16 16 0 0 0-14.62 9.5L26.8 112H16a8 8 0 0 0 0 16h8v80a16 16 0 0 0 16 16h24a16 16 0 0 0 16-16v-16h96v16a16 16 0 0 0 16 16h24a16 16 0 0 0 16-16v-80h8a8 8 0 0 0 0-16ZM69.2 56h117.6l24.89 56H44.31ZM64 208H40v-16h24Zm128 0v-16h24v16Zm24-32H40v-48h176ZM56 152a8 8 0 0 1 8-8h16a8 8 0 0 1 0 16H64a8 8 0 0 1-8-8Zm112 0a8 8 0 0 1 8-8h16a8 8 0 0 1 0 16h-16a8 8 0 0 1-8-8Z"/>
                </svg>
            </div>
        </div>
    </a>

    {{-- Manutenções Vencidas --}}
    <button type="button" @click="$dispatch('open-modal', 'manutencoes-vencidas')"
        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500 hover:shadow-md transition text-left w-full">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Manutenções Vencidas</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $manutencoesVencidasCount ?? 0 }}</p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
        </div>
    </button>

    {{-- Manutenções em Andamento --}}
    <button type="button" @click="$dispatch('open-modal', 'manutencoes-em-andamento')"
        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500 hover:shadow-md transition text-left w-full">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Em Andamento</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $manutencoesEmAndamentoCount ?? 0 }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                </svg>
            </div>
        </div>
    </button>

    {{-- Alertas Próximos (15 dias) --}}
    <button type="button" @click="$dispatch('open-modal', 'alertas-proximos')"
        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500 hover:shadow-md transition text-left w-full">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Alertas (15d)</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $alertasProximosCount ?? 0 }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>
    </button>

    {{-- Custos do Mês --}}
    <button type="button" @click="$dispatch('open-modal', 'custos-mensais')"
        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500 hover:shadow-md transition text-left w-full">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Custos do Mês</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    R$ {{ number_format($totalGastoMes ?? 0, 2, ',', '.') }}
                </p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>
        </div>
    </button>

</div>
