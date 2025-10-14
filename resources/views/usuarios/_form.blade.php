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
            <label for="name" class="block font-medium text-sm text-gray-700">Nome*</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full" value="{{ old('name', $usuario->name ?? '') }}" required autofocus>
        </div>
        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">Email*</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full" value="{{ old('email', $usuario->email ?? '') }}" required>
        </div>
        <div>
            <label for="role" class="block font-medium text-sm text-gray-700">Perfil de Acesso*</label>
            <select name="role" id="role" class="mt-1 block w-full">
                <option value="usuario" @selected(old('role', $usuario->role ?? '') == 'usuario')>Usuário</option>
                <option value="master" @selected(old('role', $usuario->role ?? '') == 'master')>Master</option>
            </select>
        </div>

        @if (!$usuario || !$usuario->exists)
        <div>
            <label for="password" class="block font-medium text-sm text-gray-700">Senha*</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full" required>
        </div>
        <div>
            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirmar Senha*</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full" required>
        </div>
        @endif
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('usuarios.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
        {{ ($usuario && $usuario->exists) ? 'Atualizar' : 'Salvar' }}
    </button>
</div>

