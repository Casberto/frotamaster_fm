{{-- resources/views/dashboard/components/veiculos/modal-manutencoes-vencidas.blade.php --}}
{{-- ESTILO ATUALIZADO --}}
<x-modal name="manutencoes-vencidas" :show="$errors->any()" maxWidth="2xl">
    <div class="p-0">
        {{-- Cabeçalho --}}
        <div class="flex items-center justify-between p-6 bg-red-100 rounded-t-lg">
            <h2 class="text-lg font-medium text-red-900">
                Manutenções Vencidas
            </h2>
            <button @click="$dispatch('close')">
                <svg class="w-6 h-6 text-red-700 hover:text-red-900" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Conteúdo --}}
        <div class="p-6 bg-slate-50">
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse ($manutencoesVencidasLista ?? [] as $manutencao)
                    <div class="p-4 bg-white rounded-lg shadow border border-red-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-gray-800">
                                    {{ $manutencao->veiculo->vei_placa ?? 'N/A' }} -
                                    {{ $manutencao->veiculo->vei_modelo ?? 'N/A' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Status: <span
                                        class="font-medium text-red-600">{{ $manutencao->man_status }}</span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    Vencida em: <span
                                        class="font-medium">{{ $manutencao->man_data_inicio->format('d/m/Y') }}</span>
                                </p>
                            </div>
                            <a href="{{ route('manutencoes.edit', $manutencao->man_id) }}"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Ver/Editar
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 bg-white rounded-lg shadow border">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tudo em dia!</h3>
                        <p class="mt-1 text-sm text-gray-500">Nenhuma manutenção vencida encontrada.</p>
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

