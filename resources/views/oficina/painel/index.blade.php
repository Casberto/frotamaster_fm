<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Oficina / Painel de Serviços') }}
            </h2>
            <a href="{{ route('oficina.os.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                + Nova OS
            </a>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ 
        activeStatus: 'aguardando',
        ordens: {{ $ordens->toJson() }},
        statusList: {{ json_encode($statusMap) }},
        
        // Função para contar quantos itens tem em cada status
        countByStatus(status) {
            return this.ordens.filter(os => os.osv_status === status).length;
        },

        // Filtra a lista atual
        get filteredOrdens() {
            return this.ordens.filter(os => os.osv_status === this.activeStatus);
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 overflow-x-auto pb-2 scrollbar-hide">
                <div class="flex space-x-3 sm:grid sm:grid-cols-7 sm:space-x-0 sm:gap-4">
                    
                    <template x-for="(config, key) in statusList" :key="key">
                        <button 
                            @click="activeStatus = key"
                            class="relative bg-white p-3 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 text-left border overflow-hidden group flex-shrink-0 w-36 sm:w-auto"
                            :class="{ 
                                'ring-2 ring-blue-500 ring-offset-2': activeStatus === key,
                                'border-gray-200': activeStatus !== key
                            }"
                        >
                            <!-- Colored Left Border -->
                            <div class="absolute left-0 top-0 bottom-0 w-1.5" :class="'bg-' + config.color + '-500'"></div>
                            
                            <div class="flex items-center justify-between pl-2">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 block truncate" x-text="config.label"></span>
                                    <div class="text-2xl font-black text-gray-800 mt-1" x-text="countByStatus(key)"></div>
                                </div>
                                
                                <div class="w-8 h-8 rounded-full flex items-center justify-center transition-colors flex-shrink-0"
                                    :class="'bg-' + config.color + '-50 text-' + config.color + '-600'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="config.icon"></path>
                                    </svg>
                                </div>
                            </div>
                        </button>
                    </template>

                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg min-h-[400px]">
                <div class="p-4 sm:p-6 bg-gray-50 border-b border-gray-200">
                    
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-700 flex items-center">
                            <span class="w-3 h-3 rounded-full mr-2" :class="'bg-' + statusList[activeStatus].color + '-500'"></span>
                            <span x-text="statusList[activeStatus].label"></span>
                        </h3>
                        <span class="text-sm text-gray-500" x-text="filteredOrdens.length + ' veículos'"></span>
                    </div>

                    <div x-show="filteredOrdens.length === 0" class="text-center py-10 text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <p>Nenhum veículo nesta etapa.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <template x-for="os in filteredOrdens" :key="os.osv_id">
                            @include('oficina.painel.partials.os-card')
                        </template>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
