<?php

namespace App\Http\Controllers;

use App\Models\Manutencao;
use App\Models\Veiculo;
use App\Models\Servico;
use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Services\LogService;
use Illuminate\Support\Facades\Log; // Adicionado para as regras de negócio

class ManutencaoController extends Controller
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function index(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;

        // Inicia a query base de manutenções
        $query = Manutencao::with(['veiculo', 'servicos'])
            ->where('man_emp_id', $idEmpresa);

        // Aplica os filtros se eles existirem na requisição
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

        // Ordena e pagina os resultados
        $manutencoes = $query->latest('man_data_inicio')->paginate(15);
        
        // Busca os veículos para popular o dropdown de filtro
        $veiculos = Veiculo::where('vei_emp_id', $idEmpresa)->orderBy('vei_placa')->get();

        // Retorna a view com os dados paginados e os veículos para o filtro
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

    public function store(Request $request)
    {
        return $this->salvarManutencao($request, new Manutencao());
    }

    public function update(Request $request, Manutencao $manutencao)
    {
        if ($manutencao->man_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        return $this->salvarManutencao($request, $manutencao);
    }

    private function salvarManutencao(Request $request, Manutencao $manutencao)
    {
        $user = Auth::user();
        $idEmpresa = $user->id_empresa;
        $eraNova = !$manutencao->exists;

        $validatedData = $request->validate([
            'man_vei_id' => ['required', Rule::exists('veiculos', 'vei_id')->where('vei_emp_id', $idEmpresa)],
            'man_for_id' => ['nullable', Rule::exists('fornecedores', 'for_id')->where('for_emp_id', $idEmpresa)],
            'man_tipo' => ['required', Rule::in(['preventiva', 'corretiva', 'preditiva', 'outra'])],
            'man_data_inicio' => ['required', 'date', 'before_or_equal:today'],
            'man_data_fim' => ['nullable', 'date', 'after_or_equal:man_data_inicio'],
            'man_km' => ['required', 'integer', 'min:0'],
            'man_custo_pecas' => ['nullable', 'string'],
            'man_custo_mao_de_obra' => ['nullable', 'string'],
            'man_responsavel' => ['nullable', 'string', 'max:255'],
            'man_nf' => ['nullable', 'string', 'max:255'],
            'man_prox_revisao_data' => [
                'nullable', 
                'date', 
                'after_or_equal:man_data_inicio',
                Rule::requiredIf(function () use ($request) {
                    return $request->input('man_tipo') === 'preventiva' && $request->input('man_status') === 'concluida' && empty($request->input('man_prox_revisao_km'));
                }),
            ],
            'man_prox_revisao_km' => [
                'nullable', 
                'integer', 
                'min:' . $request->input('man_km', 0),
                Rule::requiredIf(function () use ($request) {
                    return $request->input('man_tipo') === 'preventiva' && $request->input('man_status') === 'concluida' && empty($request->input('man_prox_revisao_data'));
                }),
            ],
            'man_status' => ['required', Rule::in(['agendada', 'em_andamento', 'concluida', 'cancelada'])],
            'servicos' => ['nullable', 'array'],
            'servicos.*.id' => ['required_with:servicos', 'integer', Rule::exists('servicos', 'ser_id')->where('ser_emp_id', $idEmpresa)],
            'servicos.*.custo' => ['required_with:servicos', 'string'],
            'servicos.*.garantia' => ['nullable', 'date'],
        ]);

        try {
            DB::beginTransaction();

            $statusOriginal = $manutencao->getOriginal('man_status');

            $validatedData['man_custo_pecas'] = $this->limparValor($request->man_custo_pecas);
            $validatedData['man_custo_mao_de_obra'] = $this->limparValor($request->man_custo_mao_de_obra);

            $manutencao->fill($validatedData);
            $manutencao->man_emp_id = $idEmpresa;
            $manutencao->man_user_id = $user->id;
            $manutencao->save();
            
            $custoTotalServicos = 0;
            if ($request->has('servicos')) {
                $dadosParaPivot = [];
                foreach ($request->servicos as $servico) {
                    $custoLimpo = $this->limparValor($servico['custo']);
                    $dadosParaPivot[$servico['id']] = [
                        'ms_custo' => $custoLimpo,
                        'ms_garantia' => !empty($servico['garantia']) ? $servico['garantia'] : null,
                    ];
                    $custoTotalServicos += $custoLimpo;
                }
                $manutencao->servicos()->sync($dadosParaPivot);
            } else {
                $manutencao->servicos()->sync([]);
            }

            $manutencao->man_custo_total = $custoTotalServicos + $manutencao->man_custo_pecas + $manutencao->man_custo_mao_de_obra;
            $manutencao->save();

            // LÓGICA DO OBSERVER MOVIDA PARA CÁ
            // Verifica se a manutenção foi concluída nesta operação
            $foiConcluidaAgora = $manutencao->man_status === 'concluida' && ($eraNova || $statusOriginal !== 'concluida');

            if ($foiConcluidaAgora) {
                $this->processarRegrasDeNegocioPosConclusao($manutencao);
            }

            DB::commit();

            $mensagem = $eraNova ? 'Manutenção registrada com sucesso!' : 'Manutenção atualizada com sucesso!';
            return redirect()->route('manutencoes.index')->with('success', $mensagem);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocorreu um erro ao salvar a manutenção: ' . $e->getMessage())->withInput();
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

    private function limparValor($valor)
    {
        if (empty($valor)) {
            return 0;
        }
        
        $valor = (string) $valor;
        $lastDot = strrpos($valor, '.');
        $lastComma = strrpos($valor, ',');

        // Caso 1: Formato brasileiro (vírgula é o decimal). Ex: "1.000,50" ou "10,50"
        if ($lastComma !== false && ($lastDot === false || $lastComma > $lastDot)) {
            $valor = str_replace('.', '', $valor); // Remove pontos (milhares)
            $valor = str_replace(',', '.', $valor); // Substitui vírgula (decimal) por ponto
        } 
        // Caso 2: Formato padrão (ponto é o decimal) ou internacional. Ex: "1,000.50" ou "10.50"
        else if ($lastDot !== false && ($lastComma === false || $lastDot > $lastComma)) {
            $valor = str_replace(',', '', $valor); // Remove vírgulas (milhares)
        }
        
        return floatval($valor);
    }

    /**
     * Centraliza a execução das regras de negócio que antes estavam no Observer.
     */
    private function processarRegrasDeNegocioPosConclusao(Manutencao $manutencao): void
    {
        // Força o recarregamento das relações para garantir que temos os dados mais recentes
        $manutencao->load('veiculo', 'servicos');

        $this->atualizarKmVeiculo($manutencao);
        $this->agendarProximaManutencao($manutencao);
    }

    /**
     * REGRA 1: Se o KM da manutenção for maior, atualiza o KM do veículo.
     */
    private function atualizarKmVeiculo(Manutencao $manutencao): void
    {
        $veiculo = $manutencao->veiculo;

        if ($veiculo && $manutencao->man_km > $veiculo->vei_km_atual) {
            $veiculo->vei_km_atual = $manutencao->man_km;
            $veiculo->save();
            Log::info("KM do veículo {$veiculo->vei_placa} atualizado para {$manutencao->man_km} via manutenção #{$manutencao->man_id}.");
        }
    }

    /**
     * REGRA 2: Cria uma nova manutenção agendada se a atual for preventiva e concluída.
     */
    private function agendarProximaManutencao(Manutencao $manutencao): void
    {
        if ($manutencao->man_tipo !== 'preventiva' || (!$manutencao->man_prox_revisao_data && !$manutencao->man_prox_revisao_km)) {
            return;
        }

        $existeAgendada = Manutencao::where('man_vei_id', $manutencao->man_vei_id)
                                    ->where('man_status', 'agendada')
                                    ->exists();

        if ($existeAgendada) {
            Log::info("Nova manutenção preventiva para o veículo {$manutencao->veiculo->vei_placa} não foi criada pois já existe uma agendada.");
            return;
        }

        $novaManutencao = new Manutencao();
        
        $novaManutencao->man_vei_id = $manutencao->man_vei_id;
        $novaManutencao->man_emp_id = $manutencao->man_emp_id;
        $novaManutencao->man_user_id = Auth::id() ?? $manutencao->man_user_id;
        $novaManutencao->man_for_id = $manutencao->man_for_id;
        $novaManutencao->man_tipo = 'preventiva';
        
        $novaManutencao->man_status = 'agendada';
        $novaManutencao->man_data_inicio = $manutencao->man_prox_revisao_data;
        $novaManutencao->man_km = $manutencao->man_prox_revisao_km;
        
        $novaManutencao->man_custo_pecas = 0;
        $novaManutencao->man_custo_mao_de_obra = 0;
        $novaManutencao->man_custo_total = 0;

        $novaManutencao->save();

        // Replica os mesmos serviços, mas sem custo
        $servicosIds = $manutencao->servicos()->pluck('ser_id');
        if ($servicosIds->isNotEmpty()) {
            $novaManutencao->servicos()->attach($servicosIds);
        }

        Log::info("Manutenção #{$novaManutencao->man_id} agendada automaticamente a partir da manutenção #{$manutencao->man_id}.");
    }
}

