{{-- resources/views/dashboard/components/veiculos/modal-custos-mensais.blade.php --}}
{{-- ESTILO ATUALIZADO --}}
<x-modal name="custos-mensais" :show="$errors->any()" maxWidth="2xl">
    <div class="p-0">
        {{-- Cabeçalho --}}
        <div class="flex items-center justify-between p-6 bg-gray-100 rounded-t-lg">
            <h2 class="text-lg font-medium text-gray-900">
                Custos do Mês (Manutenções e Abastecimentos)
            </h2>
            <button @click="$dispatch('close')">
                <svg class="w-6 h-6 text-gray-500 hover:text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Conteúdo --}}
        <div class="p-6 bg-slate-50">
            <div class="flex justify-between items-center mb-4 p-4 bg-white rounded-lg shadow">
                <div>
                    <span class="text-sm text-gray-500">Total Gasto no Mês</span>
                    <p class="text-2xl font-bold text-blue-600">R$
                        {{ number_format($totalGastoMes ?? 0, 2, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600"><span
                            class="font-bold">{{ $countManutencoesMes ?? 0 }}</span> Manutenções</p>
                    <p class="text-sm text-gray-600"><span
                            class="font-bold">{{ $countAbastecimentosMes ?? 0 }}</span> Abastecimentos</p>
                </div>
            </div>

            <div class="max-h-96 overflow-y-auto space-y-3">
                @forelse ($custosMensaisLista ?? [] as $custo)
                    <div class="p-3 bg-white rounded-lg shadow-sm border">
                        <div class="grid grid-cols-5 gap-4 items-center">
                            <div>
                                <p class="font-bold text-gray-800">
                                    {{ $custo->veiculo->vei_placa ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-500 truncate"
                                    title="{{ $custo->veiculo->vei_modelo ?? 'Veículo não encontrado' }}">
                                    {{ $custo->veiculo->vei_modelo ?? 'Veículo não encontrado' }}
                                </p>
                            </div>
                            <div>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $custo->tipo == 'Manutenção' ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $custo->tipo }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-700">
                                {{ $custo->data ? \Carbon\Carbon::parse($custo->data)->format('d/m/Y') : 'N/A' }}
                            </p>
                            <p class="text-sm font-bold text-gray-900 text-right">
                                R$ {{ number_format($custo->valor, 2, ',', '.') }}
                            </p>
                            <div class="text-right">
                                @if ($custo->tipo == 'Manutenção')
                                    <a href="{{ route('manutencoes.edit', $custo->id_operacao) }}"
                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                        Ver
                                    </a>
                                @else
                                    <a href="{{ route('abastecimentos.edit', $custo->id_operacao) }}"
                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                        Ver
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 bg-white rounded-lg shadow border">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.75A.75.75 0 0 1 3 4.5h.75m0 0h.75A.75.75 0 0 1 4.5 6v.75m0 0v.75A.75.75 0 0 1 3.75 8.25h-.75m0 0H3A.75.75 0 0 1 2.25 7.5v-.75m0 0v-.75A.75.75 0 0 1 3 5.25h.75M15 11.25l1.5-1.5.75.75-1.5 1.5.75.75 1.5-1.5.75.75-1.5 1.5.75.75 1.5-1.5.75.75-1.5 1.5V21m-10.5-6.75a.75.75 0 0 0-.75.75v.75c0 .414.336.75.75.75h.75m0 0v.75c0 .414.336.75.75.75h.75a.75.75 0 0 0 .75-.75v-.75m0 0h.75a.75.75 0 0 0 .75-.75v-.75a.75.75 0 0 0-.75-.75h-.75m0 0h-.75a.75.75 0 0 0-.75.75v.75c0 .414.336.75.75.75h.75m0 0v.75c0 .414.336.75.75.75h.75a.75.75 0 0 0 .75-.75v-.75m0 0h.75a.75.75 0 0 0 .75-.75v-.75a.75.75 0 0 0-.75-.75h-.75m0 0h-.75a.75.75 0 0 0-.75.75v.75c0 .414.336.75.75.75h.75M4.5 19.5h.008v.008H4.5v-.008Z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sem custos</h3>
                        <p class="mt-1 text-sm text-gray-500">Nenhum custo registrado este mês.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Rodapé --}}
        <div class="flex-shrink-0 flex justify-end p-4 border-t bg-gray-50 rounded-b-xl">
            <x-secondary-button x-on:click="$dispatch('close')">
                Fechar
            </x-secondary-button>
        </div>
    </div>
</x-modal>

