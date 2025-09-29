<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FornecedorController extends Controller
{
    public function index()
    {
        $idEmpresa = Auth::user()->id_empresa;
        $fornecedores = Fornecedor::where('for_emp_id', $idEmpresa)
            ->latest()
            ->paginate(15);
        
        return view('fornecedores.index', compact('fornecedores'));
    }

    public function create()
    {
        $fornecedor = new Fornecedor();
        return view('fornecedores.create', compact('fornecedor'));
    }

    public function store(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;

        $validatedData = $request->validate([
            'for_nome_fantasia' => ['required', 'string', 'max:255'],
            'for_razao_social' => ['nullable', 'string', 'max:255'],
            'for_cnpj_cpf' => ['nullable', 'string', 'max:20'],
            'for_contato_email' => ['nullable', 'email', 'max:255'],
            'for_contato_telefone' => ['nullable', 'string', 'max:20'],
            'for_endereco' => ['nullable', 'string'],
            'for_observacoes' => ['nullable', 'string'],
        ]);

        Fornecedor::create($validatedData + ['for_emp_id' => $idEmpresa]);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function edit(Fornecedor $fornecedor)
    {
        if ($fornecedor->for_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }
        return view('fornecedores.edit', compact('fornecedor'));
    }

    public function update(Request $request, Fornecedor $fornecedor)
    {
        if ($fornecedor->for_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }

        $validatedData = $request->validate([
            'for_nome_fantasia' => ['required', 'string', 'max:255'],
            'for_razao_social' => ['nullable', 'string', 'max:255'],
            'for_cnpj_cpf' => ['nullable', 'string', 'max:20'],
            'for_contato_email' => ['nullable', 'email', 'max:255'],
            'for_contato_telefone' => ['nullable', 'string', 'max:20'],
            'for_endereco' => ['nullable', 'string'],
            'for_observacoes' => ['nullable', 'string'],
        ]);

        $fornecedor->update($validatedData);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Fornecedor $fornecedor)
    {
        if ($fornecedor->for_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }
        $fornecedor->delete();
        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor excluído com sucesso!');
    }
}

