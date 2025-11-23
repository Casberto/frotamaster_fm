<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FornecedorController extends Controller
{
    public function index(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;
        
        $query = Fornecedor::where('for_emp_id', $idEmpresa);

        if ($request->filled('search')) {
            $query->where('for_nome_fantasia', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('for_tipo', $request->tipo);
        }

        if ($request->filled('status')) {
            $query->where('for_status', $request->status);
        }

        $fornecedores = $query->latest()->paginate(15)->withQueryString();
        
        $tiposExistentes = Fornecedor::where('for_emp_id', $idEmpresa)
            ->select('for_tipo')
            ->distinct()
            ->orderBy('for_tipo')
            ->pluck('for_tipo');

        return view('fornecedores.index', compact('fornecedores', 'tiposExistentes'));
    }

    public function create()
    {
        $fornecedor = new Fornecedor(['for_status' => 1]); 
        return view('fornecedores.create', compact('fornecedor'));
    }

    public function store(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;
        
        $validatedData = $request->validate([
            'for_nome_fantasia' => ['required', 'string', 'max:255'],
            'for_razao_social' => ['nullable', 'string', 'max:255'],
            'for_cnpj_cpf' => ['nullable', 'string', 'max:20', Rule::unique('fornecedores')->where('for_emp_id', $idEmpresa)],
            'for_tipo' => ['required', 'string', 'max:50'],
            'for_contato_email' => ['nullable', 'email', 'max:255'],
            'for_contato_telefone' => ['nullable', 'string', 'max:20'],
            'for_endereco' => ['nullable', 'string'],
            'for_observacoes' => ['nullable', 'string'],
            'for_status' => ['required', 'integer', Rule::in([1, 2])],
        ]);

        Fornecedor::create($validatedData + ['for_emp_id' => $idEmpresa]);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function edit(Fornecedor $fornecedor)
    {
        // Conversão explícita para inteiros para evitar erros de "1" (string) vs 1 (int)
        $empresaFornecedor = (int) $fornecedor->for_emp_id;
        $empresaUsuario = (int) Auth::user()->id_empresa;

        if ($empresaFornecedor !== $empresaUsuario) {
            // SE O ERRO PERSISTIR, DESCOMENTE A LINHA ABAIXO PARA VER OS VALORES NA TELA:
            // dd('Debug:', 'Forn ID:' . $fornecedor->for_id, 'Forn Emp ID: ' . $empresaFornecedor, 'User Emp ID: ' . $empresaUsuario);
            
            abort(403, "Acesso não autorizado. Este fornecedor pertence à empresa $empresaFornecedor, mas você é da empresa $empresaUsuario.");
        }

        return view('fornecedores.edit', compact('fornecedor'));
    }

    public function update(Request $request, Fornecedor $fornecedor)
    {
        $empresaFornecedor = (int) $fornecedor->for_emp_id;
        $empresaUsuario = (int) Auth::user()->id_empresa;

        if ($empresaFornecedor !== $empresaUsuario) {
            abort(403, 'Acesso não autorizado.');
        }

        $validatedData = $request->validate([
            'for_nome_fantasia' => ['required', 'string', 'max:255'],
            'for_razao_social' => ['nullable', 'string', 'max:255'],
            'for_cnpj_cpf' => [
                'nullable', 
                'string', 
                'max:20', 
                Rule::unique('fornecedores')->where('for_emp_id', $empresaUsuario)->ignore($fornecedor->for_id, 'for_id')
            ],
            'for_tipo' => ['required', 'string', 'max:50'],
            'for_contato_email' => ['nullable', 'email', 'max:255'],
            'for_contato_telefone' => ['nullable', 'string', 'max:20'],
            'for_endereco' => ['nullable', 'string'],
            'for_observacoes' => ['nullable', 'string'],
            'for_status' => ['required', 'integer', Rule::in([1, 2])],
        ]);

        $fornecedor->update($validatedData);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Fornecedor $fornecedor)
    {
        if ((int)$fornecedor->for_emp_id !== (int)Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }
        
        $fornecedor->delete();

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor excluído com sucesso!');
    }
}