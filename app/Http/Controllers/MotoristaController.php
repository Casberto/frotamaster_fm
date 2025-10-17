<?php

namespace App\Http\Controllers;

use App\Models\Motorista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MotoristaController extends Controller
{
    public function index(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;
        $query = Motorista::where('mot_emp_id', $idEmpresa);

        if ($request->filled('search')) {
            $query->where('mot_nome', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('mot_status', $request->status);
        }

        $motoristas = $query->latest()->paginate(15);

        return view('motoristas.index', compact('motoristas'));
    }

    public function create()
    {
        return view('motoristas.create', ['motorista' => new Motorista()]);
    }

    public function store(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;

        // Adicionar validação de CPF único por empresa
        $request->validate([
            'mot_nome' => 'required|string|max:150',
            'mot_cpf' => [
                'nullable',
                'string',
                'max:14',
                Rule::unique('motoristas')->where(fn ($query) => $query->where('mot_emp_id', $idEmpresa)),
            ],
            // Adicionar outras validações conforme necessário
        ]);

        $dados = $request->all();
        $dados['mot_emp_id'] = $idEmpresa;

        Motorista::create($dados);

        return redirect()->route('motoristas.index')->with('success', 'Motorista cadastrado com sucesso!');
    }

    public function edit(Motorista $motorista)
    {
        if ($motorista->mot_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        return view('motoristas.edit', compact('motorista'));
    }

    public function update(Request $request, Motorista $motorista)
    {
        if ($motorista->mot_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        $idEmpresa = Auth::user()->id_empresa;

        $request->validate([
            'mot_nome' => 'required|string|max:150',
            'mot_cpf' => [
                'nullable',
                'string',
                'max:14',
                Rule::unique('motoristas')->where(fn ($query) => $query->where('mot_emp_id', $idEmpresa))->ignore($motorista->mot_id, 'mot_id'),
            ],
            // Adicionar outras validações conforme necessário
        ]);

        $motorista->update($request->all());

        return redirect()->route('motoristas.index')->with('success', 'Motorista atualizado com sucesso!');
    }

    public function destroy(Motorista $motorista)
    {
        if ($motorista->mot_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        
        $motorista->delete();

        return redirect()->route('motoristas.index')->with('success', 'Motorista removido com sucesso!');
    }
}
