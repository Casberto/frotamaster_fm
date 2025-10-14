<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     * Mostra todos os usuários da mesma empresa do usuário logado.
     */
    public function index()
    {
        $id_empresa = Auth::user()->id_empresa;
        // Garante que apenas usuários da mesma empresa sejam listados.
        // E não lista o próprio usuário logado.
        $usuarios = User::where('id_empresa', $id_empresa)
                        ->where('id', '!=', Auth::id())
                        ->latest()
                        ->paginate(10);

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     * Exibe o formulário de criação de usuário.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     * Salva o novo usuário no banco de dados.
     */
    public function store(Request $request)
    {
        $id_empresa = Auth::user()->id_empresa;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', Rule::in(['master', 'usuario'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_empresa' => $id_empresa, // Vincula o novo usuário à empresa do Master
            'role' => $request->role,
            'must_change_password' => true, // Força a troca de senha no primeiro login
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        // Opcional: pode ser usado para uma tela de detalhes do usuário
        $this->authorize('view', $usuario);
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     * Exibe o formulário de edição de usuário.
     */
    public function edit(User $usuario)
    {
        // Garante que um master só pode editar usuários da sua própria empresa.
        if (Auth::user()->id_empresa !== $usuario->id_empresa) {
            abort(403);
        }

        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     * Atualiza os dados do usuário.
     */
    public function update(Request $request, User $usuario)
    {
        // Garante que um master só pode atualizar usuários da sua própria empresa.
        if (Auth::user()->id_empresa !== $usuario->id_empresa) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'role' => ['required', Rule::in(['master', 'usuario'])],
        ]);

        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     * Exclui um usuário.
     */
    public function destroy(User $usuario)
    {
        // Garante que um master só pode excluir usuários da sua própria empresa.
        if (Auth::user()->id_empresa !== $usuario->id_empresa) {
            abort(403);
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuário excluído com sucesso!');
    }
}
