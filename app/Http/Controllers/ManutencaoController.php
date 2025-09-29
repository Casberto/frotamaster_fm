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

class ManutencaoController extends Controller
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function index()
    {
        $idEmpresa = Auth::user()->id_empresa;
        // Carrega os serviços para exibição na listagem
        $manutencoes = Manutencao::with(['veiculo', 'servicos'])
            ->where('man_emp_id', $idEmpresa)
            ->latest('man_data_inicio')
            ->paginate(15);
        
        return view('manutencoes.index', compact('manutencoes'));
    }
    
    // Método privado para buscar os dados dos formulários
    private function getDadosFormulario()
    {
        $idEmpresa = Auth::user()->id_empresa;
        return [
            // CORREÇÃO: Utiliza os campos com prefixo 'vei_'
            'veiculos' => Veiculo::where('vei_emp_id', $idEmpresa)->where('vei_status', '1')->orderBy('vei_placa')->get(),
            'servicos' => Servico::where('ser_emp_id', $idEmpresa)->orderBy('ser_nome')->get(),
            'fornecedores' => Fornecedor::where('for_emp_id', $idEmpresa)->orderBy('for_nome_fantasia')->get(),
        ];
    }

    public function create()
    {
        $manutencao = new Manutencao();
        $manutencao->servicos = collect(); // Garante que a coleção exista no formulário
        $dados = $this->getDadosFormulario();

        return view('manutencoes.create', compact('manutencao') + $dados);
    }

    public function edit(Manutencao $manutencao)
    {
        // CORREÇÃO: Substituído authorize pela verificação manual padrão do projeto
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
        // CORREÇÃO: Substituído authorize pela verificação manual padrão do projeto
        if ($manutencao->man_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        return $this->salvarManutencao($request, $manutencao);
    }

    private function salvarManutencao(Request $request, Manutencao $manutencao)
    {
        $user = Auth::user();
        $idEmpresa = $user->id_empresa;

        // 1. AJUSTE NA VALIDAÇÃO
        // Adicionada a regra para o campo 'garantia' dentro do array de serviços.
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
            'man_prox_revisao_data' => ['nullable', 'date', 'after_or_equal:man_data_inicio'],
            'man_prox_revisao_km' => ['nullable', 'integer', 'min:' . $request->input('man_km', 0)],
            'man_status' => ['required', Rule::in(['agendada', 'em_andamento', 'concluida', 'cancelada'])],
            'servicos' => ['nullable', 'array'],
            'servicos.*.id' => ['required_with:servicos', 'integer', Rule::exists('servicos', 'ser_id')->where('ser_emp_id', $idEmpresa)],
            'servicos.*.custo' => ['required_with:servicos', 'string'],
            'servicos.*.garantia' => ['nullable', 'date'], // <-- TRECHO ADICIONADO
        ]);

        try {
            DB::beginTransaction();

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
                    
                    // 2. AJUSTE NO CADASTRO DA GARANTIA
                    // Adicionado o campo 'ms_garantia' ao array que será salvo na tabela pivô.
                    $dadosParaPivot[$servico['id']] = [
                        'ms_custo' => $custoLimpo,
                        // Garante que o valor seja nulo se o campo vier vazio.
                        'ms_garantia' => !empty($servico['garantia']) ? $servico['garantia'] : null, // <-- TRECHO ADICIONADO
                    ];

                    $custoTotalServicos += $custoLimpo;
                }
                $manutencao->servicos()->sync($dadosParaPivot);
            } else {
                $manutencao->servicos()->sync([]);
            }

            $manutencao->man_custo_total = $custoTotalServicos + $manutencao->man_custo_pecas + $manutencao->man_custo_mao_de_obra;
            $manutencao->save();

            DB::commit();

            $mensagem = $manutencao->wasRecentlyCreated ? 'Manutenção registrada com sucesso!' : 'Manutenção atualizada com sucesso!';
            return redirect()->route('manutencoes.index')->with('success', $mensagem);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocorreu um erro ao salvar a manutenção: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Manutencao $manutencao)
    {
        // CORREÇÃO: Substituído authorize pela verificação manual padrão do projeto
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

    // Função auxiliar para converter valores monetários (ex: "1.250,50") para o formato do DB
    private function limparValor($valor)
    {
        if (empty($valor)) {
            return 0;
        }
        return floatval(str_replace(['.', ','], ['', '.'], $valor));
    }
}

