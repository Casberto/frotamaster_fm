<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Apólice: {{ $apolice->seg_numero }}
            </h2>
            
            {{-- Botão de Excluir --}}
            {{-- Botão de Excluir --}}
            @if(Auth::user()->temPermissao('SEG004'))
            <form action="{{ route('seguros.destroy', $apolice->seg_id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta apólice?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">
                    Excluir Apólice
                </button>
            </form>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="{ tab: 'geral', mobile: window.innerWidth < 768 }" @resize.window="mobile = window.innerWidth < 768">
            <div class="p-6">
                <form action="{{ route('seguros.update', $apolice->seg_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    @include('seguros._form')

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('seguros.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition shadow-sm font-medium">Cancelar</a>
                        <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm font-medium">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
