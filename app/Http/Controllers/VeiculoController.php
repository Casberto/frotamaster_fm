<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Http\Requests\StoreVeiculoRequest;
use App\Services\VeiculoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VeiculoController extends Controller
{
    public function __construct(protected VeiculoService $veiculoService)
    {
    }

    public function index(Request $request)
    {
        if (!Auth::user()->temPermissao('VEI001')) {
            return redirect()->route('dashboard')->with('error', 'O usuário não possuí permissão à essa tela');
        }

        $idEmpresa = Auth::user()->id_empresa;

        $query = Veiculo::where('vei_emp_id', $idEmpresa);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(fn($q) => $q->where('vei_placa', 'like', $searchTerm)->orWhere('vei_modelo', 'like', $searchTerm));
        }
        if ($request->filled('tipo')) {
            $query->where('vei_tipo', $request->tipo);
        }
        if ($request->filled('status')) {
            $query->where('vei_status', $request->status);
        }

        $veiculos = $query->latest('created_at')->paginate(10);
        $tipos = [
            '6' => 'Automóvel', 
            '13' => 'Camioneta', 
            '14' => 'Caminhão', 
            '17' => 'Caminhão Trator', 
            '2' => 'Ciclomotor', 
            '7' => 'Micro-ônibus', 
            '4' => 'Motocicleta', 
            '3' => 'Motoneta', 
            '8' => 'Ônibus', 
            '21' => 'Quadriciclo', 
            '10' => 'Reboque', 
            '11' => 'Semirreboque', 
            '5' => 'Triciclo', 
            '25' => 'Utilitário', 
            '22' => 'Chassi Plataforma'
        ];

        return view('veiculos.index', compact('veiculos', 'tipos'));
    }

    public function create()
    {
        if (!Auth::user()->temPermissao('VEI002')) {
            return redirect()->route('veiculos.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        return view('veiculos.create');
    }

    public function store(StoreVeiculoRequest $request)
    {
        if (!Auth::user()->temPermissao('VEI002')) {
            return redirect()->route('veiculos.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        try {
            $this->veiculoService->salvarVeiculo($request, new Veiculo());
            return redirect()->route('veiculos.index')->with('success', 'Veículo cadastrado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro ao salvar o veículo: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Veiculo $veiculo)
    {
        if (!Auth::user()->temPermissao('VEI003')) {
            return redirect()->route('veiculos.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        if ($veiculo->vei_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        return view('veiculos.edit', compact('veiculo'));
    }

    public function update(StoreVeiculoRequest $request, Veiculo $veiculo)
    {
        if (!Auth::user()->temPermissao('VEI003')) {
            return redirect()->route('veiculos.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        if ($veiculo->vei_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        
        try {
            $this->veiculoService->salvarVeiculo($request, $veiculo);
            return redirect()->route('veiculos.index')->with('success', 'Veículo atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro ao atualizar o veículo: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Veiculo $veiculo)
    {
        if (!Auth::user()->temPermissao('VEI004')) {
            return redirect()->route('veiculos.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        try {
            $this->veiculoService->deletarVeiculo($veiculo);
            return redirect()->route('veiculos.index')->with('success', 'Veículo removido com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro ao remover o veículo: ' . $e->getMessage());
        }
    }
}
