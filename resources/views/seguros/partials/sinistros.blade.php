<div class="space-y-6">
    {{-- Lista de Sinistros --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Histórico de Sinistros
            </h3>
            @if(Auth::user()->temPermissao('SEG008'))
            <button onclick="document.getElementById('modal-sinistro').showModal()" class="text-sm bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                + Registrar Sinistro
            </button>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prejuízo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coberto</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($apolice->sinistros as $sinistro)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $sinistro->ssi_data ? $sinistro->ssi_data->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $sinistro->ssi_tipo }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $sinistro->ssi_status == 'Concluído' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $sinistro->ssi_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $sinistro->ssi_valor_prejuizo ? 'R$ ' . number_format($sinistro->ssi_valor_prejuizo, 2, ',', '.') : '-' }}
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $sinistro->ssi_valor_coberto ? 'R$ ' . number_format($sinistro->ssi_valor_coberto, 2, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            {{-- Edição via modal seria ideal, mas simplificaremos --}}
                            @if(Auth::user()->temPermissao('SEG010'))
                            <form action="{{ route('sinistros.destroy', $sinistro->ssi_id) }}" method="POST" class="inline" onsubmit="return confirm('Excluir este sinistro?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Excluir</button>
                            </form>
                            @endif
                            @if(Auth::user()->temPermissao('SEG009'))
                            <button onclick="document.getElementById('modal-editar-sinistro-{{ $sinistro->ssi_id }}').showModal()" class="text-blue-600 hover:text-blue-900 ml-2">Editar</button>
                            <button onclick="document.getElementById('modal-fotos-{{ $sinistro->ssi_id }}').showModal()" class="text-indigo-600 hover:text-indigo-900 ml-2">Fotos</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum sinistro registrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Criar Sinistro --}}
    <dialog id="modal-sinistro" class="p-0 rounded-lg shadow-xl w-full max-w-lg backdrop:bg-gray-500/50">
        <form action="{{ route('sinistros.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <input type="hidden" name="ssi_seg_id" value="{{ $apolice->seg_id }}">
            
            <h3 class="text-lg font-medium text-gray-900 mb-4">Registrar Sinistro</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Data do Ocorrido</label>
                    <input type="date" name="ssi_data" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo</label>
                    <select name="ssi_tipo" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="Colisão">Colisão</option>
                        <option value="Roubo/Furto">Roubo/Furto</option>
                        <option value="Danos a Terceiros">Danos a Terceiros</option>
                        <option value="Causas Naturais">Causas Naturais</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Valor do Prejuízo (R$)</label>
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
                        <input type="hidden" name="ssi_valor_prejuizo" x-model="raw">
                    </div>
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
                        <input type="hidden" name="ssi_valor_coberto" x-model="raw">
                    </div>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Status Atual</label>
                     <select name="ssi_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="Em análise">Em análise</option>
                        <option value="Aguardando Documentação">Aguardando Documentação</option>
                        <option value="Aprovado">Aprovado</option>
                        <option value="Negado">Negado</option>
                        <option value="Concluído">Concluído</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Observações</label>
                    <textarea name="ssi_obs" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>
                
                {{-- Upload Simples (se controller suportar array) --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Anexos (Imagens/PDF)</label>
                    <input type="file" name="anexos[]" multiple class="mt-1 block w-full text-sm text-gray-500
                      file:mr-4 file:py-2 file:px-4
                      file:rounded-full file:border-0
                      file:text-sm file:font-semibold
                      file:bg-indigo-50 file:text-indigo-700
                      hover:file:bg-indigo-100">
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modal-sinistro').close()" class="btn-secondary">Cancelar</button>
                <button type="submit" class="btn-primary">Registrar</button>
            </div>
        </form>
    </dialog>

    @foreach($apolice->sinistros as $sinistro)
    <dialog id="modal-fotos-{{ $sinistro->ssi_id }}" class="p-0 rounded-lg shadow-xl w-full max-w-4xl backdrop:bg-gray-500/50">
        <div class="relative bg-white rounded-lg p-4">
            <button onclick="document.getElementById('modal-fotos-{{ $sinistro->ssi_id }}').close()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 z-10">
                <span class="sr-only">Fechar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="mt-8">
                <x-seguros.sinistro-photo-gallery :sinistroId="$sinistro->ssi_id" />
            </div>
        </div>
    </dialog>

    <dialog id="modal-editar-sinistro-{{ $sinistro->ssi_id }}" class="p-0 rounded-lg shadow-xl w-full max-w-lg backdrop:bg-gray-500/50">
        <form action="{{ route('sinistros.update', $sinistro->ssi_id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <h3 class="text-lg font-medium text-gray-900 mb-4">Editar Sinistro</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Data do Ocorrido</label>
                    <input type="date" name="ssi_data" value="{{ $sinistro->ssi_data ? $sinistro->ssi_data->format('Y-m-d') : '' }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo</label>
                    <select name="ssi_tipo" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="Colisão" {{ $sinistro->ssi_tipo == 'Colisão' ? 'selected' : '' }}>Colisão</option>
                        <option value="Roubo/Furto" {{ $sinistro->ssi_tipo == 'Roubo/Furto' ? 'selected' : '' }}>Roubo/Furto</option>
                        <option value="Danos a Terceiros" {{ $sinistro->ssi_tipo == 'Danos a Terceiros' ? 'selected' : '' }}>Danos a Terceiros</option>
                        <option value="Causas Naturais" {{ $sinistro->ssi_tipo == 'Causas Naturais' ? 'selected' : '' }}>Causas Naturais</option>
                        <option value="Outro" {{ $sinistro->ssi_tipo == 'Outro' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Valor do Prejuízo (R$)</label>
                    <div x-data="{
                        raw: '{{ $sinistro->ssi_valor_prejuizo }}',
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
                        <input type="hidden" name="ssi_valor_prejuizo" x-model="raw">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Valor Coberto (R$)</label>
                    <div x-data="{
                        raw: '{{ $sinistro->ssi_valor_coberto }}',
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
                        <input type="hidden" name="ssi_valor_coberto" x-model="raw">
                    </div>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Status Atual</label>
                     <select name="ssi_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="Em análise" {{ $sinistro->ssi_status == 'Em análise' ? 'selected' : '' }}>Em análise</option>
                        <option value="Aguardando Documentação" {{ $sinistro->ssi_status == 'Aguardando Documentação' ? 'selected' : '' }}>Aguardando Documentação</option>
                        <option value="Aprovado" {{ $sinistro->ssi_status == 'Aprovado' ? 'selected' : '' }}>Aprovado</option>
                        <option value="Negado" {{ $sinistro->ssi_status == 'Negado' ? 'selected' : '' }}>Negado</option>
                        <option value="Concluído" {{ $sinistro->ssi_status == 'Concluído' ? 'selected' : '' }}>Concluído</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Observações</label>
                    <textarea name="ssi_obs" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $sinistro->ssi_obs }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modal-editar-sinistro-{{ $sinistro->ssi_id }}').close()" class="btn-secondary">Cancelar</button>
                <button type="submit" class="btn-primary">Salvar Alterações</button>
            </div>
        </form>
    </dialog>
    @endforeach
</div>
