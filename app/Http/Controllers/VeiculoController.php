<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VeiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->id_empresa) {
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para acessar esta área.');
        }
        
        $idEmpresa = Auth::user()->id_empresa;
        $veiculos = Veiculo::where('id_empresa', $idEmpresa)->latest()->paginate(10);
        return view('veiculos.index', compact('veiculos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->id_empresa) {
            return redirect()->route('dashboard')->with('error', 'Apenas usuários de empresas podem cadastrar veículos.');
        }
        return view('veiculos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->id_empresa) {
            return back()->with('error', 'Apenas usuários vinculados a uma empresa podem cadastrar veículos.')->withInput();
        }

        $idEmpresa = Auth::user()->id_empresa;

        // --- CORREÇÃO APLICADA AQUI ---
        $validatedData = $request->validate([
            'placa' => ['required', 'string', 'size:7', Rule::unique('veiculos')->where('id_empresa', $idEmpresa)],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'ano_fabricacao' => ['required', 'integer', 'digits:4'],
            'ano_modelo' => ['required', 'integer', 'digits:4'],
            'quilometragem_atual' => ['required', 'integer'],
            'cor' => ['nullable', 'string', 'max:255'],
            'chassi' => ['nullable', 'string', 'max:255', Rule::unique('veiculos')->where('id_empresa', $idEmpresa)->ignore($request->id)],
            'renavam' => ['nullable', 'string', 'max:255', Rule::unique('veiculos')->where('id_empresa', $idEmpresa)->ignore($request->id)],
            'tipo_veiculo' => ['required', Rule::in(['carro', 'moto', 'caminhao', 'van', 'outro'])],
            'tipo_combustivel' => ['required', Rule::in(['gasolina', 'etanol', 'diesel', 'flex', 'gnv', 'eletrico'])],
            'capacidade_tanque' => ['nullable', 'numeric'], // Campo adicionado na validação
            'data_aquisicao' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['ativo', 'inativo', 'em_manutencao', 'vendido'])],
            'observacoes' => ['nullable', 'string'],
        ]);

        $veiculo = new Veiculo($validatedData);
        $veiculo->id_empresa = $idEmpresa;
        $veiculo->save();

        return redirect()->route('veiculos.index')
                         ->with('success', 'Veículo cadastrado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Veiculo $veiculo)
    {
        if ((int)$veiculo->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403);
        }
        return view('veiculos.edit', compact('veiculo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Veiculo $veiculo)
    {
        if ((int)$veiculo->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403);
        }

        $idEmpresa = Auth::user()->id_empresa;

        // --- CORREÇÃO APLICADA AQUI ---
        $validatedData = $request->validate([
            'placa' => ['required', 'string', 'size:7', Rule::unique('veiculos')->where('id_empresa', $idEmpresa)->ignore($veiculo->id)],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'ano_fabricacao' => ['required', 'integer', 'digits:4'],
            'ano_modelo' => ['required', 'integer', 'digits:4'],
            'quilometragem_atual' => ['required', 'integer'],
            'cor' => ['nullable', 'string', 'max:255'],
            'chassi' => ['nullable', 'string', 'max:255', Rule::unique('veiculos')->where('id_empresa', $idEmpresa)->ignore($veiculo->id)],
            'renavam' => ['nullable', 'string', 'max:255', Rule::unique('veiculos')->where('id_empresa', $idEmpresa)->ignore($veiculo->id)],
            'tipo_veiculo' => ['required', Rule::in(['carro', 'moto', 'caminhao', 'van', 'outro'])],
            'tipo_combustivel' => ['required', Rule::in(['gasolina', 'etanol', 'diesel', 'flex', 'gnv', 'eletrico'])],
            'capacidade_tanque' => ['nullable', 'numeric'], // Campo adicionado na validação
            'data_aquisicao' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['ativo', 'inativo', 'em_manutencao', 'vendido'])],
            'observacoes' => ['nullable', 'string'],
        ]);
        
        $veiculo->update($validatedData);
        return redirect()->route('veiculos.index')
                         ->with('success', 'Veículo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Veiculo $veiculo)
    {
        if ((int)$veiculo->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403);
        }
        
        $veiculo->delete();
        return redirect()->route('veiculos.index')
                         ->with('success', 'Veículo removido com sucesso!');
    }
}
