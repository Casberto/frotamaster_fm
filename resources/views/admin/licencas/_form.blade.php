@csrf
<div class="space-y-6" x-data="licenseForm()">
    {{-- Linha 1: Empresa e Plano --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="id_empresa" :value="__('Empresa')" />
            <select id="id_empresa" name="id_empresa" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">Selecione uma empresa</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id }}" {{ old('id_empresa', $licenca->id_empresa ?? '') == $empresa->id ? 'selected' : '' }}>
                        {{ $empresa->nome_fantasia }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('id_empresa')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="plano" :value="__('Plano')" />
            <select id="plano" name="plano" x-model="plano" @change="calculateVencimento" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                @php
                    $planos = ['Mensal', 'Trimestral', 'Semestral', 'Anual'];
                    if (isset($licenca) && $licenca->plano === 'Trial') $planos[] = 'Trial';
                @endphp
                <option value="">Selecione um plano</option>
                @foreach($planos as $p)
                    <option value="{{ $p }}" {{ old('plano', $licenca->plano ?? '') == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('plano')" class="mt-2" />
        </div>
    </div>

    {{-- Linha 2: Datas e Vencimento Calculado --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
        <div>
            <x-input-label for="data_inicio" :value="__('Data de Início')" />
            <x-text-input id="data_inicio" class="block mt-1 w-full" type="date" name="data_inicio" x-model="dataInicio" @change="calculateVencimento" :value="old('data_inicio', (isset($licenca) && $licenca->data_inicio) ? $licenca->data_inicio->format('Y-m-d') : '')" required />
            <x-input-error :messages="$errors->get('data_inicio')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="data_vencimento_display" :value="__('Data de Vencimento (Calculado)')" />
            <input id="data_vencimento_display" type="text" x-model="dataVencimento" class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm" readonly>
        </div>
    </div>

    {{-- Linha 3: Valor e Status --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="valor_pago" :value="__('Valor Pago (R$)')" />
            <x-text-input id="valor_pago" class="block mt-1 w-full" type="number" name="valor_pago" :value="old('valor_pago', $licenca->valor_pago ?? '0.00')" step="0.01" required />
            <x-input-error :messages="$errors->get('valor_pago')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="status" :value="__('Status')" />
            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                @php $statuses = ['ativo', 'expirado', 'pendente', 'cancelado']; @endphp
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ old('status', $licenca->status ?? '') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('admin.licencas.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition mr-4">Cancelar</a>
    <x-primary-button>
        {{ isset($licenca) && $licenca->id ? 'Atualizar Licença' : 'Criar Licença' }}
    </x-primary-button>
</div>

<script>
    function licenseForm() {
        return {
            plano: '{{ old('plano', $licenca->plano ?? '') }}',
            dataInicio: '{{ old('data_inicio', (isset($licenca) && $licenca->data_inicio) ? $licenca->data_inicio->format('Y-m-d') : '') }}',
            dataVencimento: '',
            init() {
                this.calculateVencimento();
            },
            calculateVencimento() {
                if (!this.dataInicio || !this.plano) {
                    this.dataVencimento = 'Selecione a data e o plano';
                    return;
                }
                
                let date = new Date(this.dataInicio + 'T00:00:00');
                
                switch (this.plano) {
                    case 'Trial':
                    case 'Mensal':
                        date.setDate(date.getDate() + 30);
                        break;
                    case 'Trimestral':
                        date.setMonth(date.getMonth() + 3);
                        break;
                    case 'Semestral':
                        date.setMonth(date.getMonth() + 6);
                        break;
                    case 'Anual':
                        date.setFullYear(date.getFullYear() + 1);
                        break;
                }
                
                this.dataVencimento = date.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: 'numeric' });
            }
        }
    }
</script>