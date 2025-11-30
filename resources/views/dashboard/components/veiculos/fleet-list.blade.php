{{-- resources/views/dashboard/components/veiculos/fleet-list.blade.php --}}
<div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm" x-data="{
    search: '',
    statusFilter: 'all',
    veiculos: {{ json_encode($frota ?? []) }},
    get filteredVeiculos() {
        return this.veiculos.filter(v => {
            const matchesSearch = (v.vei_placa.toLowerCase().includes(this.search.toLowerCase()) || 
                                 v.vei_modelo.toLowerCase().includes(this.search.toLowerCase()));
            const matchesStatus = this.statusFilter === 'all' || v.vei_status == this.statusFilter;
            return matchesSearch && matchesStatus;
        });
    },
    formatCurrency(value) {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value || 0);
    }
}">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h3 class="text-lg font-semibold text-gray-800">Frota de Veículos</h3>
        
        <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
            {{-- Search --}}
            <div class="relative">
                <input type="text" x-model="search" placeholder="Buscar placa ou modelo..." 
                       class="pl-10 pr-4 py-2 border rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 w-full md:w-64">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            {{-- Filter --}}
            <select x-model="statusFilter" class="border rounded-lg text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                <option value="all">Todos os Status</option>
                <option value="1">Ativo</option>
                <option value="2">Inativo</option>
                <option value="3">Em Manutenção</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Veículo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ano</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Odômetro</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Custo Mês</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="veiculo in filteredVeiculos" :key="veiculo.vei_id">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xs">
                                    <span x-text="veiculo.vei_placa.substring(0,3)"></span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900" x-text="veiculo.vei_placa"></div>
                                    <div class="text-sm text-gray-500" x-text="veiculo.vei_modelo"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="veiculo.vei_ano_fab + '/' + veiculo.vei_ano_mod"></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                  :class="{
                                    'bg-green-100 text-green-800': veiculo.vei_status == 1,
                                    'bg-red-100 text-red-800': veiculo.vei_status == 2,
                                    'bg-yellow-100 text-yellow-800': veiculo.vei_status == 3
                                  }">
                                <span x-text="veiculo.vei_status == 1 ? 'Ativo' : (veiculo.vei_status == 2 ? 'Inativo' : 'Manutenção')"></span>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="veiculo.vei_km_atual ? parseInt(veiculo.vei_km_atual).toLocaleString('pt-BR') + ' km' : '-'"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium" x-text="formatCurrency(veiculo.custo_total_mensal)"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button @click="$dispatch('open-detalhes-veiculo', { id: veiculo.vei_id })" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition">
                                Detalhes
                            </button>
                        </td>
                    </tr>
                </template>
                <template x-if="filteredVeiculos.length === 0">
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            Nenhum veículo encontrado com os filtros atuais.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>

