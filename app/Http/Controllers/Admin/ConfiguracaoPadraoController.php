<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoPadrao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;

class ConfiguracaoPadraoController extends Controller
{
    public function index()
    {
        $configuracoes = ConfiguracaoPadrao::latest('cfp_id')->paginate(15);
        return view('admin.configuracoes.index', compact('configuracoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.configuracoes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cfp_modulo' => 'required|string|max:50',
            'cfp_chave' => 'required|string|max:100|unique:configuracoes_padrao,cfp_chave',
            'cfp_valor' => 'required|string|max:255',
            'cfp_tipo' => 'required|string|in:string,int,boolean,text',
            'cfp_descricao' => 'required|string|max:255',
        ]);

        ConfiguracaoPadrao::create($validated);

        // Roda o comando para sincronizar a nova chave com todas as empresas existentes
        Artisan::call('config:sync');

        return redirect()->route('admin.configuracoes-padrao.index')
                         ->with('success', 'Configuração padrão criada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConfiguracaoPadrao $configuracoes_padrao)
    {
        return view('admin.configuracoes.edit', ['configuracao' => $configuracoes_padrao]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConfiguracaoPadrao $configuracoes_padrao)
    {
        $validated = $request->validate([
            'cfp_modulo' => 'required|string|max:50',
            'cfp_chave' => [
                'required',
                'string',
                'max:100',
                Rule::unique('configuracoes_padrao', 'cfp_chave')->ignore($configuracoes_padrao->cfp_id, 'cfp_id'),
            ],
            'cfp_valor' => 'required|string|max:255',
            'cfp_tipo' => 'required|string|in:string,int,boolean,text',
            'cfp_descricao' => 'required|string|max:255',
        ]);

        $configuracoes_padrao->update($validated);

        return redirect()->route('admin.configuracoes-padrao.index')
                         ->with('success', 'Configuração padrão atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConfiguracaoPadrao $configuracoes_padrao)
    {
        $configuracoes_padrao->delete();
        return redirect()->route('admin.configuracoes-padrao.index')
                         ->with('success', 'Configuração padrão excluída com sucesso.');
    }
}

