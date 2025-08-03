<?php

namespace App\Http\Controllers;

use App\Models\Abastecimento;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AbastecimentoController extends Controller
{
    public function index()
    {
        $abastecimentos = Abastecimento::where('id_empresa', Auth::user()->id_empresa)
            ->with('veiculo', 'user') // Carrega o veículo e o usuário para a listagem
            ->latest('data_abastecimento')
            ->paginate(15);
            
        return view('abastecimentos.index', compact('abastecimentos'));
    }

    public function create()
    {
        $idEmpresa = Auth::user()->id_empresa;
        $veiculos = Veiculo::where('id_empresa', $idEmpresa)
            ->where('status', 'ativo')
            ->orderBy('placa')
            ->get();

        // Cria um mapa de ID do veículo => KM atual para o JavaScript
        $veiculosKmMap = $veiculos->pluck('quilometragem_atual', 'id');

        return view('abastecimentos.create', compact('veiculos', 'veiculosKmMap'));
    }

    public function store(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;

        // Função para limpar e converter valores monetários
        $limparValor = function ($valor) {
            if (empty($valor)) return 0;
            return floatval(str_replace(['.', ','], ['', '.'], $valor));
        };

        // Mapa para validar a ordem dos níveis do tanque
        $niveisOrdem = ['reserva' => 0, '1/4' => 1, '1/2' => 2, '3/4' => 3, 'cheio' => 4];

        // 1. Validação dos Dados
        $validatedData = $request->validate([
            'id_veiculo' => ['required', Rule::exists('veiculos', 'id')->where('id_empresa', $idEmpresa)],
            'data_abastecimento' => ['required', 'date', 'before_or_equal:today'],
            'quilometragem' => ['required', 'integer', 'min:0', function ($attribute, $value, $fail) use ($request) {
                $veiculo = Veiculo::find($request->id_veiculo);
                if (!$veiculo) return;

                // Regra: KM menor que o atual só é permitido para datas passadas.
                if ($value < $veiculo->quilometragem_atual) {
                    $ultimoAbastecimento = Abastecimento::where('id_veiculo', $veiculo->id)
                                                        ->latest('data_abastecimento')
                                                        ->first();
                    
                    if ($ultimoAbastecimento && Carbon::parse($request->data_abastecimento)->gte($ultimoAbastecimento->data_abastecimento)) {
                        $fail('A quilometragem só pode ser menor que a atual ('.$veiculo->quilometragem_atual.' km) para registros com data retroativa.');
                    }
                }
            }],
            'tipo_combustivel' => ['required'],
            'custo_total' => ['required', 'string'],
            'quantidade' => ['required', 'string'],
            'valor_por_unidade' => ['required', 'string'],
            'nome_posto' => ['nullable', 'string', 'max:255'],
            'nivel_tanque_chegada' => ['nullable', 'string'],
            'nivel_tanque_saida' => ['nullable', 'string', function ($attribute, $value, $fail) use ($request, $niveisOrdem) {
                $chegada = $request->nivel_tanque_chegada;
                $saida = $value;

                // Se ambos os campos foram preenchidos, valida a ordem
                if ($chegada && $saida && isset($niveisOrdem[$chegada]) && isset($niveisOrdem[$saida])) {
                    if ($niveisOrdem[$saida] < $niveisOrdem[$chegada]) {
                        $fail('O nível de saída não pode ser menor que o nível de chegada.');
                    }
                }
            }],
        ]);

        // 2. Lógica de Salvamento
        $veiculo = Veiculo::find($validatedData['id_veiculo']);

        $abastecimento = new Abastecimento();
        $abastecimento->id_empresa = $idEmpresa;
        $abastecimento->id_user = Auth::id(); // Salva o ID do usuário logado
        $abastecimento->id_veiculo = $validatedData['id_veiculo'];
        $abastecimento->data_abastecimento = $validatedData['data_abastecimento'];
        $abastecimento->quilometragem = $validatedData['quilometragem'];
        
        $abastecimento->unidade_medida = $veiculo->tipo_combustivel === 'eletrico' ? 'kWh' : 'Litros';
        $abastecimento->tipo_combustivel = $validatedData['tipo_combustivel'];

        $abastecimento->quantidade = $limparValor($validatedData['quantidade']);
        $abastecimento->valor_por_unidade = $limparValor($validatedData['valor_por_unidade']);
        $abastecimento->custo_total = $limparValor($validatedData['custo_total']);
        
        $abastecimento->nome_posto = $validatedData['nome_posto'];
        $abastecimento->nivel_tanque_chegada = $validatedData['nivel_tanque_chegada'];
        $abastecimento->nivel_tanque_saida = $validatedData['nivel_tanque_saida'];
        
        // Define 'tanque_cheio' com base na seleção de 'nivel_tanque_saida'
        $abastecimento->tanque_cheio = ($validatedData['nivel_tanque_saida'] === 'cheio');
        
        $abastecimento->save();

        // 3. Atualiza a Quilometragem do Veículo (se necessário)
        if ($abastecimento->quilometragem > $veiculo->quilometragem_atual) {
            $veiculo->quilometragem_atual = $abastecimento->quilometragem;
            $veiculo->save();
        }

        return redirect()->route('abastecimentos.index')->with('success', 'Abastecimento registrado com sucesso!');
    }

    public function edit(Abastecimento $abastecimento)
    {
        if ((int)$abastecimento->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }

        $idEmpresa = Auth::user()->id_empresa;
        $veiculos = Veiculo::where('id_empresa', $idEmpresa)
            ->where('status', 'ativo')
            ->orderBy('placa')
            ->get();
        
        $veiculosKmMap = $veiculos->pluck('quilometragem_atual', 'id');

        return view('abastecimentos.edit', compact('abastecimento', 'veiculos', 'veiculosKmMap'));
    }


    public function update(Request $request, Abastecimento $abastecimento)
    {
        if ((int)$abastecimento->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }

        $idEmpresa = Auth::user()->id_empresa;

        $limparValor = function ($valor) {
            if (empty($valor)) return 0;
            return floatval(str_replace(['.', ','], ['', '.'], $valor));
        };

        $niveisOrdem = ['reserva' => 0, '1/4' => 1, '1/2' => 2, '3/4' => 3, 'cheio' => 4];

        $validatedData = $request->validate([
            'id_veiculo' => ['required', Rule::exists('veiculos', 'id')->where('id_empresa', $idEmpresa)],
            'data_abastecimento' => ['required', 'date', 'before_or_equal:today'],
            'quilometragem' => ['required', 'integer', 'min:0'],
            'tipo_combustivel' => ['required'],
            'custo_total' => ['required', 'string'],
            'quantidade' => ['required', 'string'],
            'valor_por_unidade' => ['required', 'string'],
            'nome_posto' => ['nullable', 'string', 'max:255'],
            'nivel_tanque_chegada' => ['nullable', 'string'],
            'nivel_tanque_saida' => ['nullable', 'string', function ($attribute, $value, $fail) use ($request, $niveisOrdem) {
                $chegada = $request->nivel_tanque_chegada;
                $saida = $value;
                if ($chegada && $saida && isset($niveisOrdem[$chegada]) && isset($niveisOrdem[$saida])) {
                    if ($niveisOrdem[$saida] < $niveisOrdem[$chegada]) {
                        $fail('O nível de saída não pode ser menor que o nível de chegada.');
                    }
                }
            }],
        ]);

        $veiculo = Veiculo::find($validatedData['id_veiculo']);
        
        $abastecimento->fill($validatedData);
        $abastecimento->unidade_medida = $veiculo->tipo_combustivel === 'eletrico' ? 'kWh' : 'Litros';
        $abastecimento->quantidade = $limparValor($validatedData['quantidade']);
        $abastecimento->valor_por_unidade = $limparValor($validatedData['valor_por_unidade']);
        $abastecimento->custo_total = $limparValor($validatedData['custo_total']);
        $abastecimento->tanque_cheio = ($validatedData['nivel_tanque_saida'] === 'cheio');
        
        $abastecimento->save();

        if ($abastecimento->quilometragem > $veiculo->quilometragem_atual) {
            $veiculo->quilometragem_atual = $abastecimento->quilometragem;
            $veiculo->save();
        }

        return redirect()->route('abastecimentos.index')->with('success', 'Abastecimento atualizado com sucesso!');
    }

    public function getVeiculoData($id)
    {
        if ((int)$abastecimento->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }
        
        $veiculo = Veiculo::where('id', $id)
            ->where('id_empresa', Auth::user()->id_empresa)
            ->firstOrFail();

        return response()->json($veiculo);
    }
}
