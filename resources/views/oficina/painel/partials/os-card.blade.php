<div class="bg-white border rounded-xl shadow-sm hover:shadow-md transition-shadow p-4 relative border-l-4"
    :class="{
        'border-l-red-500': os.osv_prioridade === 'urgente',
        'border-l-orange-400': os.osv_prioridade === 'alta',
        'border-l-blue-400': os.osv_prioridade === 'normal',
        'border-l-gray-300': os.osv_prioridade === 'baixa',
    }">
    
    <div class="flex justify-between items-start mb-2">
        <div>
            <h4 class="font-black text-lg text-gray-800" x-text="os.veiculo.vct_placa"></h4>
            <p class="text-xs text-gray-500 font-medium" x-text="os.veiculo.vct_modelo"></p>
        </div>
        <div class="text-right">
            <span class="text-xs font-bold px-2 py-1 rounded bg-gray-100 text-gray-600" x-text="'#' + os.osv_codigo"></span>
        </div>
    </div>

    <div class="flex items-center mb-3 text-sm text-gray-600">
        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        <span class="truncate" x-text="os.cliente.clo_nome"></span>
        <template x-if="os.cliente.clo_vip">
            <span class="ml-2 text-xs bg-yellow-100 text-yellow-700 px-1 rounded border border-yellow-200 font-bold">VIP</span>
        </template>
    </div>

    <div class="bg-gray-50 p-2 rounded text-xs text-gray-700 mb-3 border border-gray-100 italic line-clamp-2">
        "<span x-text="os.osv_problema_relatado || 'Sem descrição inicial'"></span>"
    </div>

    <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-100">
        <span class="text-xs text-gray-400" x-text="new Date(os.created_at).toLocaleDateString('pt-BR')"></span>
        
        <a :href="'/oficina/os/' + os.osv_id" class="text-sm font-bold text-blue-600 hover:text-blue-800 flex items-center">
            Abrir OS
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>
</div>
