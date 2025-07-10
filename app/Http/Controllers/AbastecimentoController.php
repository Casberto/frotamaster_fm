<?php

namespace App\Http\Controllers;

use App\Models\Abastecimento;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AbastecimentoController extends Controller
{
    // ... index, create, edit, destroy e getVeiculoData permanecem iguais ...
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
        $veiculos = Veiculo::where('id_empresa', Auth::user()->id_empresa)->orderBy('placa')->get();
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
            // Validação customizada para o tipo de combustível
            'tipo_combustivel' => ['nullable', 'string', function ($attribute, $value, $fail) use ($request) {
                $veiculo = Veiculo::find($request->id_veiculo);
                if (!$veiculo || $veiculo->tipo_combustivel === 'eletrico') {
                    return; // Se for elétrico, não há combustível para validar
                }

                $combustiveisCompativeis = [];
                if ($veiculo->tipo_combustivel === 'flex') {
                    $combustiveisCompativeis = ['gasolina', 'etanol'];
                } else {
                    $combustiveisCompativeis = [$veiculo->tipo_combustivel];
                }

                if (!in_array($value, $combustiveisCompativeis)) {
                    $fail('O tipo de combustível selecionado não é compatível com o veículo.');
                }
            }],
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
    
    // ... update ...

    public function getVeiculoData($id)
    {
        $veiculo = Veiculo::where('id', $id)
            ->where('id_empresa', Auth::user()->id_empresa)
            ->firstOrFail();

        return response()->json($veiculo);
    }
}
