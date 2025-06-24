<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\User; // Certifique-se de que o Model User está a ser importado
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = Empresa::latest()->paginate(10);
        return view('admin.empresas.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.empresas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:empresas,cnpj',
            // Garante que o email da empresa também seja único na tabela de usuários
            'email_contato' => 'required|email|max:255|unique:users,email',
            'telefone_contato' => 'required|string|max:20',
        ]);

        // Cria a empresa primeiro
        $empresa = Empresa::create($request->all());

        // LÓGICA PARA CRIAR O USUÁRIO MASTER DA EMPRESA
        $password = Str::random(8); // Gera uma senha aleatória de 8 caracteres

        $user = new User();
        $user->name = 'Master ' . $empresa->nome_fantasia;
        
        // --- CORREÇÃO APLICADA AQUI ---
        // O email do usuário master agora é o mesmo email de contato da empresa
        $user->email = $request->email_contato;
        
        $user->password = Hash::make($password);
        $user->role = 'master';
        $user->email_verified_at = now();
        
        // Associa o ID da empresa recém-criada ao novo usuário
        $user->id_empresa = $empresa->id;
        
        $user->save();

        // Guarda as credenciais para exibir ao admin
        $credentials = [
            'email' => $user->email,
            'password' => $password,
        ];

        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa criada com sucesso!')
                         ->with('credentials', $credentials); // Enviamos as credenciais para a view
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        return view('admin.empresas.edit', compact('empresa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:empresas,cnpj,' . $empresa->id,
            'email_contato' => 'required|email|max:255',
            'telefone_contato' => 'required|string|max:20',
        ]);

        $empresa->update($request->all());

        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa removida com sucesso!');
    }
}
