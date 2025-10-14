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
    {{-- Dados Cadastrais --}}
    <div class="form-section">
        <h3 class="form-section-title">Dados Cadastrais</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="per_nome" class="block font-medium text-sm text-gray-700">Nome do Perfil*</label>
                <input type="text" name="per_nome" id="per_nome" class="mt-1 block w-full" value="{{ old('per_nome', $perfi->per_nome ?? '') }}" required>
            </div>
            <div>
                <label for="per_status" class="block font-medium text-sm text-gray-700">Status*</label>
                <select name="per_status" id="per_status" class="mt-1 block w-full" required>
                    <option value="1" @selected(old('per_status', $perfi->per_status ?? 1) == 1)>Ativo</option>
                    <option value="0" @selected(old('per_status', $perfi->per_status ?? 1) == 0)>Inativo</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label for="per_descricao" class="block font-medium text-sm text-gray-700">Descrição</label>
                <textarea name="per_descricao" id="per_descricao" rows="3" class="mt-1 block w-full">{{ old('per_descricao', $perfi->per_descricao ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Usuários Vinculados --}}
    <div class="form-section">
        <h3 class="form-section-title">Usuários Vinculados</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($usuarios as $usuario)
                <div class="flex items-center">
                    <input type="checkbox" name="usuarios[]" id="user_{{ $usuario->id }}" value="{{ $usuario->id }}" 
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                           @if(isset($perfi) && $perfi->usuarios->contains($usuario->id)) checked @endif>
                    <label for="user_{{ $usuario->id }}" class="ml-2 block text-sm text-gray-900">{{ $usuario->name }}</label>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Permissões do Perfil --}}
    <div class="form-section">
        <h3 class="form-section-title">Permissões do Perfil</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($permissoes->groupBy('prm_modulo') as $modulo => $group)
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">{{ $modulo }}</h4>
                    @foreach($group as $permissao)
                        <div class="flex items-center mb-1">
                            <input type="checkbox" name="permissoes[]" id="perm_{{ $permissao->prm_id }}" value="{{ $permissao->prm_id }}"
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                   @if(isset($perfi) && $perfi->permissoes->contains($permissao->prm_id)) checked @endif>
                            <label for="perm_{{ $permissao->prm_id }}" class="ml-2 block text-sm text-gray-900">{{ $permissao->prm_acao }}</label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('perfis.index') }}" class="btn-secondary mr-4">Cancelar</a>
    <button type="submit" class="btn-primary">Salvar Perfil</button>
</div>
