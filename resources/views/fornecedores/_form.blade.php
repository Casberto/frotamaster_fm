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
        {{-- Seção de Dados Principais --}}
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Dados Principais
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="for_nome_fantasia" class="block font-medium text-sm text-gray-700">Nome Fantasia*</label>
                    <input type="text" name="for_nome_fantasia" id="for_nome_fantasia" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('for_nome_fantasia', $fornecedor->for_nome_fantasia) }}" required autofocus>
                </div>
                <div>
                    <label for="for_razao_social" class="block font-medium text-sm text-gray-700">Razão Social</label>
                    <input type="text" name="for_razao_social" id="for_razao_social" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('for_razao_social', $fornecedor->for_razao_social) }}">
                </div>
                <div>
                    <label for="for_cnpj_cpf" class="block font-medium text-sm text-gray-700">CNPJ / CPF</label>
                    <input type="text" name="for_cnpj_cpf" id="for_cnpj_cpf" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('for_cnpj_cpf', $fornecedor->for_cnpj_cpf) }}">
                </div>
                <div>
                    <label for="for_tipo" class="block font-medium text-sm text-gray-700">Tipo de Fornecedor*</label>
                    <select name="for_tipo" id="for_tipo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Selecione...</option>
                        
                        <optgroup label="Abastecimento">
                            <option value="posto" @selected(old('for_tipo', $fornecedor->for_tipo) == 'posto')>Posto de Combustível</option>
                            <option value="posto_gnv" @selected(old('for_tipo', $fornecedor->for_tipo) == 'posto_gnv')>Posto GNV</option>
                            <option value="eletroposto" @selected(old('for_tipo', $fornecedor->for_tipo) == 'eletroposto')>Eletroposto / Recarga</option>
                        </optgroup>

                        <optgroup label="Manutenção e Reparo">
                            <option value="oficina" @selected(old('for_tipo', $fornecedor->for_tipo) == 'oficina')>Oficina Mecânica Geral</option>
                            <option value="auto_eletrica" @selected(old('for_tipo', $fornecedor->for_tipo) == 'auto_eletrica')>Auto Elétrica</option>
                            <option value="funilaria" @selected(old('for_tipo', $fornecedor->for_tipo) == 'funilaria')>Funilaria e Pintura</option>
                            <option value="borracharia" @selected(old('for_tipo', $fornecedor->for_tipo) == 'borracharia')>Borracharia / Pneus</option>
                            <option value="concessionaria" @selected(old('for_tipo', $fornecedor->for_tipo) == 'concessionaria')>Concessionária Autorizada</option>
                            <option value="autocentro" @selected(old('for_tipo', $fornecedor->for_tipo) == 'autocentro')>Auto Center (Pneus/Óleo)</option>
                            <option value="loja_pecas" @selected(old('for_tipo', $fornecedor->for_tipo) == 'loja_pecas')>Loja de Autopeças</option>
                            <option value="vidracaria" @selected(old('for_tipo', $fornecedor->for_tipo) == 'vidracaria')>Vidraçaria Automotiva</option>
                        </optgroup>

                        <optgroup label="Serviços e Estética">
                            <option value="lava_rapido" @selected(old('for_tipo', $fornecedor->for_tipo) == 'lava_rapido')>Lava-rápido / Estética</option>
                            <option value="guincho" @selected(old('for_tipo', $fornecedor->for_tipo) == 'guincho')>Guincho / Reboque</option>
                            <option value="vistoria" @selected(old('for_tipo', $fornecedor->for_tipo) == 'vistoria')>Empresa de Vistoria</option>
                            <option value="rastreamento" @selected(old('for_tipo', $fornecedor->for_tipo) == 'rastreamento')>Rastreamento / Telemetria</option>
                            <option value="estacionamento" @selected(old('for_tipo', $fornecedor->for_tipo) == 'estacionamento')>Estacionamento</option>
                        </optgroup>

                        <optgroup label="Administrativo e Outros">
                            <option value="seguradora" @selected(old('for_tipo', $fornecedor->for_tipo) == 'seguradora')>Seguradora / Corretora</option>
                            <option value="despachante" @selected(old('for_tipo', $fornecedor->for_tipo) == 'despachante')>Despachante</option>
                            <option value="concessionaria_rodovia" @selected(old('for_tipo', $fornecedor->for_tipo) == 'concessionaria_rodovia')>Concessionária de Rodovia (Pedágio)</option>
                            <option value="locadora" @selected(old('for_tipo', $fornecedor->for_tipo) == 'locadora')>Locadora de Veículos</option>
                            <option value="ambos" @selected(old('for_tipo', $fornecedor->for_tipo) == 'ambos')>Posto e Oficina (Integrados)</option>
                            <option value="outro" @selected(old('for_tipo', $fornecedor->for_tipo) == 'outro')>Outro</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>

        {{-- Seção de Status --}}
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
             <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Status
            </h3>
            <div class="grid grid-cols-1 gap-6">
                 <div>
                    <label for="for_status" class="block font-medium text-sm text-gray-700">Status*</label>
                    <select name="for_status" id="for_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="1" @selected(old('for_status', $fornecedor->for_status) == 1)>Ativo</option>
                        <option value="2" @selected(old('for_status', $fornecedor->for_status) == 2)>Inativo</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 2: CONTATO E ENDEREÇO --}}
    <div id="tab-contato-content" x-show="tab === 'contato' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force" style="display: none;">
        {{-- Seção de Contato e Endereço --}}
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                Contato e Endereço
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="for_contato_telefone" class="block font-medium text-sm text-gray-700">Telefone</label>
                    <input type="text" name="for_contato_telefone" id="for_contato_telefone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('for_contato_telefone', $fornecedor->for_contato_telefone) }}">
                </div>
                <div>
                    <label for="for_contato_email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input type="email" name="for_contato_email" id="for_contato_email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('for_contato_email', $fornecedor->for_contato_email) }}">
                </div>
                <div class="md:col-span-2">
                    <label for="for_endereco" class="block font-medium text-sm text-gray-700">Endereço</label>
                    <textarea name="for_endereco" id="for_endereco" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('for_endereco', $fornecedor->for_endereco) }}</textarea>
                </div>
            </div>
        </div>
    </div>
    
    {{-- TAB 3: OBSERVAÇÕES --}}
    <div id="tab-obs-content" x-show="tab === 'obs' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force" style="display: none;">
        {{-- Seção de Observações --}}
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
             <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Observações
            </h3>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="for_observacoes" class="block font-medium text-sm text-gray-700">Observações</label>
                    <textarea name="for_observacoes" id="for_observacoes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('for_observacoes', $fornecedor->for_observacoes) }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('fornecedores.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition shadow-sm font-medium">Cancelar</a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm font-medium">Salvar</button>
</div>