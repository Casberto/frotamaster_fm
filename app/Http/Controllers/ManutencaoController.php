<?php

namespace App\Http\Controllers;

use App\Models\Manutencao;
use App\Models\Veiculo;
use App\Models\Servico;
use App\Models\Fornecedor;
use App\Http\Requests\StoreManutencaoRequest;
use App\Services\ManutencaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManutencaoController extends Controller
{
    public function __construct(protected ManutencaoService $manutencaoService)
    {
    }

    public function index(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;

        $query = Manutencao::with(['veiculo', 'servicos'])->where('man_emp_id', $idEmpresa);

        if ($request->filled('data_inicio')) {
            $query->where('man_data_inicio', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->where('man_data_inicio', '<=', $request->data_fim);
        }
        if ($request->filled('veiculo_id')) {
            $query->where('man_vei_id', $request->veiculo_id);
        }
        if ($request->filled('status')) {
            $query->where('man_status', $request->status);
        }

        $manutencoes = $query->latest('man_data_inicio')->paginate(15);
        $veiculos = Veiculo::where('vei_emp_id', $idEmpresa)->orderBy('vei_placa')->get();

        return view('manutencoes.index', compact('manutencoes', 'veiculos'));
    }

    private function getDadosFormulario()
    {
        $idEmpresa = Auth::user()->id_empresa;
        return [
            'veiculos' => Veiculo::where('vei_emp_id', $idEmpresa)->where('vei_status', '1')->orderBy('vei_placa')->get(),
            'servicos' => Servico::where('ser_emp_id', $idEmpresa)->orderBy('ser_nome')->get(),
            'fornecedores' => Fornecedor::where('for_emp_id', $idEmpresa)->orderBy('for_nome_fantasia')->get(),
        ];
    }

    public function create()
    {
        $manutencao = new Manutencao();
        $manutencao->servicos = collect();
        $dados = $this->getDadosFormulario();

        return view('manutencoes.create', compact('manutencao') + $dados);
    }

    public function edit(Manutencao $manutencao)
    {
        if ($manutencao->man_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        $manutencao->load('servicos');
        $dados = $this->getDadosFormulario();

        return view('manutencoes.edit', compact('manutencao') + $dados);
    }

    public function store(StoreManutencaoRequest $request)
    {
        try {
            $this->manutencaoService->salvarManutencao($request, new Manutencao());
            return redirect()->route('manutencoes.index')->with('success', 'Manutenção registrada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro ao salvar a manutenção: ' . $e->getMessage())->withInput();
        }
    }

    public function update(StoreManutencaoRequest $request, Manutencao $manutencao)
    {
        if ($manutencao->man_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        
        try {
            $this->manutencaoService->salvarManutencao($request, $manutencao);
            return redirect()->route('manutencoes.index')->with('success', 'Manutenção atualizada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro ao atualizar a manutenção: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Manutencao $manutencao)
    {
        if ($manutencao->man_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        try {
            $manutencao->delete();
            return redirect()->route('manutencoes.index')->with('success', 'Manutenção excluída com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro ao excluir a manutenção.');
        }
    }
}
