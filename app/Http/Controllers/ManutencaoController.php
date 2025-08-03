<?php

namespace App\Http\Controllers;

use App\Models\Manutencao;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ManutencaoController extends Controller
{
    /**
     * Exibe uma lista de todas as manutenções da empresa do usuário logado.
     */
    public function index()
    {
        $idEmpresa = Auth::user()->id_empresa;
        $manutencoes = Manutencao::with('veiculo')
            ->where('id_empresa', $idEmpresa)
            ->latest('data_manutencao')
            ->paginate(15);
        
        return view('manutencoes.index', compact('manutencoes'));
    }

    /**
     * Mostra o formulário para criar um novo registro de manutenção.
     */
    public function create()
    {
        $veiculos = Veiculo::where('id_empresa', Auth::user()->id_empresa)->where('status', 'ativo')->orderBy('placa')->get();
        $manutencao = new Manutencao();
        return view('manutencoes.create', compact('veiculos', 'manutencao'));
    }

    /**
     * Salva um novo registro de manutenção no banco de dados.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $idEmpresa = $user->id_empresa;

        $limparValor = function ($valor) {
            if (empty($valor)) return null;
            return floatval(str_replace(['.', ','], ['', '.'], $valor));
        };
        
        $validatedData = $request->validate([
            'id_veiculo' => ['required', Rule::exists('veiculos', 'id')->where('id_empresa', $idEmpresa)],
            'tipo_manutencao' => ['required', Rule::in(['preventiva', 'corretiva', 'preditiva', 'outra'])],
            'descricao_servico' => ['required', 'string', 'max:255'],
            'data_manutencao' => ['required', 'date', 'before_or_equal:today'],
            'quilometragem' => ['required', 'string'],
            'custo_total' => ['required', 'string'],
            'custo_previsto' => ['nullable', 'string'],
            'nome_fornecedor' => ['nullable', 'string', 'max:255'],
            'responsavel' => ['nullable', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string'],
            'proxima_revisao_data' => ['nullable', 'date', 'after_or_equal:data_manutencao'],
            'proxima_revisao_km' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['agendada', 'em_andamento', 'concluida', 'cancelada'])],
        ]);

        $warning = $this->checkForDuplicates($validatedData['id_veiculo'], $validatedData['data_manutencao'], $limparValor($validatedData['quilometragem']));
        
        $manutencao = new Manutencao($validatedData);
        $manutencao->id_empresa = $idEmpresa;
        $manutencao->id_user = $user->id;
        $manutencao->quilometragem = $limparValor($validatedData['quilometragem']);
        $manutencao->proxima_revisao_km = $limparValor($validatedData['proxima_revisao_km']);
        $manutencao->custo_total = $limparValor($validatedData['custo_total']);
        $manutencao->custo_previsto = $limparValor($validatedData['custo_previsto']);
        
        $manutencao->save();

        // Processa as regras de negócio baseadas no status
        $this->handleStatusChange($manutencao);

        return redirect()->route('manutencoes.index')
                         ->with('success', 'Manutenção registrada com sucesso!')
                         ->with('warning', $warning);
    }

    /**
     * Mostra o formulário para editar uma manutenção existente.
     */
    public function edit(Manutencao $manutencao)
    {
        $veiculos = Veiculo::where('id_empresa', Auth::user()->id_empresa)->get();
        return view('manutencoes.edit', compact('manutencao', 'veiculos'));
    }
    
    /**
     * Atualiza uma manutenção existente no banco de dados.
     */
    public function update(Request $request, Manutencao $manutencao)
    {
        // Validação de segurança manual
        if ((int)$manutencao->id_empresa !== (int)Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }

        $idEmpresa = Auth::user()->id_empresa;
        $limparValor = function ($valor) {
            if (empty($valor)) return null;
            return floatval(str_replace(['.', ','], ['', '.'], $valor));
        };
        
        $validatedData = $request->validate([
            'id_veiculo' => ['required', Rule::exists('veiculos', 'id')->where('id_empresa', $idEmpresa)],
            'tipo_manutencao' => ['required', Rule::in(['preventiva', 'corretiva', 'preditiva', 'outra'])],
            'descricao_servico' => ['required', 'string', 'max:255'],
            'data_manutencao' => ['required', 'date', 'before_or_equal:today'],
            'quilometragem' => ['required', 'string'],
            'custo_total' => ['required', 'string'],
            'custo_previsto' => ['nullable', 'string'],
            'nome_fornecedor' => ['nullable', 'string', 'max:255'],
            'responsavel' => ['nullable', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string'],
            'proxima_revisao_data' => ['nullable', 'date', 'after_or_equal:data_manutencao'],
            'proxima_revisao_km' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['agendada', 'em_andamento', 'concluida', 'cancelada'])],
        ]);

        $warning = $this->checkForDuplicates($validatedData['id_veiculo'], $validatedData['data_manutencao'], $limparValor($validatedData['quilometragem']), $manutencao->id);

        $manutencao->fill($validatedData);
        $manutencao->quilometragem = $limparValor($validatedData['quilometragem']);
        $manutencao->proxima_revisao_km = $limparValor($validatedData['proxima_revisao_km']);
        $manutencao->custo_total = $limparValor($validatedData['custo_total']);
        $manutencao->custo_previsto = $limparValor($validatedData['custo_previsto']);
        
        $manutencao->save();

        // Processa as regras de negócio baseadas no status
        $this->handleStatusChange($manutencao);

        return redirect()->route('manutencoes.index')
                         ->with('success', 'Manutenção atualizada com sucesso!')
                         ->with('warning', $warning);
    }

    /**
     * Remove uma manutenção do banco de dados.
     */
    public function destroy(Manutencao $manutencao)
    {
        $manutencao->delete();
        return redirect()->route('manutencoes.index')
                         ->with('success', 'Registro de manutenção removido com sucesso!');
    }

    /**
     * Aplica as regras de negócio após salvar uma manutenção, com base no seu status.
     */
    private function handleStatusChange(Manutencao $manutencao)
    {
        if ($manutencao->status === 'concluida') {
            // 1. Atualiza a quilometragem do veículo se a da manutenção for maior
            $veiculo = $manutencao->veiculo;
            if ($manutencao->quilometragem > $veiculo->quilometragem_atual) {
                $veiculo->quilometragem_atual = $manutencao->quilometragem;
                $veiculo->save();
            }

            // 2. Cria uma nova manutenção agendada se a atual for 'preventiva' e tiver dados de próxima revisão
            if ($manutencao->tipo_manutencao === 'preventiva' && (!empty($manutencao->proxima_revisao_data) || !empty($manutencao->proxima_revisao_km))) {
                Manutencao::create([
                    'id_veiculo' => $manutencao->id_veiculo,
                    'id_empresa' => $manutencao->id_empresa,
                    'id_user' => $manutencao->id_user,
                    'tipo_manutencao' => $manutencao->tipo_manutencao,
                    'descricao_servico' => 'Próxima Revisão Agendada: ' . $manutencao->descricao_servico,
                    'data_manutencao' => $manutencao->proxima_revisao_data ?? now()->addYear(),
                    'quilometragem' => $manutencao->proxima_revisao_km ?? $veiculo->quilometragem_atual,
                    'custo_total' => 0,
                    'status' => 'agendada',
                ]);
            }
        }
    }

    /**
     * Verifica se já existem manutenções agendadas em datas ou quilometragens próximas.
     */
    private function checkForDuplicates($veiculoId, $data, $km, $excludeId = null)
    {
        $dataCarbon = Carbon::parse($data);
        
        $query = Manutencao::where('id_veiculo', $veiculoId)
            ->where('status', 'agendada')
            ->when($excludeId, function ($q) use ($excludeId) {
                return $q->where('id', '!=', $excludeId);
            });

        // Verifica por data próxima
        $proximaData = $query->clone()->whereBetween('data_manutencao', [
            $dataCarbon->clone()->subDays(7),
            $dataCarbon->clone()->addDays(7)
        ])->exists();

        // Verifica por quilometragem próxima
        $proximoKm = $query->clone()->whereBetween('quilometragem', [
            $km - 500,
            $km + 500
        ])->exists();

        if ($proximaData || $proximoKm) {
            return 'Aviso: Já existe uma manutenção agendada para este veículo em data ou quilometragem próxima.';
        }

        return null;
    }
}
