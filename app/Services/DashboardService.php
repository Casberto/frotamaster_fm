<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Veiculo;
use App\Models\Manutencao;
use App\Models\Abastecimento;
use App\Models\Fornecedor;
use App\Models\Servico;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Classe de serviço para encapsular a lógica de negócio do Dashboard.
 */
class DashboardService
{
    private $idEmpresa;

    public function __construct()
    {
        $this->idEmpresa = Auth::user()->id_empresa;
    }

    /**
     * Reúne todos os dados necessários para a view do dashboard.
     *
     * @return array
     */
    public function getDashboardData(): array
    {
        if (!$this->idEmpresa) {
            return [];
        }

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // --- Cards de Resumo ---
        $summaryCards = $this->getSummaryCardData($startOfMonth, $endOfMonth);

        // --- Frota e Lembretes ---
        $frota = $this->getFleetData($startOfMonth, $endOfMonth);
        $proximosLembretes = $this->getUpcomingReminders();

        return array_merge($summaryCards, [
            'frota'             => $frota,
            'proximosLembretes' => $proximosLembretes,
        ]);
    }

    /**
     * Reúne os dados para os cards de resumo e análise.
     */
    private function getSummaryCardData(Carbon $startOfMonth, Carbon $endOfMonth): array
    {
        $custoMensalManutencoes = Manutencao::where('man_emp_id', $this->idEmpresa)
            ->whereBetween('man_data_inicio', [$startOfMonth, $endOfMonth])
            ->sum('man_custo_total');

        $custoMensalAbastecimentos = Abastecimento::where('aba_emp_id', $this->idEmpresa)
            ->whereBetween('aba_data', [$startOfMonth, $endOfMonth])
            ->sum('aba_vlr_tot');
        
        $custoTotalMensal = $custoMensalManutencoes + $custoMensalAbastecimentos;

        $kmTotalRodado = 0;
        $veiculosDaEmpresa = Veiculo::where('vei_emp_id', $this->idEmpresa)->get();
        foreach ($veiculosDaEmpresa as $veiculo) {
            $primeiroKm = $veiculo->abastecimentos()->whereBetween('aba_data', [$startOfMonth, $endOfMonth])->min('aba_km');
            $ultimoKm = $veiculo->abastecimentos()->whereBetween('aba_data', [$startOfMonth, $endOfMonth])->max('aba_km');
            if ($ultimoKm && $primeiroKm) {
                $kmTotalRodado += ($ultimoKm - $primeiroKm);
            }
        }
        
        return [
            'veiculosAtivos'       => Veiculo::where('vei_emp_id', $this->idEmpresa)->where('vei_status', 1)->count(),
            'manutencoesVencidas'  => Manutencao::where('man_emp_id', $this->idEmpresa)->where('man_status', '!=', 'concluida')->where('man_data_inicio', '<', now()->toDateString())->count(),
            'alertasProximos'      => Manutencao::where('man_emp_id', $this->idEmpresa)->where('man_status', 'agendada')->whereBetween('man_data_inicio', [now()->toDateString(), now()->addDays(7)->toDateString()])->count(),
            'custoTotalMensal'     => $custoTotalMensal,
            'topFornecedor'        => $this->getTopFornecedor($startOfMonth, $endOfMonth),
            'servicoMaisFrequente' => $this->getMostFrequentService($startOfMonth, $endOfMonth),
            'custoMedioPorKm'      => $kmTotalRodado > 0 ? $custoTotalMensal / $kmTotalRodado : 0,
        ];
    }

