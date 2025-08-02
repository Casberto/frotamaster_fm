<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VeiculoController extends Controller
{
    public function index()
    {
        if (!Auth::user()->id_empresa) {
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para acessar esta área.');
        }
        
        $idEmpresa = Auth::user()->id_empresa;
        $veiculos = Veiculo::where('id_empresa', $idEmpresa)->latest()->paginate(10);
        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        if (!Auth::user()->id_empresa) {
            return redirect()->route('dashboard')->with('error', 'Apenas usuários de empresas podem cadastrar veículos.');
        }
        return view('veiculos.create');
    }

     public function store(Request $request)
    {
        if (!Auth::user()->id_empresa) {
            return back()->with('error', 'Apenas usuários vinculados a uma empresa podem cadastrar veículos.')->withInput();
        }

        $idEmpresa = Auth::user()->id_empresa;

        $validatedData = $request->validate([
            'placa' => ['required', 'string', 'min:8', 'max:8', Rule::unique('veiculos')->where('id_empresa', $idEmpresa)],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'ano_fabricacao' => ['required', 'integer', 'digits:4', 'gte:1940'],
            'ano_modelo' => ['required', 'integer', 'digits:4', 'gte:ano_fabricacao'],
            'quilometragem_inicial' => ['required', 'integer', 'min:0', 'max:9999999'],
            'quilometragem_atual' => ['required', 'integer', 'gte:quilometragem_inicial', 'max:9999999'],
            'cor' => ['nullable', 'string', 'max:255'],
            'chassi' => ['nullable', 'string', 'size:17', 'regex:/^[A-HJ-NPR-Z0-9]{17}$/i', Rule::unique('veiculos')->where('id_empresa', $idEmpresa)],
            'renavam' => ['nullable', 'string', 'min:9', 'max:11', Rule::unique('veiculos')->where('id_empresa', $idEmpresa)],
            'tipo_veiculo' => ['required', Rule::in(['carro', 'moto', 'caminhao', 'van', 'outro'])],
            'tipo_combustivel' => ['required', Rule::in(['gasolina', 'etanol', 'diesel', 'flex', 'gnv', 'eletrico'])],
            'capacidade_tanque' => ['nullable', 'numeric'],
            'consumo_medio_fabricante' => ['nullable', 'numeric'],
            'data_aquisicao' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['ativo', 'inativo', 'em_manutencao', 'vendido'])],
            'observacoes' => ['nullable', 'string'],
        ]);

        // Formata os dados antes de salvar
        $validatedData['placa'] = strtoupper($validatedData['placa']);
        if (isset($validatedData['chassi'])) {
            $validatedData['chassi'] = strtoupper($validatedData['chassi']);
        }

        $veiculo = new Veiculo($validatedData);
        $veiculo->id_empresa = $idEmpresa;
        $veiculo->save();

        return redirect()->route('veiculos.index')
                         ->with('success', 'Veículo cadastrado com sucesso!');
    }

    public function edit(Veiculo $veiculo)
    {
        if ((int)$veiculo->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403);
        }
        return view('veiculos.edit', compact('veiculo'));
    }

    public function update(Request $request, Veiculo $veiculo)
    {
        if ((int)$veiculo->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403);
        }

        $request->merge([
            'quilometragem_atual' => str_replace('.', '', $request->quilometragem_atual)
        ]);

        $idEmpresa = Auth::user()->id_empresa;

        $validatedData = $request->validate([
            'placa' => ['required', 'string', 'min:8', 'max:8', Rule::unique('veiculos')->where('id_empresa', $idEmpresa)->ignore($veiculo->id)],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'ano_fabricacao' => ['required', 'integer', 'digits:4', 'gte:1940'],
            'ano_modelo' => ['required', 'integer', 'digits:4', 'gte:ano_fabricacao'],
            'quilometragem_inicial' => ['required', 'integer', 'min:0', 'max:9999999'],
            'quilometragem_atual' => ['required', 'integer', 'gte:quilometragem_inicial', 'max:9999999'],
            'cor' => ['nullable', 'string', 'max:255'],
            'chassi' => ['nullable', 'string', 'size:17', 'regex:/^[A-HJ-NPR-Z0-9]{17}$/i', Rule::unique('veiculos')->where('id_empresa', $idEmpresa)->ignore($veiculo->id)],
            'renavam' => ['nullable', 'string', 'min:9', 'max:11', Rule::unique('veiculos')->where('id_empresa', $idEmpresa)->ignore($veiculo->id)],
            'tipo_veiculo' => ['required', Rule::in(['carro', 'moto', 'caminhao', 'van', 'outro'])],
            'tipo_combustivel' => ['required', Rule::in(['gasolina', 'etanol', 'diesel', 'flex', 'gnv', 'eletrico'])],
            'capacidade_tanque' => ['nullable', 'numeric'],
            'consumo_medio_fabricante' => ['nullable', 'numeric'],
            'data_aquisicao' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['ativo', 'inativo', 'em_manutencao', 'vendido'])],
            'observacoes' => ['nullable', 'string'],
        ]);

        $validatedData['placa'] = strtoupper($validatedData['placa']);
        if (isset($validatedData['chassi'])) {
            $validatedData['chassi'] = strtoupper($validatedData['chassi']);
        }
        
        $veiculo->update($validatedData);
        return redirect()->route('veiculos.index')
                         ->with('success', 'Veículo atualizado com sucesso!');
    }
    
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
