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

<div class="form-section" x-data="{ tipo: '{{ old('tipo', $empresa->tipo ?? 'PJ') }}' }">
    <h3 class="form-section-title">Dados da Empresa</h3>

    <div class="mb-4">
        <label class="block font-medium text-sm text-gray-700">Tipo de Pessoa*</label>
        <div class="mt-2 space-x-4">
            <label class="inline-flex items-center">
                <input type="radio" x-model="tipo" name="tipo" value="PJ" class="form-radio text-blue-600 focus:ring-blue-500">
                <span class="ml-2">Pessoa Jurídica (PJ)</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" x-model="tipo" name="tipo" value="PF" class="form-radio text-blue-600 focus:ring-blue-500">
                <span class="ml-2">Pessoa Física (PF)</span>
            </label>
        </div>
    </div>

    <!-- Seleção de Perfil / Tipo de Empresa -->
    <div class="mb-6" x-data="{ 
        selected: '{{ old('profile', isset($empresa) && $empresa->profile ? $empresa->profile->value : 'frotista') }}',
        modules: {{ json_encode(old('modules', isset($empresa) ? ($empresa->modules ?? []) : [])) }},
        defaults: {{ json_encode($profileDefaults ?? []) }},
        
        init() {
            this.$watch('selected', (value) => {
                if (this.defaults && this.defaults[value]) {
                    this.modules = this.defaults[value];
                }
            });
            
            // Se for criação (sem old input e sem empresa), define o padrão inicial do 'frotista' (ou o que estiver selecionado)
            @if(!old('profile') && !isset($empresa))
                if (this.defaults && this.defaults[this.selected]) {
                    this.modules = this.defaults[this.selected];
                }
            @endif
        }
    }">
        <label class="block font-medium text-sm text-gray-700 mb-2">Perfil de Uso (Preset)*</label>
        <p class="text-xs text-gray-500 mb-3">Selecionar um perfil atualizará automaticamente os módulos abaixo.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach(\App\Enums\CompanyProfile::cases() as $profile)
                <label class="relative flex p-4 border rounded-lg cursor-pointer transition-all hover:border-blue-500 hover:bg-blue-50"
                       :class="selected === '{{ $profile->value }}' ? 'border-blue-600 bg-blue-50 ring-1 ring-blue-600' : 'border-gray-200'">
                    
                    {{-- Input Radio Oculto --}}
                    <input type="radio" name="profile" value="{{ $profile->value }}" 
                           class="sr-only" x-model="selected">
    
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="block text-sm font-medium text-gray-900">
                                {{ $profile->label() }}
                            </span>
                            
                            {{-- Ícone de Informação (Tooltip Trigger) --}}
                            <div x-data="{ tooltip: false }" class="relative ml-2" @mouseenter="tooltip = true" @mouseleave="tooltip = false">
                                <svg class="w-5 h-5 text-gray-400 hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
    
                                {{-- Tooltip Content --}}
                                <div x-show="tooltip" 
                                     x-transition.opacity
                                     class="absolute z-10 w-64 p-3 mt-2 -ml-32 text-xs font-medium text-white bg-slate-800 rounded-lg shadow-xl bottom-full left-1/2 mb-2 pointer-events-none"
                                     style="display: none;">
                                    {{ $profile->description() }}
                                    {{-- Seta do Tooltip --}}
                                    <div class="absolute w-2 h-2 bg-slate-800 rotate-45 -bottom-1 left-1/2 -translate-x-1/2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>

        <!-- Seleção de Módulos (Dinâmico) -->
        <div class="mt-8 border-t pt-6">
            <label class="block font-medium text-sm text-gray-700 mb-2">Permissões de Módulos</label>
            <p class="text-xs text-gray-500 mb-4">Marque ou desmarque os módulos que esta empresa terá acesso.</p>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($availableModules ?? [] as $key => $label)
                    <label class="inline-flex items-center space-x-2 p-2 border rounded hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="modules[]" value="{{ $key }}" 
                               class="form-checkbox text-blue-600 rounded focus:ring-blue-500" 
                               x-model="modules">
                        <span class="text-sm text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="nome_fantasia" class="block font-medium text-sm text-gray-700" x-text="tipo === 'PF' ? 'Apelido*' : 'Nome Fantasia*'"></label>
            <input type="text" name="nome_fantasia" id="nome_fantasia" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('nome_fantasia', $empresa->nome_fantasia ?? '') }}" required>
        </div>
        <div>
            <label for="razao_social" class="block font-medium text-sm text-gray-700" x-text="tipo === 'PF' ? 'Nome Completo*' : 'Razão Social*'"></label>
            <input type="text" name="razao_social" id="razao_social" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('razao_social', $empresa->razao_social ?? '') }}" required>
        </div>
        <div>
            <label for="cnpj" class="block font-medium text-sm text-gray-700" x-text="tipo === 'PF' ? 'CPF*' : 'CNPJ*'"></label>
            <input type="text" name="cnpj" id="cnpj" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('cnpj', $empresa->cnpj ?? '') }}" required>
        </div>
        <div>
            <label for="email_contato" class="block font-medium text-sm text-gray-700">Email de Contato*</label>
            <input type="email" name="email_contato" id="email_contato" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('email_contato', $empresa->email_contato ?? '') }}" required>
        </div>
        <div>
            <label for="telefone_contato" class="block font-medium text-sm text-gray-700">Telefone de Contato*</label>
            <input type="text" name="telefone_contato" id="telefone_contato" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('telefone_contato', $empresa->telefone_contato ?? '') }}" required>
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('admin.empresas.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
        Cancelar
    </a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
        Salvar
    </button>
</div>
