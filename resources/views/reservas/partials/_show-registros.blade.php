<div class="space-y-8 py-4">

    {{-- ======================================================================== --}}
    {{-- 1. ABASTECIMENTOS --}}
    {{-- ======================================================================== --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" 
         x-data="{ 
            mode: null, 
            litros: '',
            preco: '',
            total: '',
            calcularTotal() {
                if(this.litros && this.preco) {
                    this.total = (parseFloat(this.litros) * parseFloat(this.preco)).toFixed(2);
                }
            }
         }">
        
        {{-- Header da Seção --}}
        <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Abastecimentos</h4>
                    <p class="text-xs text-gray-500">Registro de combustíveis e recargas</p>
                </div>
            </div>
            
            @if($reserva->res_status == 'em_uso')
                <div class="flex items-center gap-2">
                     <button type="button" @click="mode = (mode === 'existing' ? null : 'existing')" 
                        class="text-xs font-medium text-gray-500 hover:text-blue-600 underline px-2 transition">
                        Vincular Existente
                    </button>
                    <x-primary-button type="button" @click="mode = (mode === 'new' ? null : 'new')" class="!py-2 !px-3 !text-xs">
                        <span x-text="mode === 'new' ? 'Cancelar' : '+ Registrar Novo'"></span>
                    </x-primary-button>
                </div>
            @endif
        </div>

        {{-- FORMULÁRIO: REGISTRAR NOVO --}}
        <div x-show="mode === 'new'" x-transition class="bg-blue-50/50 border-b border-blue-100 p-6">
            <form action="{{ route('reservas.abastecimentos.attach', $reserva) }}" method="POST"> {{-- Nota: Ajustei a rota assumindo attach/create --}}
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Coluna 1 --}}
                    <div class="space-y-4">
                        <div>
                            <x-input-label for="aba_data" value="Data *" />
                            <x-text-input id="aba_data" name="aba_data" type="datetime-local" class="w-full text-sm" value="{{ now()->format('Y-m-d\TH:i') }}" required />
                        </div>
                        <div>
                            <x-input-label for="aba_tipo_combustivel" value="Combustível *" />
                            <select id="aba_tipo_combustivel" name="aba_tipo_combustivel" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="Gasolina">Gasolina</option>
                                <option value="Etanol">Etanol</option>
                                <option value="Diesel">Diesel</option>
                                <option value="GNV">GNV</option>
                                <option value="Elétrico">Elétrico</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="aba_vlr_tot" value="Valor Total (R$) *" />
                            <x-text-input id="aba_vlr_tot" name="aba_vlr_tot" type="number" step="0.01" x-model="total" class="w-full text-sm font-bold text-blue-700" placeholder="0.00" required />
                        </div>
                    </div>

                    {{-- Coluna 2 --}}
                    <div class="space-y-4">
                        <div>
                            <x-input-label for="aba_km" value="Hodômetro (KM) *" />
                            <div class="relative">
                                <x-text-input id="aba_km" name="aba_km" type="number" class="w-full text-sm pl-20" required />
                                <span class="absolute left-3 top-2 text-xs text-gray-500 border-r pr-2 border-gray-300">Atual: {{ $reserva->veiculo->vei_km_atual ?? '?' }}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="aba_qtd" value="Litros *" />
                                <x-text-input id="aba_qtd" name="aba_qtd" type="number" step="0.01" x-model="litros" @input="calcularTotal()" class="w-full text-sm" placeholder="0.00" required />
                            </div>
                            <div>
                                <x-input-label for="aba_vlr_und" value="Preço/Litro *" />
                                <x-text-input id="aba_vlr_und" name="aba_vlr_unit" type="number" step="0.01" x-model="preco" @input="calcularTotal()" class="w-full text-sm" placeholder="0.00" required />
                            </div>
                        </div>
                        
                        {{-- Campos Ocultos Necessários --}}
                        <input type="hidden" name="forma_pagamento" value="Cartão Corporativo"> 
                        <input type="hidden" name="reembolso" value="0">
                        
                        <div class="pt-2">
                            <label for="aba_tanque_cheio" class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="aba_tanque_cheio" id="aba_tanque_cheio" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Tanque Cheio?</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-6 pt-4 border-t border-blue-200">
                    <x-secondary-button @click="mode = null" class="mr-3">Cancelar</x-secondary-button>
                    <x-primary-button type="submit">Salvar Abastecimento</x-primary-button>
                </div>
            </form>
        </div>

        {{-- FORMULÁRIO: VINCULAR EXISTENTE --}}
        <div x-show="mode === 'existing'" x-transition class="bg-gray-50 border-b border-gray-200 p-6">
            <form action="{{ route('reservas.abastecimentos.attach', $reserva) }}" method="POST">
                @csrf
                <div class="flex gap-4 items-end">
                    <div class="flex-grow">
                        <x-input-label for="abastecimento_id" value="Selecione o Registro" />
                        <select id="abastecimento_id" name="abastecimento_id" class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">-- Buscar Abastecimento --</option>
                            @foreach (\App\Models\Abastecimento::where('aba_vei_id', $reserva->res_vei_id)->orderBy('aba_data', 'desc')->limit(10)->get() as $abs)
                                <option value="{{ $abs->aba_id }}">
                                    {{ $abs->aba_data->format('d/m/Y') }} - R$ {{ number_format($abs->aba_vlr_tot, 2, ',', '.') }} ({{ $abs->aba_km }} km)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="forma_pagamento" value="Vinculado">
                    <input type="hidden" name="reembolso" value="0">
                    <x-primary-button type="submit">Vincular</x-primary-button>
                </div>
            </form>
        </div>

        {{-- LISTA DE REGISTROS --}}
        <div>
            @if($reserva->abastecimentos->isEmpty())
                <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    </div>
                    <p class="text-sm text-gray-500">Nenhum abastecimento vinculado a esta reserva.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 font-medium">Data</th>
                                <th class="px-6 py-3 font-medium">KM</th>
                                <th class="px-6 py-3 font-medium">Litros</th>
                                <th class="px-6 py-3 font-medium">Valor</th>
                                <th class="px-6 py-3 font-medium text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($reserva->abastecimentos as $abastecimento)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-700">{{ $abastecimento->aba_data->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ number_format($abastecimento->aba_km, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ number_format($abastecimento->aba_qtd, 2, ',', '.') }} L
                                        @if($abastecimento->aba_tanque_cheio)
                                            <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 uppercase">Cheio</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900">R$ {{ number_format($abastecimento->aba_vlr_tot, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if(in_array($reserva->res_status, ['em_uso', 'em_revisao']))
                                            <form action="{{ route('reservas.abastecimentos.detach', [$reserva, $abastecimento]) }}" method="POST" onsubmit="return confirm('Desvincular?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Desvincular">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>


    {{-- ======================================================================== --}}
    {{-- 2. PEDÁGIOS --}}
    {{-- ======================================================================== --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ adding: false }">
        <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Pedágios</h4>
                    <p class="text-xs text-gray-500">Custos de praças de pedágio</p>
                </div>
            </div>
            @if($reserva->res_status == 'em_uso')
                <x-secondary-button type="button" @click="adding = !adding" class="!py-2 !px-3 !text-xs">
                    <span x-text="adding ? 'Cancelar' : '+ Adicionar'"></span>
                </x-secondary-button>
            @endif
        </div>

        {{-- FORMULÁRIO --}}
        <div x-show="adding" x-collapse x-cloak class="bg-yellow-50/50 border-b border-yellow-100 p-6">
            <form action="{{ route('reservas.pedagios.attach', $reserva) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2 lg:col-span-1">
                         <x-input-label for="rpe_desc" value="Local" />
                         <x-text-input id="rpe_desc" name="rpe_desc" placeholder="Ex: Praça SP-01" class="w-full text-sm" required />
                    </div>
                    <div class="md:col-span-2 lg:col-span-1">
                        <x-input-label for="rpe_data_hora" value="Data/Hora" />
                        <x-text-input id="rpe_data_hora" name="rpe_data_hora" type="datetime-local" class="w-full text-sm" value="{{ now()->format('Y-m-d\TH:i') }}" required />
                    </div>
                    <div>
                        <x-input-label for="rpe_valor" value="Valor (R$)" />
                        <x-text-input id="rpe_valor" name="rpe_valor" type="number" step="0.01" class="w-full text-sm font-bold text-yellow-700" placeholder="0,00" required />
                    </div>
                    <div>
                        <x-input-label for="rpe_forma_pagto" value="Pagamento" />
                        <select id="rpe_forma_pagto" name="rpe_forma_pagto" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="TAG">TAG</option>
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Cartão">Cartão</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <input id="reembolso_sim_ped" name="rpe_reembolso" type="checkbox" value="1" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:ring-yellow-500">
                        <label for="reembolso_sim_ped" class="ml-2 text-sm text-gray-700">Solicitar Reembolso</label>
                    </div>
                    <x-primary-button type="submit" class="!bg-yellow-600 hover:!bg-yellow-700 focus:!ring-yellow-500">Salvar</x-primary-button>
                </div>
            </form>
        </div>

        {{-- LISTA --}}
        <div>
            @if($reserva->pedagios->isEmpty())
                 <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="text-sm text-gray-500">Nenhum pedágio registrado.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 font-medium">Data</th>
                                <th class="px-6 py-3 font-medium">Local</th>
                                <th class="px-6 py-3 font-medium">Pagamento</th>
                                <th class="px-6 py-3 font-medium">Valor</th>
                                <th class="px-6 py-3 font-medium text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($reserva->pedagios as $pedagio)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-700">{{ $pedagio->rpe_data_hora->format('d/m H:i') }}</td>
                                    <td class="px-6 py-4 text-gray-900 font-medium">{{ $pedagio->rpe_desc }}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $pedagio->rpe_forma_pagto }}
                                        @if($pedagio->rpe_reembolso)
                                            <span class="text-[10px] font-bold bg-green-100 text-green-700 px-1.5 py-0.5 rounded ml-1">REEMB</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900">R$ {{ number_format($pedagio->rpe_valor, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if(in_array($reserva->res_status, ['em_uso', 'em_revisao']))
                                            <form action="{{ route('reservas.pedagios.detach', [$reserva, $pedagio]) }}" method="POST" onsubmit="return confirm('Remover?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ======================================================================== --}}
    {{-- 3. PASSAGEIROS --}}
    {{-- ======================================================================== --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ adding: false }">
        <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Passageiros</h4>
                    <p class="text-xs text-gray-500">Registro de ocupantes</p>
                </div>
            </div>
            @if($reserva->res_status == 'em_uso')
                <x-secondary-button type="button" @click="adding = !adding" class="!py-2 !px-3 !text-xs">
                    <span x-text="adding ? 'Cancelar' : '+ Adicionar'"></span>
                </x-secondary-button>
            @endif
        </div>

        {{-- FORMULÁRIO --}}
        <div x-show="adding" x-collapse x-cloak class="bg-green-50/50 border-b border-green-100 p-6">
            <form action="{{ route('reservas.passageiros.attach', $reserva) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <x-input-label for="rpa_nome" value="Nome Completo" />
                        <x-text-input id="rpa_nome" name="rpa_nome" class="w-full text-sm" required />
                    </div>
                    <div>
                        <x-input-label for="rpa_doc" value="Documento (Opcional)" />
                        <x-text-input id="rpa_doc" name="rpa_doc" class="w-full text-sm" />
                    </div>
                    <div>
                        <x-input-label for="rpa_entrou_em" value="Local Embarque" />
                        <div class="flex gap-2">
                            <x-text-input id="rpa_entrou_em" name="rpa_entrou_em" class="w-full text-sm" placeholder="Ex: Sede" required />
                            <x-primary-button type="submit" class="!bg-green-600 hover:!bg-green-700 focus:!ring-green-500">Add</x-primary-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- LISTA --}}
        <div>
            @if($reserva->passageiros->isEmpty())
                 <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <p class="text-sm text-gray-500">Nenhum passageiro registrado.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 font-medium">Nome</th>
                                <th class="px-6 py-3 font-medium">Documento</th>
                                <th class="px-6 py-3 font-medium">Embarque</th>
                                <th class="px-6 py-3 font-medium text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($reserva->passageiros as $passageiro)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $passageiro->rpa_nome }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $passageiro->rpa_doc ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $passageiro->rpa_entrou_em }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if(in_array($reserva->res_status, ['em_uso', 'em_revisao']))
                                            <form action="{{ route('reservas.passageiros.detach', [$reserva, $passageiro]) }}" method="POST" onsubmit="return confirm('Remover?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ======================================================================== --}}
    {{-- 4. MANUTENÇÕES (Apenas se for Reserva de Manutenção) --}}
    {{-- ======================================================================== --}}
    @if ($reserva->res_tipo == 'manutencao')
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ adding: false }">
        <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" /></svg>
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-900">Serviços</h4>
                    <p class="text-xs text-gray-500">Ordens de serviço vinculadas</p>
                </div>
            </div>
            
            @if($reserva->res_status == 'em_uso')
                <div class="flex gap-2">
                     <a href="{{ route('manutencoes.create', ['veiculo_id' => $reserva->res_vei_id, 'fornecedor_id' => $reserva->res_for_id]) }}" target="_blank" 
                       class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150">
                        Nova OS
                    </a>
                    <x-primary-button type="button" @click="adding = !adding" class="!py-2 !px-3 !text-xs !bg-purple-600 hover:!bg-purple-700 !border-transparent focus:!ring-purple-500">
                        <span x-text="adding ? 'Cancelar' : 'Vincular OS'"></span>
                    </x-primary-button>
                </div>
            @endif
        </div>

        {{-- FORMULÁRIO --}}
        <div x-show="adding" x-collapse x-cloak class="bg-purple-50/50 border-b border-purple-100 p-6">
            <form action="{{ route('reservas.manutencoes.attach', $reserva) }}" method="POST">
                @csrf
                <div class="flex gap-4 items-end">
                    <div class="flex-grow">
                         <x-input-label for="manutencao_id" value="Selecione a Ordem de Serviço" />
                        @php
                            $manutencoesDisponiveis = \App\Models\Manutencao::where('man_vei_id', $reserva->res_vei_id)
                                ->where('man_emp_id', $reserva->res_emp_id)
                                ->whereDoesntHave('reservas', function ($query) use ($reserva) { $query->where('reservas.res_id', $reserva->res_id); })
                                ->orderBy('man_data_inicio', 'desc')->limit(20)->get();
                        @endphp
                        <select id="manutencao_id" name="manutencao_id" class="w-full rounded-md border-purple-300 focus:border-purple-500 focus:ring-purple-500 text-sm mt-1" required>
                            <option value="">-- Escolha uma OS --</option>
                            @foreach ($manutencoesDisponiveis as $man)
                                <option value="{{ $man->man_id }}">
                                    {{ $man->man_data_inicio->format('d/m/y') }} • {{ ucfirst($man->man_tipo) }} • R$ {{ number_format($man->man_custo_total, 2, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <x-primary-button type="submit" class="!bg-purple-600 hover:!bg-purple-700 focus:!ring-purple-500">Vincular</x-primary-button>
                </div>
            </form>
        </div>

        {{-- LISTA --}}
        <div>
            @if($reserva->manutencoes->isEmpty())
                <div class="p-8 text-center">
                     <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </div>
                    <p class="text-sm text-gray-500">Nenhuma manutenção vinculada.</p>
                </div>
            @else
                 <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 font-medium">Data</th>
                                <th class="px-6 py-3 font-medium">Tipo</th>
                                <th class="px-6 py-3 font-medium">Custo</th>
                                <th class="px-6 py-3 font-medium">Status</th>
                                <th class="px-6 py-3 font-medium text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($reserva->manutencoes as $manutencao)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-700">{{ $manutencao->man_data_inicio->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ ucfirst($manutencao->man_tipo) }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">R$ {{ number_format($manutencao->man_custo_total, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                         <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $manutencao->man_status == 'agendada' ? 'bg-blue-100 text-blue-800' :
                                                       ($manutencao->man_status == 'em_andamento' ? 'bg-yellow-100 text-yellow-800' :
                                                       ($manutencao->man_status == 'concluida' ? 'bg-green-100 text-green-800' :
                                                       ($manutencao->man_status == 'cancelada' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                            {{ ucfirst(str_replace('_', ' ', $manutencao->man_status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if(in_array($reserva->res_status, ['em_uso', 'em_revisao']))
                                            <form action="{{ route('reservas.manutencoes.detach', [$reserva, $manutencao]) }}" method="POST" onsubmit="return confirm('Desvincular?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    @endif

</div>