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
    {{-- Seção de Dados Principais --}}
    <div class="form-section">
        <h3 class="form-section-title">Dados Principais</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="for_nome_fantasia" class="block font-medium text-sm text-gray-700">Nome Fantasia*</label>
                <input type="text" name="for_nome_fantasia" id="for_nome_fantasia" class="mt-1 block w-full" value="{{ old('for_nome_fantasia', $fornecedor->for_nome_fantasia) }}" required autofocus>
            </div>
            <div>
                <label for="for_razao_social" class="block font-medium text-sm text-gray-700">Razão Social</label>
                <input type="text" name="for_razao_social" id="for_razao_social" class="mt-1 block w-full" value="{{ old('for_razao_social', $fornecedor->for_razao_social) }}">
            </div>
            <div>
                <label for="for_cnpj_cpf" class="block font-medium text-sm text-gray-700">CNPJ / CPF</label>
                <input type="text" name="for_cnpj_cpf" id="for_cnpj_cpf" class="mt-1 block w-full" value="{{ old('for_cnpj_cpf', $fornecedor->for_cnpj_cpf) }}">
            </div>
            <div>
                <label for="for_tipo" class="block font-medium text-sm text-gray-700">Tipo de Fornecedor*</label>
                <select name="for_tipo" id="for_tipo" class="mt-1 block w-full" required>
                    <option value="oficina" @selected(old('for_tipo', $fornecedor->for_tipo) == 'oficina')>Oficina Mecânica</option>
                    <option value="posto" @selected(old('for_tipo', $fornecedor->for_tipo) == 'posto')>Posto de Combustível</option>
                    <option value="ambos" @selected(old('for_tipo', $fornecedor->for_tipo) == 'ambos')>Oficina e Posto</option>
                    <option value="outro" @selected(old('for_tipo', $fornecedor->for_tipo) == 'outro')>Outro</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Seção de Contato e Endereço --}}
    <div class="form-section">
        <h3 class="form-section-title">Contato e Endereço</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="for_contato_telefone" class="block font-medium text-sm text-gray-700">Telefone</label>
                <input type="text" name="for_contato_telefone" id="for_contato_telefone" class="mt-1 block w-full" value="{{ old('for_contato_telefone', $fornecedor->for_contato_telefone) }}">
            </div>
            <div>
                <label for="for_contato_email" class="block font-medium text-sm text-gray-700">Email</label>
                <input type="email" name="for_contato_email" id="for_contato_email" class="mt-1 block w-full" value="{{ old('for_contato_email', $fornecedor->for_contato_email) }}">
            </div>
            <div class="md:col-span-2">
                <label for="for_endereco" class="block font-medium text-sm text-gray-700">Endereço</label>
                <textarea name="for_endereco" id="for_endereco" rows="2" class="mt-1 block w-full">{{ old('for_endereco', $fornecedor->for_endereco) }}</textarea>
            </div>
        </div>
    </div>
    
    {{-- Seção de Status e Observações --}}
    <div class="form-section">
         <h3 class="form-section-title">Status e Observações</h3>
        <div class="grid grid-cols-1 gap-6">
             <div>
                {{-- CORREÇÃO: Nome do campo e valores ajustados para for_status --}}
                <label for="for_status" class="block font-medium text-sm text-gray-700">Status*</label>
                <select name="for_status" id="for_status" class="mt-1 block w-full" required>
                    <option value="1" @selected(old('for_status', $fornecedor->for_status) == 1)>Ativo</option>
                    <option value="2" @selected(old('for_status', $fornecedor->for_status) == 2)>Inativo</option>
                </select>
            </div>
            <div>
                <label for="for_observacoes" class="block font-medium text-sm text-gray-700">Observações</label>
                <textarea name="for_observacoes" id="for_observacoes" rows="3" class="mt-1 block w-full">{{ old('for_observacoes', $fornecedor->for_observacoes) }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('fornecedores.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Salvar</button>
</div>

