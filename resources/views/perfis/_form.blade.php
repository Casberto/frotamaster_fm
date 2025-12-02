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
    {{-- TAB 1: DADOS CADASTRAIS --}}
    <div id="tab-geral-content" x-show="tab === 'geral' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force">
        
        {{-- Dados Cadastrais --}}
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Dados Cadastrais
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="per_nome" class="block font-medium text-sm text-gray-700">Nome do Perfil*</label>
                    <input type="text" name="per_nome" id="per_nome" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('per_nome', $perfi->per_nome ?? '') }}" required autofocus>
                </div>
                <div>
                    <label for="per_status" class="block font-medium text-sm text-gray-700">Status*</label>
                    <select name="per_status" id="per_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="1" @selected(old('per_status', $perfi->per_status ?? 1) == 1)>Ativo</option>
                        <option value="0" @selected(old('per_status', $perfi->per_status ?? 1) == 0)>Inativo</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="per_descricao" class="block font-medium text-sm text-gray-700">Descrição</label>
                    <textarea name="per_descricao" id="per_descricao" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('per_descricao', $perfi->per_descricao ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Usuários Vinculados --}}
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Usuários Vinculados
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($usuarios as $usuario)
                    <div class="flex items-center p-2 hover:bg-gray-50 rounded-md transition">
                        <input type="checkbox" name="usuarios[]" id="user_{{ $usuario->id }}" value="{{ $usuario->id }}" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer"
                               @if(isset($perfi) && $perfi->usuarios->contains($usuario->id)) checked @endif>
                        <label for="user_{{ $usuario->id }}" class="ml-2 block text-sm text-gray-700 cursor-pointer w-full">{{ $usuario->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SEÇÃO INDEPENDENTE: PERMISSÕES DO PERFIL --}}
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b pb-3 mb-4 sm:mb-6 gap-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.542 17.314A4 4 0 0110 18.656L10 14l-4-4a6 6 0 1113-6z"></path></svg>
                Permissões do Perfil
            </h3>
            
            {{-- Botões Globais --}}
            <div class="flex gap-2">
                <button type="button" @click="toggleAll(true)" class="text-xs px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition font-medium border border-blue-200">
                    Marcar Todas
                </button>
                <button type="button" @click="toggleAll(false)" class="text-xs px-3 py-1.5 bg-gray-50 text-gray-700 rounded-md hover:bg-gray-100 transition font-medium border border-gray-200">
                    Desmarcar Todas
                </button>
            </div>
        </div>

        {{-- Module Tabs Navigation --}}
        <div class="mb-6">
            <nav class="flex flex-wrap gap-2" aria-label="Module Tabs">
                @foreach($permissoes->groupBy('prm_modulo') as $modulo => $group)
                    <button type="button" @click="moduleTab = '{{ $modulo }}'" 
                        :class="moduleTab === '{{ $modulo }}' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                        class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        {{ $modulo }}
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- Module Content --}}
        @foreach($permissoes->groupBy('prm_modulo') as $modulo => $group)
            <div id="module-{{ $modulo }}" x-show="moduleTab === '{{ $modulo }}'" class="animate-fade-in-up">
                <div class="flex justify-end mb-4 gap-2 border-b border-gray-100 pb-2">
                    <button type="button" @click="toggleModule('{{ $modulo }}', true)" class="text-xs px-2 py-1 text-blue-600 hover:text-blue-800 hover:underline transition">
                        Marcar Módulo
                    </button>
                    <span class="text-gray-300">|</span>
                    <button type="button" @click="toggleModule('{{ $modulo }}', false)" class="text-xs px-2 py-1 text-gray-500 hover:text-gray-700 hover:underline transition">
                        Desmarcar Módulo
                    </button>
                </div>
                
                <div class="flex flex-wrap gap-4">
                    @foreach($group as $permissao)
                        <div class="flex items-center p-2 hover:bg-gray-50 rounded-md transition border border-transparent hover:border-gray-100 min-w-[150px]">
                            <input type="checkbox" name="permissoes[]" id="perm_{{ $permissao->prm_id }}" value="{{ $permissao->prm_id }}"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer"
                                   @if(isset($perfi) && $perfi->permissoes->contains($permissao->prm_id)) checked @endif>
                            <label for="perm_{{ $permissao->prm_id }}" class="ml-2 block text-sm text-gray-700 cursor-pointer">{{ $permissao->prm_acao }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('perfis.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition shadow-sm font-medium">Cancelar</a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm font-medium">Salvar Perfil</button>
</div>
