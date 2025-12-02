@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6" role="alert">
        <p class="font-bold">Atenção</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-8">
    {{-- TAB 1: DADOS GERAIS --}}
    <div id="tab-geral-content" x-show="tab === 'geral' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Dados do Serviço
            </h3>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="ser_nome" class="block font-medium text-sm text-gray-700">Código do Serviço*</label>
                    <input type="text" name="ser_nome" id="ser_nome" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('ser_nome', $servico->ser_nome ?? '') }}" required autofocus maxlength="20">
                </div>
                <div>
                    <label for="ser_descricao" class="block font-medium text-sm text-gray-700">Descrição</label>
                    <textarea name="ser_descricao" id="ser_descricao" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('ser_descricao', $servico->ser_descricao ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('servicos.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition shadow-sm font-medium">Cancelar</a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm font-medium">Salvar</button>
</div>
