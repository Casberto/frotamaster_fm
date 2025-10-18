@csrf
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="p-8 bg-white rounded-lg shadow-md space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="cfp_modulo" class="block text-sm font-medium text-gray-700">Módulo</label>
            <input type="text" name="cfp_modulo" id="cfp_modulo" value="{{ old('cfp_modulo', $configuracao->cfp_modulo ?? '') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                   placeholder="ex: veiculos" required>
        </div>
        <div>
            <label for="cfp_chave" class="block text-sm font-medium text-gray-700">Chave de Identificação</label>
            <input type="text" name="cfp_chave" id="cfp_chave" value="{{ old('cfp_chave', $configuracao->cfp_chave ?? '') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                   placeholder="ex: limite_usuarios" required>
        </div>
    </div>
    <div>
        <label for="cfp_valor" class="block text-sm font-medium text-gray-700">Valor Padrão</label>
        <input type="text" name="cfp_valor" id="cfp_valor" value="{{ old('cfp_valor', $configuracao->cfp_valor ?? '') }}"
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
               required>
    </div>
    <div>
        <label for="cfp_tipo" class="block text-sm font-medium text-gray-700">Tipo de Dado</label>
        <select name="cfp_tipo" id="cfp_tipo"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="string" @selected(old('cfp_tipo', $configuracao->cfp_tipo ?? '') == 'string')>String</option>
            <option value="int" @selected(old('cfp_tipo', $configuracao->cfp_tipo ?? '') == 'int')>Integer</option>
            <option value="boolean" @selected(old('cfp_tipo', $configuracao->cfp_tipo ?? '') == 'boolean')>Boolean</option>
            <option value="text" @selected(old('cfp_tipo', $configuracao->cfp_tipo ?? '') == 'text')>Text</option>
        </select>
    </div>
    <div>
        <label for="cfp_descricao" class="block text-sm font-medium text-gray-700">Descrição (Rótulo do Campo)</label>
        <textarea name="cfp_descricao" id="cfp_descricao" rows="3"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                  required>{{ old('cfp_descricao', $configuracao->cfp_descricao ?? '') }}</textarea>
    </div>
</div>

<div class="mt-8 flex justify-end space-x-4">
    <a href="{{ route('admin.configuracoes-padrao.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
        Cancelar
    </a>
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
        Salvar
    </button>
</div>

