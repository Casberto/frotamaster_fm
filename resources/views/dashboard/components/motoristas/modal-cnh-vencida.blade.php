{{-- resources/views/dashboard/components/motoristas/modal-cnh-vencida.blade.php --}}
<x-modal name="motoristas-cnh-vencida" maxWidth="2xl">
    {{-- MODIFICAÇÃO: Estrutura interna refeita para ter header, body e footer --}}
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
        {{-- Header --}}
        <div class="flex-shrink-0 flex justify-between items-center p-4 border-b bg-red-50 rounded-t-xl">
            <h2 class="text-lg font-medium text-red-800"> {{-- Cor ajustada para o header --}}
                Motoristas com CNH Vencida
            </h2>
            <button x-on:click="$dispatch('close')" class="text-red-400 hover:text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Body --}}
         <div class="flex-grow p-6 overflow-y-auto bg-slate-50">
             @if(isset($motoristasCnhVencidaLista) && $motoristasCnhVencidaLista->isNotEmpty())
                 <div class="max-h-96 overflow-y-auto border rounded-lg bg-white">
                    <table class="w-full text-sm text-left">
                         <thead class="sticky top-0 bg-gray-100 text-xs text-gray-700 uppercase">
                            <tr>
                                <th class="px-4 py-2">Nome</th>
                                <th class="px-4 py-2">Data de Validade</th>
                                <th class="px-4 py-2 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @foreach ($motoristasCnhVencidaLista as $motorista)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 font-medium text-gray-900">{{ $motorista->mot_nome }}</td>
                                    <td class="px-4 py-2 text-red-600 font-semibold">{{ \Carbon\Carbon::parse($motorista->mot_cnh_data_validade)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <a href="{{ route('motoristas.edit', $motorista->mot_id) }}" class="font-medium text-blue-600 hover:underline">Editar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center text-center text-gray-500 p-6 border rounded-lg bg-white">
                     <svg class="w-10 h-10 mb-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <p>Nenhum motorista encontrado com CNH vencida.</p>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="flex-shrink-0 flex justify-end p-4 border-t bg-gray-50 rounded-b-xl">
            <x-secondary-button x-on:click="$dispatch('close')">
                Fechar
            </x-secondary-button>
        </div>
    </div>
</x-modal>

