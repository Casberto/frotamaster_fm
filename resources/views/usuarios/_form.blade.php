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
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Dados do Usuário
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700">Nome*</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('name', $usuario->name ?? '') }}" required autofocus>
                </div>
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700">Email*</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('email', $usuario->email ?? '') }}" required>
                </div>
                <div>
                    <label for="role" class="block font-medium text-sm text-gray-700">Perfil de Acesso*</label>
                    <select name="role" id="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="usuario" @selected(old('role', $usuario->role ?? '') == 'usuario')>Usuário</option>
                        <option value="master" @selected(old('role', $usuario->role ?? '') == 'master')>Master</option>
                    </select>
                </div>

                @if (!$usuario || !$usuario->exists)
                <div>
                    <label for="password" class="block font-medium text-sm text-gray-700">Senha*</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <div>
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirmar Senha*</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('usuarios.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition shadow-sm font-medium">Cancelar</a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm font-medium">
        {{ ($usuario && $usuario->exists) ? 'Atualizar' : 'Salvar' }}
    </button>
</div>
