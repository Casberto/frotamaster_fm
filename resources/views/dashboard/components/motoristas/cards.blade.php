<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    {{-- Motoristas Ativos --}}
    <a href="{{ route('motoristas.index', ['status' => 'Ativo']) }}"
        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Motoristas Ativos</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $motoristasAtivosCount ?? 0 }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
        </div>
    </a>

    {{-- CNH Vencida --}}
    <button type="button" @click="$dispatch('open-modal', 'motoristas-cnh-vencida')"
        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500 hover:shadow-md transition text-left w-full">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">CNH Vencida</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $motoristasCnhVencidaCount ?? 0 }}</p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
            </div>
        </div>
    </button>

    {{-- CNH a Vencer --}}
    <button type="button" @click="$dispatch('open-modal', 'motoristas-cnh-a-vencer')"
        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500 hover:shadow-md transition text-left w-full">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">CNH a Vencer (30d)</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $motoristasCnhAVencerCount ?? 0 }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>
    </button>

    {{-- Motoristas Bloqueados --}}
    <button type="button" @click="$dispatch('open-modal', 'motoristas-bloqueados')"
        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-orange-500 hover:shadow-md transition text-left w-full">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Bloqueados/Inativos</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $motoristasBloqueadosCount ?? 0 }}</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
        </div>
    </button>
</div>
