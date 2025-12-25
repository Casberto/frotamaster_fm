<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de Compras (Peças Aprovadas)') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ copied: false }">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 bg-yellow-50 border-b border-yellow-100">
                    <p class="text-sm text-yellow-800">
                        Esta lista contém todas as peças de OS com status <strong>"Aprovado"</strong> ou <strong>"Aguardando Peças"</strong>.
                    </p>
                </div>

                <div class="p-6" id="lista-pecas">
                    <h3 class="font-bold text-gray-900 mb-4 underline">PEDIDO DE PEÇAS - {{ date('d/m/Y') }}</h3>
                    
                    <ul class="space-y-2 list-disc list-inside text-gray-800 font-mono text-lg">
                        @forelse($pecas as $peca)
                            <li>
                                <span class="font-bold">{{ $peca->qtd_total }}x</span> {{ $peca->osi_descricao }}
                            </li>
                        @empty
                            <li class="list-none text-gray-400 italic">Nenhuma peça pendente de compra no momento.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <a href="{{ route('oficina.painel.index') }}" class="text-gray-600 text-sm hover:underline">&larr; Voltar ao Painel</a>
                    
                    <button 
                        @click="
                            const lista = document.getElementById('lista-pecas').innerText;
                            navigator.clipboard.writeText(lista);
                            copied = true;
                            setTimeout(() => copied = false, 2000);
                        "
                        class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 font-bold flex items-center transition"
                        :class="{ 'bg-gray-600': copied, 'bg-green-600': !copied }"
                    >
                        <svg x-show="!copied" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        <svg x-show="copied" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span x-text="copied ? 'Copiado!' : 'Copiar para WhatsApp'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
