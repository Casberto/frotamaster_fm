{{-- resources/views/dashboard/components/motoristas/modal-novos.blade.php --}}
<x-modal name="novos-motoristas-mes" maxWidth="2xl">
     {{-- MODIFICAÇÃO: Estrutura interna refeita para ter header, body e footer --}}
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
        {{-- Header --}}
        <div class="flex-shrink-0 flex justify-between items-center p-4 border-b bg-gray-50 rounded-t-xl">
            <h2 class="text-lg font-medium text-gray-900">
                Motoristas Cadastrados no Mês
            </h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

         {{-- Body --}}
         <div class="flex-grow p-6 overflow-y-auto bg-slate-50">
             @if(isset($novosMotoristasMesLista) && $novosMotoristasMesLista->isNotEmpty())
                <div class="max-h-96 overflow-y-auto border rounded-lg bg-white">
                    <table class="w-full text-sm text-left">
                         <thead class="sticky top-0 bg-gray-100 text-xs text-gray-700 uppercase">
                            <tr>
                                <th class="px-4 py-2">Nome</th>
                                <th class="px-4 py-2">Data Cadastro</th>
                                <th class="px-4 py-2 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @foreach ($novosMotoristasMesLista as $motorista)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 font-medium text-gray-900">{{ $motorista->mot_nome }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($motorista->created_at)->format('d/m/Y') }}</td>
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
                       <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                    <p>Nenhum motorista cadastrado este mês.</p>
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

