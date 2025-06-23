@if ($errors->any())
    <div class="bg-red-200 text-red-800 p-4 rounded-md mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="nome_fantasia" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nome Fantasia</label>
        {{-- Correção: Usamos ?? para fornecer um valor padrão se $empresa não existir --}}
        <input type="text" name="nome_fantasia" id="nome_fantasia" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('nome_fantasia', $empresa->nome_fantasia ?? '') }}" required>
    </div>
    <div>
        <label for="razao_social" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Razão Social</label>
        <input type="text" name="razao_social" id="razao_social" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('razao_social', $empresa->razao_social ?? '') }}" required>
    </div>
    <div>
        <label for="cnpj" class="block font-medium text-sm text-gray-700 dark:text-gray-300">CNPJ</label>
        {{-- Removido o '}' extra que estava no seu código anterior --}}
        <input type="text" name="cnpj" id="cnpj" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('cnpj', $empresa->cnpj ?? '') }}" required>
    </div>
    <div>
        <label for="email_contato" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email de Contato</label>
        <input type="email" name="email_contato" id="email_contato" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('email_contato', $empresa->email_contato ?? '') }}" required>
    </div>
    <div>
        <label for="telefone_contato" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Telefone de Contato</label>
        <input type="text" name="telefone_contato" id="telefone_contato" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('telefone_contato', $empresa->telefone_contato ?? '') }}" required>
    </div>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('admin.empresas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
        Cancelar
    </a>
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
        Salvar
    </button>
</div>
