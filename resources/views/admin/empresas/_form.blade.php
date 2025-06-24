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
    <h3 class="form-section-title">Dados da Empresa</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="nome_fantasia" class="block font-medium text-sm text-gray-700">Nome Fantasia*</label>
            <input type="text" name="nome_fantasia" id="nome_fantasia" class="mt-1 block w-full" value="{{ old('nome_fantasia', $empresa->nome_fantasia ?? '') }}" required>
        </div>
        <div>
            <label for="razao_social" class="block font-medium text-sm text-gray-700">Razão Social*</label>
            <input type="text" name="razao_social" id="razao_social" class="mt-1 block w-full" value="{{ old('razao_social', $empresa->razao_social ?? '') }}" required>
        </div>
        <div>
            <label for="cnpj" class="block font-medium text-sm text-gray-700">CNPJ*</label>
            <input type="text" name="cnpj" id="cnpj" class="mt-1 block w-full" value="{{ old('cnpj', $empresa->cnpj ?? '') }}" required>
        </div>
        <div>
            <label for="email_contato" class="block font-medium text-sm text-gray-700">Email de Contato*</label>
            <input type="email" name="email_contato" id="email_contato" class="mt-1 block w-full" value="{{ old('email_contato', $empresa->email_contato ?? '') }}" required>
        </div>
        <div>
            <label for="telefone_contato" class="block font-medium text-sm text-gray-700">Telefone de Contato*</label>
            <input type="text" name="telefone_contato" id="telefone_contato" class="mt-1 block w-full" value="{{ old('telefone_contato', $empresa->telefone_contato ?? '') }}" required>
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
