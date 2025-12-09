<div class="space-y-6">
    {{-- Lista de Coberturas --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Coberturas Contratadas
            </h3>
            @if(Auth::user()->temPermissao('SEG005'))
            <button onclick="document.getElementById('modal-cobertura').showModal()" class="text-sm bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">
                + Adicionar Cobertura
            </button>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Coberto</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($apolice->coberturas as $cobertura)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cobertura->sco_titulo }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $cobertura->sco_descricao }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $cobertura->sco_valor ? 'R$ ' . number_format($cobertura->sco_valor, 2, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if(Auth::user()->temPermissao('SEG006'))
                            <form action="{{ route('coberturas.destroy', $cobertura->sco_id) }}" method="POST" onsubmit="return confirm('Excluir esta cobertura?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Excluir</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Nenhuma cobertura cadastrada.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal de Adicionar Cobertura (HTML nativo dialog ou Alpine/Tailwind modal) --}}
    {{-- Vamos usar um dialog nativo simples estilizado ou hidden div com alpine --}}
    <dialog id="modal-cobertura" class="p-0 rounded-lg shadow-xl w-full max-w-md backdrop:bg-gray-500/50">
        <form action="{{ route('coberturas.store') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="sco_seg_id" value="{{ $apolice->seg_id }}">
            
            <h3 class="text-lg font-medium text-gray-900 mb-4">Nova Cobertura</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Título</label>
                    <input type="text" name="sco_titulo" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Descrição</label>
                    <textarea name="sco_descricao" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Valor Coberto (R$)</label>
                    <div x-data="{
                        raw: '',
                        display: '',
                        format(value) {
                            if (!value) return '';
                            let number = parseFloat(value).toFixed(2);
                            return number.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        },
                        init() {
                            if (this.raw) {
                                this.display = this.format(this.raw);
                            }
                        },
                        input(e) {
                            let value = e.target.value.replace(/\D/g, '');
                            if (!value) {
                                this.raw = '';
                                this.display = '';
                                return;
                            }
                            let floatVal = parseFloat(value) / 100;
                            this.raw = floatVal.toFixed(2);
                            this.display = this.format(this.raw);
                            e.target.value = this.display;
                        }
                    }" x-init="init">
                        <input type="text" x-model="display" @input="input" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="0,00">
                        <input type="hidden" name="sco_valor" x-model="raw">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modal-cobertura').close()" class="btn-secondary">Cancelar</button>
                <button type="submit" class="btn-primary">Salvar</button>
            </div>
        </form>
    </dialog>
</div>
