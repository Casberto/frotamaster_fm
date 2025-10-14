<div class="space-y-4">
    <div>
        <label for="prm_modulo" class="block font-medium text-sm text-gray-700">Módulo*</label>
        <input type="text" name="prm_modulo" id="prm_modulo" class="mt-1 block w-full" value="{{ old('prm_modulo', $permissao->prm_modulo ?? '') }}" required>
    </div>
    <div>
        <label for="prm_acao" class="block font-medium text-sm text-gray-700">Ação*</label>
        <input type="text" name="prm_acao" id="prm_acao" class="mt-1 block w-full" value="{{ old('prm_acao', $permissao->prm_acao ?? '') }}" required>
    </div>
    <div>
        <label for="prm_descricao" class="block font-medium text-sm text-gray-700">Descrição</label>
        <textarea name="prm_descricao" id="prm_descricao" rows="3" class="mt-1 block w-full">{{ old('prm_descricao', $permissao->prm_descricao ?? '') }}</textarea>
    </div>
</div>
<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.permissoes.index') }}" class="btn-secondary mr-4">Cancelar</a>
    <button type="submit" class="btn-primary">Salvar</button>
</div>