    /**
     * Busca e processa os dados da frota.
     */
    private function getFleetData(Carbon $startOfMonth, Carbon $endOfMonth)
    {
        $frota = Veiculo::where('vei_emp_id', $this->idEmpresa)
            ->with([
                'manutencoes' => fn ($q) => $q->with(['fornecedor', 'servicos'])->orderBy('man_data_inicio', 'desc'),
                'ultimoAbastecimento'
            ])
            ->where('vei_status', 1)
            ->get();

        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();
        $start12Months = now()->subMonths(11)->startOfMonth();

        $frota->each(function ($veiculo) use ($startOfMonth, $endOfMonth, $startOfLastMonth, $endOfLastMonth, $start12Months) {
            $veiculo->custo_mensal_manutencao = $veiculo->manutencoes->whereBetween('man_data_inicio', [$startOfMonth, $endOfMonth])->sum('man_custo_total');
            $veiculo->custo_mensal_abastecimento = $veiculo->abastecimentos()->whereBetween('aba_data', [$startOfMonth, $endOfMonth])->sum('aba_vlr_tot');
            $veiculo->custo_total_mensal = $veiculo->custo_mensal_manutencao + $veiculo->custo_mensal_abastecimento;
            
            $veiculo->custo_anterior_manutencao = $veiculo->manutencoes()->whereBetween('man_data_inicio', [$startOfLastMonth, $endOfLastMonth])->sum('man_custo_total');
            $veiculo->custo_anterior_abastecimento = $veiculo->abastecimentos()->whereBetween('aba_data', [$startOfLastMonth, $endOfLastMonth])->sum('aba_vlr_tot');
            $veiculo->custo_total_anterior = $veiculo->custo_anterior_manutencao + $veiculo->custo_anterior_abastecimento;
        
            $totalManutencao12 = $veiculo->manutencoes()->where('man_data_inicio', '>=', $start12Months)->sum('man_custo_total');
            $totalAbastecimento12 = $veiculo->abastecimentos()->where('aba_data', '>=', $start12Months)->sum('aba_vlr_tot');
            $veiculo->media_custo_total_12_meses = ($totalManutencao12 + $totalAbastecimento12) / 12;
        });

        return $frota;
    }

    /**
     * Busca os próximos lembretes de manutenção.
     */
    private function getUpcomingReminders()
    {
        return Manutencao::with('veiculo')
            ->where('man_emp_id', $this->idEmpresa)
            ->where('man_status', 'agendada')
            ->where('man_data_inicio', '>=', now()->toDateString())
            ->orderBy('man_data_inicio', 'asc')
            ->take(5)
            ->get();
    }

    /**
     * Identifica o fornecedor com maior custo no período.
     */
    private function getTopFornecedor(Carbon $startOfMonth, Carbon $endOfMonth): string
    {
        $custosManutencao = Manutencao::where('man_emp_id', $this->idEmpresa)->whereBetween('man_data_inicio', [$startOfMonth, $endOfMonth])->groupBy('man_for_id')->select('man_for_id as for_id', DB::raw('SUM(man_custo_total) as total'))->pluck('total', 'for_id');
        $custosAbastecimento = Abastecimento::where('aba_emp_id', $this->idEmpresa)->whereBetween('aba_data', [$startOfMonth, $endOfMonth])->groupBy('aba_for_id')->select('aba_for_id as for_id', DB::raw('SUM(aba_vlr_tot) as total'))->pluck('total', 'for_id');
        
        $custosTotais = [];
        $custosManutencao->each(fn($total, $id) => $custosTotais[$id] = ($custosTotais[$id] ?? 0) + $total);
        $custosAbastecimento->each(fn($total, $id) => $custosTotais[$id] = ($custosTotais[$id] ?? 0) + $total);
        
        if (empty($custosTotais)) return 'N/A';
        
        $topFornecedorId = array_search(max($custosTotais), $custosTotais);
        return $topFornecedorId ? Fornecedor::find($topFornecedorId)->for_nome_fantasia ?? 'N/A' : 'N/A';
    }

