<?php

namespace App\Http\Controllers;

use App\Models\Abastecimento;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AbastecimentoController extends Controller
{
    public function index()
    {
        $idEmpresa = Auth::user()->id_empresa;
        $abastecimentos = Abastecimento::with('veiculo')
            ->where('id_empresa', $idEmpresa)
            ->latest()
            ->paginate(15);
            
        return view('abastecimentos.index', compact('abastecimentos'));
    }

    public function create()
    {
        $veiculos = Veiculo::where('id_empresa', Auth::user()->id_empresa)->get();
        return view('abastecimentos.create', compact('veiculos'));
    }

    public function store(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;

        $limparValor = function ($valor) {
            return floatval(str_replace(['.', ','], ['', '.'], $valor));
        };

        $validatedData = $request->validate([
            'id_veiculo' => ['required', Rule::exists('veiculos', 'id')->where('id_empresa', $idEmpresa)],
            'data_abastecimento' => ['required', 'date'],
            'quilometragem' => ['required', 'integer'],
            'tipo_combustivel' => ['sometimes', 'required', 'string'],
            'quantidade' => ['required', 'string'],
            'valor_por_unidade' => ['required', 'string'],
            'custo_total' => ['required', 'string'],
            'nome_posto' => ['nullable', 'string', 'max:255'],
            'nivel_tanque_inicio' => ['nullable', 'string'],
            'tanque_cheio' => ['nullable', 'boolean'],
        ]);

        $veiculo = Veiculo::find($validatedData['id_veiculo']);
        $unidadeMedida = $veiculo->tipo_combustivel === 'eletrico' ? 'kWh' : 'Litros';

        $abastecimento = new Abastecimento();
        $abastecimento->id_empresa = $idEmpresa;
        $abastecimento->id_veiculo = $validatedData['id_veiculo'];
        $abastecimento->data_abastecimento = $validatedData['data_abastecimento'];
        $abastecimento->quilometragem = $validatedData['quilometragem'];
        $abastecimento->tipo_combustivel = $validatedData['tipo_combustivel'] ?? null;
        $abastecimento->unidade_medida = $unidadeMedida;
        $abastecimento->quantidade = $limparValor($validatedData['quantidade']);
        $abastecimento->valor_por_unidade = $limparValor($validatedData['valor_por_unidade']);
        $abastecimento->custo_total = $limparValor($validatedData['custo_total']);
        $abastecimento->nome_posto = $validatedData['nome_posto'];
        $abastecimento->nivel_tanque_inicio = $validatedData['nivel_tanque_inicio'];
        $abastecimento->tanque_cheio = $request->has('tanque_cheio');
        
        $abastecimento->save();

        return redirect()->route('abastecimentos.index')->with('success', 'Abastecimento registrado com sucesso!');
    }

    public function edit(Abastecimento $abastecimento)
    {
        if ((int)$abastecimento->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403);
        }
        $veiculos = Veiculo::where('id_empresa', Auth::user()->id_empresa)->orderBy('placa')->get();
        return view('abastecimentos.edit', compact('abastecimento', 'veiculos'));
    }


    public function update(Request $request, Abastecimento $abastecimento)
    {
        if ((int)$abastecimento->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403);
        }

        $idEmpresa = Auth::user()->id_empresa;

        $validatedData = $request->validate([
            'id_veiculo' => ['required', Rule::exists('veiculos', 'id')->where('id_empresa', $idEmpresa)],
            'data_abastecimento' => ['required', 'date'],
            'quilometragem' => ['required', 'integer'],
            'tipo_combustivel' => ['required', Rule::in(['gasolina', 'etanol', 'diesel', 'gnv'])],
            'litros' => ['required', 'numeric'],
            'valor_por_litro' => ['required', 'numeric'],
            'nome_posto' => ['nullable', 'string', 'max:255'],
            'tanque_cheio' => ['nullable', 'boolean'],
        ]);
        
        $validatedData['custo_total'] = $validatedData['litros'] * $validatedData['valor_por_litro'];
        $validatedData['tanque_cheio'] = $request->has('tanque_cheio');

        $abastecimento->update($validatedData);

        return redirect()->route('abastecimentos.index')
                         ->with('success', 'Abastecimento atualizado com sucesso!');
    }

    public function destroy(Abastecimento $abastecimento)
    {
        if ((int)$abastecimento->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403);
        }
        $abastecimento->delete();
        return redirect()->route('abastecimentos.index')
                         ->with('success', 'Registro de abastecimento removido com sucesso!');
    }

    public function getVeiculoData($id)
    {
        $veiculo = Veiculo::where('id', $id)
            ->where('id_empresa', Auth::user()->id_empresa)
            ->firstOrFail();

        return response()->json($veiculo);
    }
}
