<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">

    {{-- Motoristas Ativos --}}
    <a href="{{ route('motoristas.index', ['status' => 1]) }}" class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition group text-left w-full">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-green-100 rounded-full">
                <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 group-hover:text-gray-700">Ativos</p>
                <p class="text-2xl font-bold text-gray-900 group-hover:text-green-600">{{ $motoristasAtivosCount ?? 0 }}</p>
            </div>
        </div>
    </a>

    {{-- Motoristas Bloqueados --}}
    <button type="button" @click="$dispatch('open-modal', 'motoristas-bloqueados')" class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition group text-left w-full">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-gray-100 rounded-full">
                <svg class="w-5 h-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 group-hover:text-gray-700">Bloqueados</p>
                <p class="text-2xl font-bold text-gray-900 group-hover:text-gray-600">{{ $motoristasBloqueadosCount ?? 0 }}</p>
            </div>
        </div>
    </button>

    {{-- CNH Vencida --}}
     <button type="button" @click="$dispatch('open-modal', 'motoristas-cnh-vencida')" class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition group text-left w-full {{ ($motoristasCnhVencidaCount ?? 0) > 0 ? 'border-l-4 border-red-500' : '' }}">
        <div class="flex items-center space-x-3">
            <div class="p-2 {{ ($motoristasCnhVencidaCount ?? 0) > 0 ? 'bg-red-100' : 'bg-orange-100' }} rounded-full">
                <svg class="w-5 h-5 {{ ($motoristasCnhVencidaCount ?? 0) > 0 ? 'text-red-600' : 'text-orange-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 group-hover:text-gray-700">CNH Vencida</p>
                <p class="text-2xl font-bold {{ ($motoristasCnhVencidaCount ?? 0) > 0 ? 'text-red-600' : 'text-gray-900' }} group-hover:{{ ($motoristasCnhVencidaCount ?? 0) > 0 ? 'text-red-700' : 'text-orange-600' }}">{{ $motoristasCnhVencidaCount ?? 0 }}</p>
            </div>
        </div>
    </button>

     {{-- CNH a Vencer --}}
     <button type="button" @click="$dispatch('open-modal', 'motoristas-cnh-a-vencer')" class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition group text-left w-full {{ ($motoristasCnhAVencerCount ?? 0) > 0 ? 'border-l-4 border-yellow-400' : '' }}">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-yellow-100 rounded-full">
                <svg class="w-5 h-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 group-hover:text-gray-700">CNH a Vencer (30d)</p>
                <p class="text-2xl font-bold {{ ($motoristasCnhAVencerCount ?? 0) > 0 ? 'text-yellow-600' : 'text-gray-900' }} group-hover:{{ ($motoristasCnhAVencerCount ?? 0) > 0 ? 'text-yellow-700' : 'text-yellow-600' }}">{{ $motoristasCnhAVencerCount ?? 0 }}</p>
            </div>
        </div>
    </button>

     {{-- Novos no Mês --}}
     <button type="button" @click="$dispatch('open-modal', 'novos-motoristas-mes')" class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition group text-left w-full">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-100 rounded-full">
                 <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 group-hover:text-gray-700">Novos no Mês</p>
                <p class="text-2xl font-bold text-gray-900 group-hover:text-blue-600">{{ $novosMotoristasMesCount ?? 0 }}</p>
            </div>
        </div>
    </button>

    {{-- Em Treinamento --}}
     <button type="button" @click="$dispatch('open-modal', 'motoristas-em-treinamento')" class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition group text-left w-full">
        <div class="flex items-center space-x-3">
             <div class="p-2 bg-purple-100 rounded-full">
                <svg class="w-5 h-5 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 group-hover:text-gray-700">Em Treinamento</p>
                <p class="text-2xl font-bold text-gray-900 group-hover:text-purple-600">{{ $motoristasEmTreinamentoCount ?? 0 }}</p>
            </div>
        </div>
    </button>
</div>
