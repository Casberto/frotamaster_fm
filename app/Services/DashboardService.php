<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Veiculo;
use App\Models\Manutencao;
use App\Models\Abastecimento;
use App\Models\Fornecedor;
use App\Models\Servico;
use App\Models\Motorista;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardService
{
    private $idEmpresa;
    private $today;
    private $thirtyDaysFromNow;

    public function __construct()
    {
        $user = Auth::user();
        $this->idEmpresa = $user ? $user->id_empresa : null;
        $this->today = Carbon::today();
        $this->thirtyDaysFromNow = Carbon::today()->addDays(30);
    }

    public function getDashboardData()
    {
        $idEmpresa = Auth::user()->id_empresa;
        $hoje = Carbon::now()->format('Y-m-d');
        $proximos15Dias = Carbon::now()->addDays(15)->format('Y-m-d');
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();
        $inicio30Dias = Carbon::now()->subDays(30);

        // --- DADOS DE VEÍCULOS ---
        $veiculosAtivosCount = Veiculo::where('vei_emp_id', $idEmpresa)->where('vei_status', 1)->count();

        $manutencoesVencidas = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', '!=', 'Concluída')
            ->where('man_status', '!=', 'em_andamento')
            ->where('man_data_inicio', '<', $hoje)
            ->with('veiculo')
            ->orderBy('man_data_inicio', 'asc')
            ->get();

        $manutencoesEmAndamento = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', 'em_andamento')
            ->with('veiculo')
            ->orderBy('man_data_inicio', 'asc')
            ->get();

        $alertasProximos = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', 'Agendada')
            ->whereBetween('man_data_inicio', [$hoje, $proximos15Dias])
            ->with('veiculo')
            ->orderBy('man_data_inicio', 'asc')
            ->get();

        $custosManutencoes = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', 'Concluída')
            ->whereBetween('man_data_fim', [$inicioMes, $fimMes])
            ->with('veiculo')
            ->get(['man_id', 'man_vei_id', 'man_data_fim as data', 'man_custo_total as valor']);

        $custosAbastecimentos = Abastecimento::where('aba_emp_id', $idEmpresa)
            ->whereBetween('aba_data', [$inicioMes, $fimMes])
            ->with('veiculo')
            ->get(['aba_id', 'aba_vei_id', 'aba_data as data', 'aba_vlr_tot as valor']);

        $custosMensaisLista = $custosManutencoes->map(function ($item) {
            $item->tipo = 'Manutenção';
            $item->id_operacao = $item->man_id;
            return $item;
        })->concat($custosAbastecimentos->map(function ($item) {
            $item->tipo = 'Abastecimento';
            $item->id_operacao = $item->aba_id;
            return $item;
        }))->sortByDesc('data');

        $totalGastoMes = $custosMensaisLista->sum('valor');
        $countManutencoesMes = $custosManutencoes->count();
        $countAbastecimentosMes = $custosAbastecimentos->count();

        // --- TOP FORNECEDORES POR GRUPO ---
        // Definimos grupos para englobar os novos tipos detalhados
        $grupos = [
            'manutencao' => ['oficina', 'auto_eletrica', 'funilaria', 'borracharia', 'concessionaria', 'autocentro', 'loja_pecas', 'vidracaria', 'mecanica', 'ambos'],
            'combustivel' => ['posto', 'posto_gnv', 'eletroposto', 'ambos'],
            'servicos' => ['lava_rapido', 'guincho', 'vistoria', 'rastreamento', 'estacionamento'],
            'outros' => ['seguradora', 'despachante', 'concessionaria_rodovia', 'locadora', 'outro']
        ];

        $topFornecedoresPorTipo = [
            'manutencao' => $this->getTopFornecedorPorGrupo($idEmpresa, $grupos['manutencao'], $inicioMes),
            'combustivel' => $this->getTopFornecedorPorGrupo($idEmpresa, $grupos['combustivel'], $inicioMes),
            'servicos' => $this->getTopFornecedorPorGrupo($idEmpresa, $grupos['servicos'], $inicioMes),
            'outros' => $this->getTopFornecedorPorGrupo($idEmpresa, $grupos['outros'], $inicioMes),
        ];

        $rankingServicos = DB::table('manutencao_servico')
            ->join('servicos', 'manutencao_servico.ms_ser_id', '=', 'servicos.ser_id')
            ->join('manutencoes', 'manutencao_servico.ms_man_id', '=', 'manutencoes.man_id')
            ->where('manutencoes.man_emp_id', $idEmpresa)
            ->where('manutencoes.created_at', '>=', $inicio30Dias)
            ->select('servicos.ser_nome', DB::raw('count(*) as total'))
            ->groupBy('servicos.ser_id', 'servicos.ser_nome')
            ->orderByDesc('total')
            ->take(10)
            ->get();
        $servicoMaisFrequente = $rankingServicos->first();

        $custoCombustivelMes = $custosAbastecimentos->sum('valor');
        $veiculosEmpresa = Veiculo::where('vei_emp_id', $idEmpresa)->get();
        $totalKmRodadoMes = 0;

        foreach ($veiculosEmpresa as $veiculo) {
            $primeiroKm = $veiculo->abastecimentos()->whereBetween('aba_data', [$inicioMes, $fimMes])->orderBy('aba_data', 'asc')->first();
            $ultimoKm = $veiculo->abastecimentos()->whereBetween('aba_data', [$inicioMes, $fimMes])->orderBy('aba_data', 'desc')->first();

            if ($primeiroKm && $ultimoKm && $ultimoKm->aba_km > $primeiroKm->aba_km) {
                $totalKmRodadoMes += ($ultimoKm->aba_km - $primeiroKm->aba_km);
            }
        }

        $custoMedioKm = ($totalKmRodadoMes > 0) ? ($custoCombustivelMes / $totalKmRodadoMes) : 0;
        $memoriaCalculoKm = "R$ " . number_format($custoCombustivelMes, 2, ',', '.') . " (Custo Combustível Mês) / " . number_format($totalKmRodadoMes, 0, ',', '.') . " KM (KM Rodados Mês)";

        // --- DADOS DE MOTORISTAS ---
        $motoristasBaseQuery = Motorista::where('mot_emp_id', $idEmpresa);
        $motoristasAtivosCount = (clone $motoristasBaseQuery)->where('mot_status', 'Ativo')->count();
        $statusBloqueio = ['Inativo', 'Bloqueado', 'Afastado', 'Aguardando documentação', 'Suspenso', 'Em análise', 'Rejeitado'];
        $motoristasBloqueados = (clone $motoristasBaseQuery)->whereIn('mot_status', $statusBloqueio)->get();
        $motoristasCnhVencida = (clone $motoristasBaseQuery)->whereNotNull('mot_cnh_data_validade')->where('mot_cnh_data_validade', '<', $hoje)->get();
        $motoristasCnhAVencer = (clone $motoristasBaseQuery)->whereNotNull('mot_cnh_data_validade')->where('mot_cnh_data_validade', '>=', $hoje)->where('mot_cnh_data_validade', '<=', Carbon::now()->addDays(30)->format('Y-m-d'))->get();
        $novosMotoristasMes = (clone $motoristasBaseQuery)->whereBetween('created_at', [$inicioMes, $fimMes])->get();
        $motoristasEmTreinamento = (clone $motoristasBaseQuery)->where('mot_status', 'Em treinamento')->get();
        
        $frota = $this->getFleetData($inicioMes, $fimMes);

        return [
            'veiculosAtivosCount' => $veiculosAtivosCount,
            'manutencoesVencidasCount' => $manutencoesVencidas->count(),
            'manutencoesVencidasLista' => $manutencoesVencidas,
            'manutencoesEmAndamentoCount' => $manutencoesEmAndamento->count(),
            'manutencoesEmAndamentoLista' => $manutencoesEmAndamento,
            'alertasProximosCount' => $alertasProximos->count(),
            'alertasProximosLista' => $alertasProximos,
            'totalGastoMes' => $totalGastoMes,
            'custosMensaisLista' => $custosMensaisLista,
            'countManutencoesMes' => $countManutencoesMes,
            'countAbastecimentosMes' => $countAbastecimentosMes,
            'topFornecedoresPorTipo' => $topFornecedoresPorTipo,
            'servicoMaisFrequente' => $servicoMaisFrequente,
            'rankingServicos' => $rankingServicos,
            'custoMedioKm' => $custoMedioKm,
            'memoriaCalculoKm' => $memoriaCalculoKm,
            'frota' => $frota,
            'proximosLembretes' => $this->getUpcomingReminders(),
            'motoristasAtivosCount' => $motoristasAtivosCount,
            'motoristasBloqueadosCount' => $motoristasBloqueados->count(),
            'motoristasBloqueadosLista' => $motoristasBloqueados,
            'motoristasCnhVencidaCount' => $motoristasCnhVencida->count(),
            'motoristasCnhVencidaLista' => $motoristasCnhVencida,
            'motoristasCnhAVencerCount' => $motoristasCnhAVencer->count(),
            'motoristasCnhAVencerLista' => $motoristasCnhAVencer,
            'novosMotoristasMesCount' => $novosMotoristasMes->count(),
            'novosMotoristasMesLista' => $novosMotoristasMes,
            'motoristasEmTreinamentoCount' => $motoristasEmTreinamento->count(),
            'motoristasEmTreinamentoLista' => $motoristasEmTreinamento,
        ];
    }

    // Alterado para aceitar array de tipos (Grupo)
    private function getTopFornecedorPorGrupo($idEmpresa, array $tipos, $inicioMes)
    {
        if (!DB::getSchemaBuilder()->hasColumn('fornecedores', 'for_tipo')) {
            return null;
        }

        $manutencoes = DB::table('manutencoes')
            ->join('fornecedores', 'manutencoes.man_for_id', '=', 'fornecedores.for_id')
            ->where('manutencoes.man_emp_id', $idEmpresa)
            ->whereIn('fornecedores.for_tipo', $tipos) // whereIn para aceitar vários
            ->where('manutencoes.man_data_fim', '>=', $inicioMes)
            ->groupBy('man_for_id')
            ->select('man_for_id as for_id', DB::raw('COUNT(*) as total'));

        $abastecimentos = DB::table('abastecimentos')
            ->join('fornecedores', 'abastecimentos.aba_for_id', '=', 'fornecedores.for_id')
            ->where('abastecimentos.aba_emp_id', $idEmpresa)
            ->whereIn('fornecedores.for_tipo', $tipos) // whereIn para aceitar vários
            ->where('abastecimentos.aba_data', '>=', $inicioMes)
            ->groupBy('aba_for_id')
            ->select('aba_for_id as for_id', DB::raw('COUNT(*) as total'));

        $union = $manutencoes->unionAll($abastecimentos);

        $topFornecedorId = DB::table(DB::raw("({$union->toSql()}) as uses"))
            ->mergeBindings($union)
            ->groupBy('for_id')
            ->select('for_id', DB::raw('SUM(total) as uso_total'))
            ->orderByDesc('uso_total')
            ->first();

        if ($topFornecedorId) {
            $fornecedor = Fornecedor::find($topFornecedorId->for_id);
            if ($fornecedor) {
                $fornecedor->uso_total = $topFornecedorId->uso_total;
            }
            return $fornecedor;
        }
        return null;
    }

    private function getFleetData(Carbon $startOfMonth, Carbon $endOfMonth)
    {
        $frota = Veiculo::where('vei_emp_id', $this->idEmpresa)
            ->with([
                'manutencoes' => fn ($q) => $q->with(['fornecedor', 'servicos'])->orderBy('man_data_inicio', 'desc'),
                'ultimoAbastecimento'
            ])
            ->where('vei_status', 1)
            ->get();

        $startOfLastMonth = now()->subMonthNoOverflow()->startOfMonth();
        $endOfLastMonth = now()->subMonthNoOverflow()->endOfMonth();
        $start12Months = now()->subMonthsNoOverflow(11)->startOfMonth();

        $frota->each(function ($veiculo) use ($startOfMonth, $endOfMonth, $startOfLastMonth, $endOfLastMonth, $start12Months) {
            $veiculo->custo_mensal_manutencao = $veiculo->manutencoes()->where('man_status', 'Concluída')->whereBetween('man_data_fim', [$startOfMonth, $endOfMonth])->sum('man_custo_total');
            $veiculo->custo_mensal_abastecimento = $veiculo->abastecimentos()->whereBetween('aba_data', [$startOfMonth, $endOfMonth])->sum('aba_vlr_tot');
            $veiculo->custo_total_mensal = $veiculo->custo_mensal_manutencao + $veiculo->custo_mensal_abastecimento;

            $veiculo->custo_anterior_manutencao = $veiculo->manutencoes()->where('man_status', 'Concluída')->whereBetween('man_data_fim', [$startOfLastMonth, $endOfLastMonth])->sum('man_custo_total');
            $veiculo->custo_anterior_abastecimento = $veiculo->abastecimentos()->whereBetween('aba_data', [$startOfLastMonth, $endOfLastMonth])->sum('aba_vlr_tot');
            $veiculo->custo_total_anterior = $veiculo->custo_anterior_manutencao + $veiculo->custo_anterior_abastecimento;

            $totalManutencao12 = $veiculo->manutencoes()->where('man_status', 'Concluída')->where('man_data_fim', '>=', $start12Months)->sum('man_custo_total');
            $totalAbastecimento12 = $veiculo->abastecimentos()->where('aba_data', '>=', $start12Months)->sum('aba_vlr_tot');
            $veiculo->media_custo_total_12_meses = ($totalManutencao12 + $totalAbastecimento12) / 12;
        });

        return $frota;
    }

    private function getUpcomingReminders()
    {
        return Manutencao::with('veiculo')
            ->where('man_emp_id', $this->idEmpresa)
            ->where('man_status', 'agendada')
            ->where('man_data_inicio', '>=', $this->today->toDateString())
            ->orderBy('man_data_inicio', 'asc')
            ->take(5)
            ->get();
    }

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
        $custoManutencoes = Manutencao::where('man_emp_id', $this->idEmpresa)->where('man_status', 'Concluída')->whereBetween('man_data_fim', [$startDate, $endDate])->sum('man_custo_total');
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
            $date = now()->subMonthsNoOverflow($i);
            $labels[] = $date->format('M/y');
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $totalManutencoes = Manutencao::where('man_emp_id', $this->idEmpresa)->where('man_status', 'Concluída')->whereBetween('man_data_fim', [$start, $end])->sum('man_custo_total');
            $totalAbastecimentos = Abastecimento::where('aba_emp_id', $this->idEmpresa)->whereBetween('aba_data', [$start, $end])->sum('aba_vlr_tot');

            $data[] = round($totalManutencoes + $totalAbastecimentos, 2);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getCostByVehicle(Carbon $startDate, Carbon $endDate): array
    {
        $custosManutencao = Manutencao::where('man_emp_id', $this->idEmpresa)->where('man_status', 'Concluída')->whereBetween('man_data_fim', [$startDate, $endDate])->groupBy('man_vei_id')->select('man_vei_id', DB::raw('SUM(man_custo_total) as total'))->pluck('total', 'man_vei_id');
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
            if (isset($veiculos[$id])) {
                $labels[] = $veiculos[$id];
                $data[] = round($total, 2);
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }
}