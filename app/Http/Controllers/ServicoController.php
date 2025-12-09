<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ServicoController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->temPermissao('SER001')) {
            return redirect()->route('dashboard')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        $idEmpresa = Auth::user()->id_empresa;
        $query = Servico::where('ser_emp_id', $idEmpresa);

        if ($request->filled('search')) {
            $query->where('ser_nome', 'like', '%' . $request->search . '%');
        }

        $servicos = $query->latest()->paginate(15);
        
        return view('servicos.index', compact('servicos'));
    }

    public function create()
    {
        if (!Auth::user()->temPermissao('SER002')) {
            return redirect()->route('servicos.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        $servico = new Servico();
        return view('servicos.create', compact('servico'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->temPermissao('SER002')) {
            return redirect()->route('servicos.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        $idEmpresa = Auth::user()->id_empresa;

        $validatedData = $request->validate([
            'ser_nome' => ['required', 'string', 'max:255', Rule::unique('servicos')->where('ser_emp_id', $idEmpresa)],
            'ser_descricao' => ['nullable', 'string'],
        ]);

        Servico::create($validatedData + ['ser_emp_id' => $idEmpresa]);

        return redirect()->route('servicos.index')->with('success', 'Serviço cadastrado com sucesso!');
    }

    public function edit(Servico $servico)
    {
        if (!Auth::user()->temPermissao('SER003')) {
            return redirect()->route('servicos.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        if ($servico->ser_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }
        return view('servicos.edit', compact('servico'));
    }

    public function update(Request $request, Servico $servico)
    {
        if (!Auth::user()->temPermissao('SER003')) {
            return redirect()->route('servicos.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        if ($servico->ser_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }
        $idEmpresa = Auth::user()->id_empresa;

        $validatedData = $request->validate([
            'ser_nome' => ['required', 'string', 'max:255', Rule::unique('servicos')->where('ser_emp_id', $idEmpresa)->ignore($servico->ser_id, 'ser_id')],
            'ser_descricao' => ['nullable', 'string'],
        ]);

        $servico->update($validatedData);

        return redirect()->route('servicos.index')->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy(Servico $servico)
    {
        if (!Auth::user()->temPermissao('SER004')) {
            return redirect()->route('servicos.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        if ($servico->ser_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }
        $servico->delete();
        return redirect()->route('servicos.index')->with('success', 'Serviço excluído com sucesso!');
    }
}

