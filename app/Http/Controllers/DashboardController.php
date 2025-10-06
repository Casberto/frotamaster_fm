<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Veiculo;
use App\Models\Manutencao;
use App\Models\Abastecimento;
use App\Models\Fornecedor;
use App\Models\Servico;
use Carbon\Carbon;
use Exception;

class DashboardController extends Controller
{
    public function index()
    {
        $idEmpresa = Auth::user()->id_empresa;
        if (!$idEmpresa) {
            return view('dashboard');
        }

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // --- Cards de Resumo ---
        $veiculosAtivos = Veiculo::where('vei_emp_id', $idEmpresa)->where('vei_status', 1)->count();

        $manutencoesVencidas = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', '!=', 'concluida')
            ->where('man_data_inicio', '<', now()->toDateString())
            ->count();

        $alertasProximos = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', 'agendada')
            ->whereBetween('man_data_inicio', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->count();

        // Custo Total do Mês (Manutenções + Abastecimentos)
        $custoMensalManutencoes = Manutencao::where('man_emp_id', $idEmpresa)
            ->whereBetween('man_data_inicio', [$startOfMonth, $endOfMonth])
            ->sum('man_custo_total');
        
        $custoMensalAbastecimentos = Abastecimento::where('aba_emp_id', $idEmpresa)
            ->whereBetween('aba_data', [$startOfMonth, $endOfMonth])
            ->sum('aba_vlr_tot');

        $custoTotalMensal = $custoMensalManutencoes + $custoMensalAbastecimentos;

        // Top Fornecedor do Mês
        $custosManutencaoFornecedor = Manutencao::where('man_emp_id', $idEmpresa)
            ->whereBetween('man_data_inicio', [$startOfMonth, $endOfMonth])
            ->groupBy('man_for_id')
            ->select('man_for_id as for_id', DB::raw('SUM(man_custo_total) as total'))
            ->pluck('total', 'for_id');

        $custosAbastecimentoFornecedor = Abastecimento::where('aba_emp_id', $idEmpresa)
            ->whereBetween('aba_data', [$startOfMonth, $endOfMonth])
            ->groupBy('aba_for_id')
            ->select('aba_for_id as for_id', DB::raw('SUM(aba_vlr_tot) as total'))
            ->pluck('total', 'for_id');

        $custosTotaisFornecedor = [];
        foreach ($custosManutencaoFornecedor as $id => $total) {
            $custosTotaisFornecedor[$id] = ($custosTotaisFornecedor[$id] ?? 0) + $total;
        }
        foreach ($custosAbastecimentoFornecedor as $id => $total) {
            $custosTotaisFornecedor[$id] = ($custosTotaisFornecedor[$id] ?? 0) + $total;
        }
        
        $topFornecedorId = !empty($custosTotaisFornecedor) ? array_search(max($custosTotaisFornecedor), $custosTotaisFornecedor) : null;
        $topFornecedor = $topFornecedorId ? Fornecedor::find($topFornecedorId)->for_nome_fantasia : 'N/A';

        // Serviço mais frequente do Mês
        $servicoMaisFrequenteId = DB::table('manutencao_servico as ms')
            ->join('manutencoes as m', 'ms.ms_man_id', '=', 'm.man_id')
            ->where('m.man_emp_id', $idEmpresa)
            ->whereBetween('m.man_data_inicio', [$startOfMonth, $endOfMonth])
            ->groupBy('ms.ms_ser_id')
            ->select('ms.ms_ser_id', DB::raw('COUNT(ms.ms_ser_id) as total'))
            ->orderByDesc('total')
            ->value('ms_ser_id');

        $servicoMaisFrequente = $servicoMaisFrequenteId ? Servico::find($servicoMaisFrequenteId)->ser_nome : 'N/A';
        
        // Custo Médio por KM
        $kmTotalRodado = 0;
        $veiculosDaEmpresa = Veiculo::where('vei_emp_id', $idEmpresa)->get();
        foreach ($veiculosDaEmpresa as $veiculo) {
            $primeiroKm = $veiculo->abastecimentos()->whereBetween('aba_data', [$startOfMonth, $endOfMonth])->min('aba_km');
            $ultimoKm = $veiculo->abastecimentos()->whereBetween('aba_data', [$startOfMonth, $endOfMonth])->max('aba_km');
            if ($ultimoKm && $primeiroKm) {
                $kmTotalRodado += ($ultimoKm - $primeiroKm);
            }
        }
        $custoMedioPorKm = $kmTotalRodado > 0 ? $custoTotalMensal / $kmTotalRodado : 0;

        // --- Listagem da Frota ---
        $frota = Veiculo::where('vei_emp_id', $idEmpresa)
            ->with([
                'manutencoes' => function ($query) {
                    $query->with(['fornecedor', 'servicos'])->orderBy('man_data_inicio', 'desc');
                },
                'abastecimentos' => function ($query) {
                     $query->orderBy('aba_data', 'desc');
                },
                'ultimoAbastecimento'
            ])
            ->where('vei_status', 1)
            ->get();
        
        // --- Análise Comparativa de Custos (para o modal) ---
        $frota->each(function ($veiculo) use ($startOfMonth, $endOfMonth) {
            // Mês Atual
            $veiculo->custo_mensal_manutencao = $veiculo->manutencoes()
                ->whereBetween('man_data_inicio', [$startOfMonth, $endOfMonth])
                ->sum('man_custo_total');
            
            $veiculo->custo_mensal_abastecimento = $veiculo->abastecimentos()
                ->whereBetween('aba_data', [$startOfMonth, $endOfMonth])
                ->sum('aba_vlr_tot');

            $veiculo->custo_total_mensal = $veiculo->custo_mensal_manutencao + $veiculo->custo_mensal_abastecimento;
            
            // Mês Anterior
            $startOfLastMonth = now()->subMonth()->startOfMonth();
            $endOfLastMonth = now()->subMonth()->endOfMonth();
            $veiculo->custo_anterior_manutencao = $veiculo->manutencoes()->whereBetween('man_data_inicio', [$startOfLastMonth, $endOfLastMonth])->sum('man_custo_total');
            $veiculo->custo_anterior_abastecimento = $veiculo->abastecimentos()->whereBetween('aba_data', [$startOfLastMonth, $endOfLastMonth])->sum('aba_vlr_tot');
            $veiculo->custo_total_anterior = $veiculo->custo_anterior_manutencao + $veiculo->custo_anterior_abastecimento;
        
            // Média 12 Meses
            $start12Months = now()->subMonths(11)->startOfMonth();
            $totalManutencao12 = $veiculo->manutencoes()->where('man_data_inicio', '>=', $start12Months)->sum('man_custo_total');
            $totalAbastecimento12 = $veiculo->abastecimentos()->where('aba_data', '>=', $start12Months)->sum('aba_vlr_tot');
            $veiculo->media_custo_total_12_meses = ($totalManutencao12 + $totalAbastecimento12) / 12;
        });

        // --- Próximos Lembretes ---
        $proximosLembretes = Manutencao::with('veiculo')
            ->where('man_emp_id', $idEmpresa)
            ->where('man_status', 'agendada')
            ->where('man_data_inicio', '>=', now()->toDateString())
            ->orderBy('man_data_inicio', 'asc')
            ->take(5)
            ->get();


        return view('dashboard', compact(
            'veiculosAtivos',
            'manutencoesVencidas',
            'alertasProximos',
            'custoTotalMensal',
            'topFornecedor',
            'servicoMaisFrequente',
            'custoMedioPorKm',
            'frota',
            'proximosLembretes'
        ));
    }

    /**
     * Busca o histórico de um veículo para exibir no modal.
     */
    public function getVeiculoHistorico($id)
    {
        try {
            $idEmpresa = Auth::user()->id_empresa;

            $veiculo = Veiculo::where('vei_id', $id)
                ->where('vei_emp_id', $idEmpresa)
                ->firstOrFail();

            $manutencoes = $veiculo->manutencoes()
                ->with(['fornecedor', 'servicos'])
                ->orderBy('man_data_inicio', 'desc')
                ->take(5) // Ajustado para buscar os 5 mais recentes
                ->get();
                
            $abastecimentos = $veiculo->abastecimentos()
                ->with('fornecedor')
                ->orderBy('aba_data', 'desc')
                ->take(5) // Ajustado para buscar os 5 mais recentes
                ->get();
            
            return response()->json([
                'manutencoes' => $manutencoes,
                'abastecimentos' => $abastecimentos
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocorreu um erro no servidor.',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