    /**
     * Identifica o serviço mais frequente no período.
     */
    private function getMostFrequentService(Carbon $startOfMonth, Carbon $endOfMonth): string
    {
        $servicoId = DB::table('manutencao_servico as ms')
            ->join('manutencoes as m', 'ms.ms_man_id', '=', 'm.man_id')
            ->where('m.man_emp_id', $this->idEmpresa)
            ->whereBetween('m.man_data_inicio', [$startOfMonth, $endOfMonth])
            ->groupBy('ms.ms_ser_id')
            ->select('ms.ms_ser_id', DB::raw('COUNT(ms.ms_ser_id) as total'))
            ->orderByDesc('total')
            ->value('ms_ser_id');

        return $servicoId ? Servico::find($servicoId)->ser_nome ?? 'N/A' : 'N/A';
    }

    /**
     * Busca dados para os gráficos com base no período.
     */
    public function getChartData(Request $request): array
    {
        $period = (int)$request->input('period', 30);
        $startDate = now()->subDays($period);
        $endDate = now();

        $custosPeriodo = $this->getCostComposition($startDate, $endDate);
        $evolucaoCustos = $this->getCostEvolution();
        $custoPorVeiculo = $this->getCostByVehicle($startDate, $endDate);

        return [
            'custosPeriodo'   => $custosPeriodo,
            'evolucaoCustos'  => $evolucaoCustos,
            'custoPorVeiculo' => $custoPorVeiculo,
        ];
    }
    
    private function getCostComposition(Carbon $startDate, Carbon $endDate): array
    {
        $custoManutencoes = Manutencao::where('man_emp_id', $this->idEmpresa)->whereBetween('man_data_inicio', [$startDate, $endDate])->sum('man_custo_total');
        $custoAbastecimentos = Abastecimento::where('aba_emp_id', $this->idEmpresa)->whereBetween('aba_data', [$startDate, $endDate])->sum('aba_vlr_tot');
        
        return [
            'labels' => ['Manutenções', 'Abastecimentos'],
            'data'   => [round($custoManutencoes, 2), round($custoAbastecimentos, 2)],
        ];
    }

    private function getCostEvolution(): array
    {
        $labels = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M/y');
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $totalManutencoes = Manutencao::where('man_emp_id', $this->idEmpresa)->whereBetween('man_data_inicio', [$start, $end])->sum('man_custo_total');
            $totalAbastecimentos = Abastecimento::where('aba_emp_id', $this->idEmpresa)->whereBetween('aba_data', [$start, $end])->sum('aba_vlr_tot');
            
            $data[] = round($totalManutencoes + $totalAbastecimentos, 2);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getCostByVehicle(Carbon $startDate, Carbon $endDate): array
    {
        $custosManutencao = Manutencao::where('man_emp_id', $this->idEmpresa)->whereBetween('man_data_inicio', [$startDate, $endDate])->groupBy('man_vei_id')->select('man_vei_id', DB::raw('SUM(man_custo_total) as total'))->pluck('total', 'man_vei_id');
        $custosAbastecimento = Abastecimento::where('aba_emp_id', $this->idEmpresa)->whereBetween('aba_data', [$startDate, $endDate])->groupBy('aba_vei_id')->select('aba_vei_id', DB::raw('SUM(aba_vlr_tot) as total'))->pluck('total', 'aba_vei_id');

        $custosCombinados = [];
        $custosManutencao->each(fn($t, $id) => $custosCombinados[$id] = ($custosCombinados[$id] ?? 0) + $t);
        $custosAbastecimento->each(fn($t, $id) => $custosCombinados[$id] = ($custosCombinados[$id] ?? 0) + $t);

        arsort($custosCombinados);
        $custosCombinados = array_slice($custosCombinados, 0, 10, true);

        $veiculoIds = array_keys($custosCombinados);
        $veiculos = Veiculo::whereIn('vei_id', $veiculoIds)->pluck('vei_placa', 'vei_id');

        $labels = [];
        $data = [];
        foreach ($custosCombinados as $id => $total) {
            $labels[] = $veiculos[$id] ?? 'ID ' . $id;
            $data[] = round($total, 2);
        }
        
        return ['labels' => $labels, 'data' => $data];
    }
}
