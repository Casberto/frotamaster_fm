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

<div class="form-section">
    <div class="space-y-6">
        <div>
            <label for="ser_nome" class="block font-medium text-sm text-gray-700">Código do Serviço*</label>
            <input type="text" name="ser_nome" id="ser_nome" class="mt-1 block w-full" value="{{ old('ser_nome', $servico->ser_nome ?? '') }}" required autofocus maxlength="20">
        </div>
        <div>
            <label for="ser_descricao" class="block font-medium text-sm text-gray-700">Descrição*</label>
            <textarea name="ser_descricao" id="ser_descricao" rows="4" class="mt-1 block w-full" required>{{ old('ser_descricao', $servico->ser_descricao ?? '') }}</textarea>
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('servicos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Salvar</button>
</div>
