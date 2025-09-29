<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ServicoController extends Controller
{
    public function index()
    {
        $idEmpresa = Auth::user()->id_empresa;
        $servicos = Servico::where('ser_emp_id', $idEmpresa)
            ->latest()
            ->paginate(15);
        
        return view('servicos.index', compact('servicos'));
    }

    public function create()
    {
        $servico = new Servico();
        return view('servicos.create', compact('servico'));
    }

    public function store(Request $request)
    {
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
        if ($servico->ser_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }
        return view('servicos.edit', compact('servico'));
    }

    public function update(Request $request, Servico $servico)
    {
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
        if ($servico->ser_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }
        $servico->delete();
        return redirect()->route('servicos.index')->with('success', 'Serviço excluído com sucesso!');
    }
}

