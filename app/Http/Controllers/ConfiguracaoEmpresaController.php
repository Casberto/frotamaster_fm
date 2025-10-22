<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ConfiguracaoPadrao;

class ConfiguracaoEmpresaController extends Controller
{

    public function index(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;

        $query = ConfiguracaoEmpresa::where('cfe_emp_id', $idEmpresa)
            ->with('configuracaoPadrao');

        // Adiciona o filtro por termo de busca
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('configuracaoPadrao', function ($q) use ($searchTerm) {
                $q->where('cfp_chave', 'like', '%' . $searchTerm . '%')
                  ->orWhere('cfp_descricao', 'like', '%' . $searchTerm . '%');
            });
        }

        $configuracoes = $query->get();

        $configuracoesAgrupadas = $configuracoes->groupBy(function ($item) {
            return optional($item->configuracaoPadrao)->cfp_modulo ?? 'outros';
        });

        return view('parametros.index', [
            'configuracoesAgrupadas' => $configuracoesAgrupadas,
        ]);
    }

    /**
     * Atualiza as configurações da empresa.
     */
    public function update(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;

        $request->validate([
            'config' => 'present|array'
        ]);

        $configs = $request->input('config', []);

        DB::beginTransaction();
        try {
            foreach ($configs as $id => $valor) {
                $config = ConfiguracaoEmpresa::where('cfe_id', $id)
                    ->where('cfe_emp_id', $idEmpresa)
                    ->firstOrFail();

                // Trata o valor booleano corretamente
                if ($config->configuracaoPadrao->cfp_tipo === 'boolean') {
                    $valor = filter_var($valor, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
                }

                $config->update(['cfe_valor' => $valor]);
            }

            DB::commit();

            return redirect()->route('parametros.index')->with('success', 'Parâmetros atualizados com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao atualizar parâmetros: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao salvar os parâmetros. Tente novamente.');
        }
    }
}

