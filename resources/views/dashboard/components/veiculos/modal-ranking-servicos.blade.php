{{-- resources/views/dashboard/components/veiculos/modal-ranking-servicos.blade.php --}}
{{-- ESTILO ATUALIZADO --}}
<x-modal name="ranking-servicos" :show="$errors->any()" maxWidth="2xl">
    <div class="p-0">
        {{-- Cabeçalho --}}
        <div class="flex items-center justify-between p-6 bg-gray-100 rounded-t-lg">
            <h2 class="text-lg font-medium text-gray-900">
                Top 10 Serviços (Últimos 30 dias)
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
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse ($rankingServicos ?? [] as $index => $servico)
                    <div class="p-3 bg-white rounded-lg shadow-sm border">
                        <div class="flex items-center space-x-4">
                            <span
                                class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-bold text-sm">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $servico->ser_nome }}</p>
                            </div>
                            <p class="text-lg font-bold text-gray-900">
                                {{ $servico->total }} <span class="text-sm font-normal text-gray-500">x</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 bg-white rounded-lg shadow border">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17 4.872 21.01a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sem dados</h3>
                        <p class="mt-1 text-sm text-gray-500">Nenhum serviço registrado nos últimos 30 dias.</p>
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

