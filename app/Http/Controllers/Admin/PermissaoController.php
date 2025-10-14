<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permissao;
use Illuminate\Http\Request;

class PermissaoController extends Controller
{
    public function index()
    {
        $permissoes = Permissao::latest()->paginate(15);
        return view('admin.permissoes.index', compact('permissoes'));
    }

    public function create()
    {
        return view('admin.permissoes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'prm_modulo' => 'required|string|max:100',
            'prm_acao' => 'required|string|max:50',
            'prm_descricao' => 'nullable|string|max:255',
        ]);

        Permissao::create($request->all());

        return redirect()->route('admin.permissoes.index')->with('success', 'Permissão criada com sucesso.');
    }

    public function edit(Permissao $permissao)
    {
        return view('admin.permissoes.edit', compact('permissao'));
    }

    public function update(Request $request, Permissao $permissao)
    {
        $request->validate([
            'prm_modulo' => 'required|string|max:100',
            'prm_acao' => 'required|string|max:50',
            'prm_descricao' => 'nullable|string|max:255',
        ]);

        $permissao->update($request->all());

        return redirect()->route('admin.permissoes.index')->with('success', 'Permissão atualizada com sucesso.');
    }

    public function destroy(Permissao $permissao)
    {
        $permissao->delete();
        return redirect()->route('admin.permissoes.index')->with('success', 'Permissão excluída com sucesso.');
    }
}
