<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;
        
        $query = Fornecedor::where('for_emp_id', $idEmpresa);

        // Filtro de busca por nome
        if ($request->filled('search')) {
            $query->where('for_nome_fantasia', 'like', '%' . $request->search . '%');
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('for_tipo', $request->tipo);
        }

        // CORREÇÃO: Filtro por status ajustado para 'for_status'
        if ($request->filled('status')) {
            $query->where('for_status', $request->status);
        }

        $fornecedores = $query->latest()->paginate(15)->withQueryString();
        
        return view('fornecedores.index', compact('fornecedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // CORREÇÃO: Padrão '1' para for_status (Ativo)
        $fornecedor = new Fornecedor(['for_status' => 1]); 
        return view('fornecedores.create', compact('fornecedor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;

        $validatedData = $request->validate($this->validationRules());

        Fornecedor::create($validatedData + ['for_emp_id' => $idEmpresa]);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fornecedor $fornecedor)
    {
        if ($fornecedor->for_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }
        return view('fornecedores.edit', compact('fornecedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fornecedor $fornecedor)
    {
        if ($fornecedor->for_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }

        $validatedData = $request->validate($this->validationRules($fornecedor->for_id));

        $fornecedor->update($validatedData);

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fornecedor $fornecedor)
    {
        if ($fornecedor->for_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }
        
        $fornecedor->delete();

        return redirect()->route('fornecedores.index')->with('success', 'Fornecedor excluído com sucesso!');
    }

    /**
     * Regras de validação centralizadas.
     */
    private function validationRules($id = null): array
    {
        return [
            'for_nome_fantasia' => ['required', 'string', 'max:255'],
            'for_razao_social' => ['nullable', 'string', 'max:255'],
            'for_cnpj_cpf' => ['nullable', 'string', 'max:20'],
            'for_tipo' => ['required', Rule::in(['oficina', 'posto', 'ambos', 'outro'])],
            'for_contato_email' => ['nullable', 'email', 'max:255'],
            'for_contato_telefone' => ['nullable', 'string', 'max:20'],
            'for_endereco' => ['nullable', 'string'],
            'for_observacoes' => ['nullable', 'string'],
            // CORREÇÃO: Validação ajustada para for_status
            'for_status' => ['required', 'integer', Rule::in([1, 2])],
        ];
    }
}

