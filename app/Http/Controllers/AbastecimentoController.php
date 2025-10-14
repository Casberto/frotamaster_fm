<?php

namespace App\Http\Controllers;

use App\Models\Abastecimento;
use App\Models\Veiculo;
use App\Models\Fornecedor;
use App\Http\Requests\StoreAbastecimentoRequest;
use App\Services\AbastecimentoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbastecimentoController extends Controller
{
    public function __construct(protected AbastecimentoService $abastecimentoService)
    {
    }

    public function index(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;
        $query = Abastecimento::where('aba_emp_id', $idEmpresa)->with('veiculo');

        if ($request->filled('veiculo_id')) {
            $query->where('aba_vei_id', $request->veiculo_id);
        }
        if ($request->filled('data_inicio')) {
            $query->where('aba_data', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->where('aba_data', '<=', $request->data_fim);
        }
        
        $abastecimentos = $query->latest('aba_data')->paginate(15)->appends($request->query());
        $veiculos = Veiculo::where('vei_emp_id', $idEmpresa)->orderBy('vei_placa')->get();

        return view('abastecimentos.index', compact('abastecimentos', 'veiculos'));
    }

    private function getDadosFormulario()
    {
        $idEmpresa = Auth::user()->id_empresa;
        $veiculos = Veiculo::where('vei_emp_id', $idEmpresa)->orderBy('vei_placa')->get();
        return [
            'veiculos' => $veiculos,
            'fornecedores' => Fornecedor::where('for_emp_id', $idEmpresa)->orderBy('for_nome_fantasia')->get(),
            'veiculosData' => $veiculos->mapWithKeys(fn ($v) => [$v->vei_id => ['km' => $v->vei_km_atual, 'combustivel_tipo' => $v->vei_combustivel]])
        ];
    }

    public function create()
    {
        return view('abastecimentos.create', $this->getDadosFormulario());
    }

    public function store(StoreAbastecimentoRequest $request)
    {
        try {
            $this->abastecimentoService->salvarAbastecimento($request, new Abastecimento());
            return redirect()->route('abastecimentos.index')->with('success', 'Abastecimento registrado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro ao salvar o abastecimento: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Abastecimento $abastecimento)
    {
        if ($abastecimento->aba_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        return view('abastecimentos.edit', ['abastecimento' => $abastecimento] + $this->getDadosFormulario());
    }

    public function update(StoreAbastecimentoRequest $request, Abastecimento $abastecimento)
    {
        if ($abastecimento->aba_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        
        try {
            $this->abastecimentoService->salvarAbastecimento($request, $abastecimento);
            return redirect()->route('abastecimentos.index')->with('success', 'Abastecimento atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro ao atualizar o abastecimento: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Abastecimento $abastecimento)
    {
        if ($abastecimento->aba_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        $abastecimento->delete();
        return redirect()->route('abastecimentos.index')->with('success', 'Registro de abastecimento removido com sucesso!');
    }
}
