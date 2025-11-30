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
use App\Models\Reserva;
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

    public function getExecutiveDashboardData()
    {
        $idEmpresa = $this->idEmpresa;
        $hoje = Carbon::now()->format('Y-m-d');
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();
        $inicio30Dias = Carbon::now()->subDays(30);

        // 1.1 Indicadores Gerais
        $veiculosAtivosCount = Veiculo::where('vei_emp_id', $idEmpresa)->where('vei_status', 1)->count();

        $totalVeiculosCount = Veiculo::where('vei_emp_id', $idEmpresa)->count();
        
        $motoristasAtivosCount = Motorista::where('mot_emp_id', $idEmpresa)->where('mot_status', 'Ativo')->count();
        
        $manutencoesVencidasCount = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', '!=', 'Concluída')
            ->where('man_status', '!=', 'em_andamento')
            ->where('man_data_inicio', '<', $hoje)
            ->count();

        $manutencoesEmAndamentoCount = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', 'em_andamento')
            ->count();

        $abastecimentosMesCount = Abastecimento::where('aba_emp_id', $idEmpresa)
            ->whereBetween('aba_data', [$inicioMes, $fimMes])
            ->count();

        $alertasProximosCount = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', 'Agendada')
            ->whereBetween('man_data_inicio', [$hoje, Carbon::now()->addDays(15)])
            ->count();

        $custoManutencaoMes = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', 'Concluída')
            ->whereBetween('man_data_fim', [$inicioMes, $fimMes])
            ->sum('man_custo_total');

        $custoAbastecimentoMes = Abastecimento::where('aba_emp_id', $idEmpresa)
            ->whereBetween('aba_data', [$inicioMes, $fimMes])
            ->sum('aba_vlr_tot');

        $custoTotalMes = $custoManutencaoMes + $custoAbastecimentoMes;
        $custoMedioPorVeiculo = $veiculosAtivosCount > 0 ? $custoTotalMes / $veiculosAtivosCount : 0;

        // 1.2 Gráficos Essenciais
        $evolucaoCustos = $this->getCostEvolution();
        $composicaoCustos = [
            'labels' => ['Manutenção', 'Combustível'],
            'data' => [$custoManutencaoMes, $custoAbastecimentoMes]
        ];
        
        // Uso da frota (simplificado por enquanto, total de km no mês)
        // Para pegar o veículo mais usado, precisaríamos iterar como no getFleetData, mas vamos otimizar
        // Vamos pegar os abastecimentos do mês para estimar KM
        $veiculoMaisUsado = $this->getMostUsedVehicle($inicioMes, $fimMes);

        $manutencoesPorCategoria = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', 'Concluída')
            ->whereBetween('man_data_fim', [$inicio30Dias, Carbon::now()])
            ->select('man_tipo', DB::raw('count(*) as total'))
            ->groupBy('man_tipo')
            ->pluck('total', 'man_tipo');

        // 1.3 Resumo Operacional
        $veiculosComProblemas = $this->getVehiclesWithProblems();
        $proximasManutencoes = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', 'Agendada')
            ->where('man_data_inicio', '>=', $hoje)
            ->with('veiculo')
            ->orderBy('man_data_inicio', 'asc')
            ->take(5)
            ->get();
        
        $abastecimentosRecentes = Abastecimento::where('aba_emp_id', $idEmpresa)
            ->with('veiculo')
            ->orderBy('aba_data', 'desc')
            ->take(5)
            ->get();

        $motoristasPendencias = $this->getDriversWithIssues();

        // 1.4 Destaques
        $veiculosMaisCaros = $this->getMostExpensiveVehicles($inicioMes, $fimMes);
        
        return [
            'indicadores' => [
                'veiculos_ativos' => $veiculosAtivosCount,
                'veiculos_total' => $totalVeiculosCount,
                'motoristas_ativos' => $motoristasAtivosCount,
                'manutencoes_vencidas' => $manutencoesVencidasCount,
                'manutencoes_andamento' => $manutencoesEmAndamentoCount,
                'abastecimentos_mes' => $abastecimentosMesCount,
                'alertas_proximos' => $alertasProximosCount,
                'custo_total_mes' => $custoTotalMes,
                'custo_medio_veiculo' => $custoMedioPorVeiculo
            ],
            'graficos' => [
                'evolucao_custos' => $evolucaoCustos,
                'composicao_custos' => $composicaoCustos,
                'manutencoes_categoria' => $manutencoesPorCategoria,
            ],
            'operacional' => [
                'veiculos_problemas' => $veiculosComProblemas,
                'proximas_manutencoes' => $proximasManutencoes,
                'abastecimentos_recentes' => $abastecimentosRecentes,
                'motoristas_pendencias' => $motoristasPendencias,
                'veiculo_mais_usado' => $veiculoMaisUsado
            ],
            'destaques' => [
                'veiculos_mais_caros' => $veiculosMaisCaros
            ]
        ];
    }

    private function getMostUsedVehicle($startDate, $endDate)
    {
        $veiculos = Veiculo::where('vei_emp_id', $this->idEmpresa)->where('vei_status', 1)->get();
        $maxKm = 0;
        $topVeiculo = null;

        foreach ($veiculos as $veiculo) {
            $primeiro = $veiculo->abastecimentos()->whereBetween('aba_data', [$startDate, $endDate])->orderBy('aba_km', 'asc')->first();
            $ultimo = $veiculo->abastecimentos()->whereBetween('aba_data', [$startDate, $endDate])->orderBy('aba_km', 'desc')->first();

            if ($primeiro && $ultimo && $ultimo->aba_km > $primeiro->aba_km) {
                $kmRodado = $ultimo->aba_km - $primeiro->aba_km;
                if ($kmRodado > $maxKm) {
                    $maxKm = $kmRodado;
                    $topVeiculo = $veiculo;
                }
            }
        }

        return $topVeiculo ? ['veiculo' => $topVeiculo, 'km' => $maxKm] : null;
    }

    private function getVehiclesWithProblems()
    {
        $vencidas = Manutencao::where('man_emp_id', $this->idEmpresa)
            ->where('man_status', '!=', 'Concluída')
            ->where('man_status', '!=', 'em_andamento')
            ->where('man_data_inicio', '<', Carbon::today())
            ->pluck('man_vei_id')
            ->unique();
            
        return Veiculo::whereIn('vei_id', $vencidas)->get();
    }

    private function getDriversWithIssues()
    {
        $hoje = Carbon::today();
        return Motorista::where('mot_emp_id', $this->idEmpresa)
            ->where(function($q) use ($hoje) {
                $q->where('mot_cnh_data_validade', '<', $hoje)
                  ->orWhere('mot_status', 'Bloqueado')
                  ->orWhere('mot_status', 'Suspenso');
            })
            ->get();
    }

    public function getVehicleDetails($veiculoId)
    {
        $veiculo = Veiculo::where('vei_emp_id', $this->idEmpresa)
            ->where('vei_id', $veiculoId)
            ->with(['manutencoes.fornecedor', 'manutencoes.servicos', 'abastecimentos.fornecedor', 'documentos'])
            ->firstOrFail();

        // KPIs
        $totalGasto = $veiculo->manutencoes->sum('man_custo_total') + $veiculo->abastecimentos->sum('aba_vlr_tot');
        
        $abastecimentosValidos = $veiculo->abastecimentos->where('aba_km', '>', 0)->where('aba_qtd', '>', 0);
        $mediaConsumo = $abastecimentosValidos->count() > 0 
            ? $abastecimentosValidos->avg(fn($a) => $a->aba_km / $a->aba_qtd) 
            : 0;
            
        $ultimoAbastecimento = $veiculo->ultimoAbastecimento;
        $ultimaManutencao = $veiculo->manutencoes()->where('man_status', 'Concluída')->latest('man_data_fim')->first();

        // Gráficos
        $graficoCustos = $this->getVehicleMonthlyCosts($veiculo);
        $graficoConsumo = $this->getVehicleConsumptionEvolution($veiculo);
        $graficoManutencao = $this->getVehicleMaintenanceTypes($veiculo);
        $graficoKm = $this->getVehicleMileageEvolution($veiculo);

        // Histórico Unificado
        $historico = collect();
        foreach ($veiculo->manutencoes as $man) {
            $historico->push([
                'tipo' => 'manutencao',
                'data' => $man->man_data_inicio,
                'descricao' => $man->servicos->pluck('ser_nome')->join(', '),
                'fornecedor' => $man->fornecedor->for_nome_fantasia ?? 'N/A',
                'valor' => $man->man_custo_total,
                'status' => $man->man_status
            ]);
        }
        foreach ($veiculo->abastecimentos as $abs) {
            $historico->push([
                'tipo' => 'abastecimento',
                'data' => $abs->aba_data,
                'descricao' => 'Abastecimento - ' . $abs->aba_tipo_combustivel,
                'fornecedor' => $abs->fornecedor->for_nome_fantasia ?? 'N/A',
                'valor' => $abs->aba_vlr_tot,
                'km' => $abs->aba_km,
                'litros' => $abs->aba_qtd
            ]);
        }
        $historico = $historico->sortByDesc('data')->values()->take(50);

        // Alertas
        $alertas = Manutencao::where('man_vei_id', $veiculoId)
            ->where('man_status', 'Agendada')
            ->where('man_data_inicio', '>=', now())
            ->orderBy('man_data_inicio')
            ->get();

        return [
            'veiculo' => $veiculo,
            'kpis' => [
                'total_gasto' => $totalGasto,
                'media_consumo' => 0, 
                'ultima_manutencao' => $ultimaManutencao ? $ultimaManutencao->man_data_fim : null,
                'ultimo_abastecimento' => $ultimoAbastecimento ? $ultimoAbastecimento->aba_data : null,
            ],
            'charts' => [
                'custos' => $graficoCustos,
                'consumo' => $graficoConsumo,
                'manutencao_tipo' => $graficoManutencao,
                'km_mes' => $graficoKm
            ],
            'historico' => $historico,
            'alertas' => $alertas,
            'documentos' => $veiculo->documentos
        ];
    }

    private function getVehicleMonthlyCosts($veiculo)
    {
        $labels = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonthsNoOverflow($i);
            $labels[] = $date->format('M/y');
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $man = $veiculo->manutencoes()->where('man_status', 'Concluída')->whereBetween('man_data_fim', [$start, $end])->sum('man_custo_total');
            $abs = $veiculo->abastecimentos()->whereBetween('aba_data', [$start, $end])->sum('aba_vlr_tot');
            
            $data[] = round($man + $abs, 2);
        }
        return ['labels' => $labels, 'data' => $data];
    }

    private function getVehicleConsumptionEvolution($veiculo)
    {
        $abastecimentos = $veiculo->abastecimentos()
            ->orderBy('aba_data', 'desc')
            ->take(6)
            ->get()
            ->reverse();

        return ['labels' => [], 'data' => []]; 
    }

    private function getVehicleMaintenanceTypes($veiculo)
    {
        $types = $veiculo->manutencoes()
            ->where('man_status', 'Concluída')
            ->select('man_tipo', DB::raw('count(*) as total'))
            ->groupBy('man_tipo')
            ->pluck('total', 'man_tipo');
            
        return ['labels' => $types->keys(), 'data' => $types->values()];
    }

    private function getVehicleMileageEvolution($veiculo)
    {
        $labels = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonthsNoOverflow($i);
            $labels[] = $date->format('M/y');
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $maxKm = $veiculo->abastecimentos()->whereBetween('aba_data', [$start, $end])->max('aba_km');
            
            $data[] = $maxKm ?? 0; 
        }
        return ['labels' => $labels, 'data' => $data];
    }

    private function getMostExpensiveVehicles($startDate, $endDate)
    {
        $veiculos = Veiculo::where('vei_emp_id', $this->idEmpresa)->where('vei_status', 1)->get();
        $ranking = [];

        foreach ($veiculos as $veiculo) {
            $custoMan = $veiculo->manutencoes()->where('man_status', 'Concluída')->whereBetween('man_data_fim', [$startDate, $endDate])->sum('man_custo_total');
            $custoAbs = $veiculo->abastecimentos()->whereBetween('aba_data', [$startDate, $endDate])->sum('aba_vlr_tot');
            $total = $custoMan + $custoAbs;
            
            if ($total > 0) {
                $ranking[] = [
                    'veiculo' => $veiculo,
                    'valor' => $total
                ];
            }
        }

        usort($ranking, function($a, $b) {
            return $b['valor'] <=> $a['valor'];
        });

        return array_slice($ranking, 0, 5);
    }
    public function getMaintenanceDashboardData()
    {
        $idEmpresa = $this->idEmpresa;
        $hoje = Carbon::now()->format('Y-m-d');
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();
        $start12Months = Carbon::now()->subMonths(12)->startOfMonth();

        // 3.1 Cards de Status
        $vencidasCount = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', '!=', 'Concluída')
            ->where('man_status', '!=', 'em_andamento')
            ->where('man_data_inicio', '<', $hoje)
            ->count();

        $emAndamentoCount = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', 'em_andamento')
            ->count();

        $previstasCount = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', 'Agendada')
            ->where('man_data_inicio', '>=', $hoje)
            ->count();

        $porTipo = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', '!=', 'Cancelada') // Considerar todas ativas? Ou só pendentes? Vamos pegar geral por enquanto ou do mês?
            // O pedido diz "Por tipo", geralmente é um snapshot atual ou do mês. Vamos pegar do mês atual para ser relevante.
            ->whereBetween('man_data_inicio', [$inicioMes, $fimMes])
            ->select('man_tipo', DB::raw('count(*) as total'))
            ->groupBy('man_tipo')
            ->pluck('total', 'man_tipo');

        // 3.2 Lista de Manutenções (Inicial - últimos 50 registros ou do mês atual)
        // Vamos pegar as mais recentes para a listagem inicial
        $listaManutencoes = Manutencao::where('man_emp_id', $idEmpresa)
            ->with(['veiculo', 'fornecedor'])
            ->orderBy('man_data_inicio', 'desc')
            ->take(50)
            ->get();

        // 3.3 Dashboard de Manutenções (Gráficos)
        
        // Gráfico de custos por mês (últimos 12 meses)
        $custosPorMes = $this->getMaintenanceCostsPerMonth($start12Months);

        // Gráfico de tipos mais comuns (últimos 12 meses para ter massa de dados)
        $tiposMaisComuns = Manutencao::where('man_emp_id', $idEmpresa)
            ->where('man_status', 'Concluída')
            ->where('man_data_fim', '>=', $start12Months)
            ->select('man_tipo', DB::raw('count(*) as total'))
            ->groupBy('man_tipo')
            ->orderByDesc('total')
            ->take(5)
            ->pluck('total', 'man_tipo');

        // Fornecedores mais utilizados (top 5 por valor gasto nos últimos 12 meses)
        $topFornecedores = DB::table('manutencoes')
            ->join('fornecedores', 'manutencoes.man_for_id', '=', 'fornecedores.for_id')
            ->where('manutencoes.man_emp_id', $idEmpresa)
            ->where('manutencoes.man_status', 'Concluída')
            ->where('manutencoes.man_data_fim', '>=', $start12Months)
            ->select('fornecedores.for_nome_fantasia', DB::raw('SUM(manutencoes.man_custo_total) as total_gasto'))
            ->groupBy('fornecedores.for_id', 'fornecedores.for_nome_fantasia')
            ->orderByDesc('total_gasto')
            ->take(5)
            ->get();

        // Tempo médio para conclusão (em dias, últimos 12 meses)
        // Considerando data_fim - data_inicio
        $tempoMedio = DB::table('manutencoes')
            ->where('man_emp_id', $idEmpresa)
            ->where('man_status', 'Concluída')
            ->where('man_data_fim', '>=', $start12Months)
            ->select(DB::raw('AVG(DATEDIFF(man_data_fim, man_data_inicio)) as media_dias'))
            ->first();

        return [
            'cards' => [
                'vencidas' => $vencidasCount,
                'em_andamento' => $emAndamentoCount,
                'previstas' => $previstasCount,
                'por_tipo' => $porTipo
            ],
            'lista' => $listaManutencoes,
            'graficos' => [
                'custos_mensais' => $custosPorMes,
                'tipos_comuns' => $tiposMaisComuns,
                'top_fornecedores' => $topFornecedores,
                'tempo_medio' => $tempoMedio ? round($tempoMedio->media_dias, 1) : 0
            ]
        ];
    }

    private function getMaintenanceCostsPerMonth($startDate)
    {
        $labels = [];
        $data = [];
        
        // Loop pelos últimos 12 meses
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonthsNoOverflow($i);
            $labels[] = $date->format('M/y');
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $total = Manutencao::where('man_emp_id', $this->idEmpresa)
                ->where('man_status', 'Concluída')
                ->whereBetween('man_data_fim', [$start, $end])
                ->sum('man_custo_total');

            $data[] = round($total, 2);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function getMaintenanceDetails($id)
    {
        return Manutencao::where('man_emp_id', $this->idEmpresa)
            ->where('man_id', $id)
            ->with(['veiculo', 'fornecedor', 'servicos', 'user'])
            ->firstOrFail();
    }

    public function getFuelingDashboardData()
    {
        $idEmpresa = $this->idEmpresa;
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();
        $start12Months = Carbon::now()->subMonths(12)->startOfMonth();

        // 4.1 - Indicadores
        
        // Custo total do mês
        $custoTotalMes = Abastecimento::where('aba_emp_id', $idEmpresa)
            ->whereBetween('aba_data', [$inicioMes, $fimMes])
            ->sum('aba_vlr_tot');

        // Quantidade total abastecida (litros)
        $quantidadeAbastecida = Abastecimento::where('aba_emp_id', $idEmpresa)
            ->whereBetween('aba_data', [$inicioMes, $fimMes])
            ->sum('aba_qtd');

        // Média geral (km/L) da frota
        $mediaGeralConsumo = $this->calcularMediaGeralConsumo($inicioMes, $fimMes);

        // Veículo mais gastador do mês
        $veiculoMaisGastador = $this->getVeiculoMaisGastador($inicioMes, $fimMes);

        // 4.2 - Lista de Abastecimentos
        $listaAbastecimentos = Abastecimento::where('aba_emp_id', $idEmpresa)
            ->with(['veiculo', 'fornecedor', 'reservas.motorista'])
            ->orderBy('aba_data', 'desc')
            ->take(50)
            ->get();

        // 4.3 - Gráficos
        
        // Evolução de consumo por mês (últimos 12 meses)
        $evolucaoConsumo = $this->getEvolucaoConsumoPorMes($start12Months);

        // Custo por tipo de combustível (últimos 12 meses)
        $custoPorCombustivel = Abastecimento::where('aba_emp_id', $idEmpresa)
            ->where('aba_data', '>=', $start12Months)
            ->select('aba_combustivel', DB::raw('SUM(aba_vlr_tot) as total_custo'))
            ->groupBy('aba_combustivel')
            ->get()
            ->mapWithKeys(function ($item) {
                $tipo = match($item->aba_combustivel) {
                    1 => 'Gasolina',
                    2 => 'Etanol',
                    3 => 'Diesel',
                    4 => 'GNV',
                    5 => 'Elétrico',
                    default => 'Outro'
                };
                return [$tipo => round($item->total_custo, 2)];
            });

        // Rankings de eficiência
        $rankingEficienciaMotoristas = $this->getRankingEficienciaMotoristas($start12Months);
        $rankingEficienciaVeiculos = $this->getRankingEficienciaVeiculos($start12Months);

        return [
            'indicadores' => [
                'custo_total_mes' => $custoTotalMes,
                'quantidade_abastecida' => $quantidadeAbastecida,
                'media_geral_consumo' => $mediaGeralConsumo,
                'veiculo_mais_gastador' => $veiculoMaisGastador
            ],
            'lista' => $listaAbastecimentos,
            'graficos' => [
                'evolucao_consumo' => $evolucaoConsumo,
                'custo_combustivel' => $custoPorCombustivel,
                'ranking_motoristas' => $rankingEficienciaMotoristas,
                'ranking_veiculos' => $rankingEficienciaVeiculos
            ]
        ];
    }

    private function calcularMediaGeralConsumo($startDate, $endDate)
    {
        $veiculos = Veiculo::where('vei_emp_id', $this->idEmpresa)->where('vei_status', 1)->get();
        $totalKm = 0;
        $totalLitros = 0;

        foreach ($veiculos as $veiculo) {
            $abastecimentos = $veiculo->abastecimentos()
                ->whereBetween('aba_data', [$startDate, $endDate])
                ->orderBy('aba_km', 'asc')
                ->get();

            if ($abastecimentos->count() >= 2) {
                $primeiroKm = $abastecimentos->first()->aba_km;
                $ultimoKm = $abastecimentos->last()->aba_km;
                $kmRodados = $ultimoKm - $primeiroKm;

                if ($kmRodados > 0) {
                    $litrosConsumidos = $abastecimentos->sum('aba_qtd');
                    $totalKm += $kmRodados;
                    $totalLitros += $litrosConsumidos;
                }
            }
        }

        return ($totalLitros > 0) ? round($totalKm / $totalLitros, 2) : 0;
    }

    private function getVeiculoMaisGastador($startDate, $endDate)
    {
        $gastosPorVeiculo = Abastecimento::where('aba_emp_id', $this->idEmpresa)
            ->whereBetween('aba_data', [$startDate, $endDate])
            ->select('aba_vei_id', DB::raw('SUM(aba_vlr_tot) as total_gasto'))
            ->groupBy('aba_vei_id')
            ->orderByDesc('total_gasto')
            ->first();

        if ($gastosPorVeiculo) {
            $veiculo = Veiculo::find($gastosPorVeiculo->aba_vei_id);
            return [
                'veiculo' => $veiculo,
                'valor' => $gastosPorVeiculo->total_gasto
            ];
        }

        return null;
    }

    private function getEvolucaoConsumoPorMes($startDate)
    {
        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonthsNoOverflow($i);
            $labels[] = $date->format('M/y');
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $consumoMedio = $this->calcularMediaGeralConsumo($start, $end);
            $data[] = $consumoMedio;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getRankingEficienciaMotoristas($startDate)
    {
        // Buscar abastecimentos com motorista via reservas
        $motoristasConsumo = DB::table('abastecimentos')
            ->join('reserva_abastecimentos', 'abastecimentos.aba_id', '=', 'reserva_abastecimentos.rab_abs_id')
            ->join('motoristas', 'reserva_abastecimentos.rab_mot_id', '=', 'motoristas.mot_id')
            ->where('abastecimentos.aba_emp_id', $this->idEmpresa)
            ->where('abastecimentos.aba_data', '>=', $startDate)
            ->select(
                'motoristas.mot_id',
                'motoristas.mot_nome',
                DB::raw('SUM(abastecimentos.aba_qtd) as total_litros')
            )
            ->groupBy('motoristas.mot_id', 'motoristas.mot_nome')
            ->get();

        $ranking = [];

        foreach ($motoristasConsumo as $motoristaData) {
            // Calcular km rodados pelo motorista
            $abastecimentos = DB::table('abastecimentos')
                ->join('reserva_abastecimentos', 'abastecimentos.aba_id', '=', 'reserva_abastecimentos.rab_abs_id')
                ->where('reserva_abastecimentos.rab_mot_id', $motoristaData->mot_id)
                ->where('abastecimentos.aba_data', '>=', $startDate)
                ->orderBy('abastecimentos.aba_km', 'asc')
                ->pluck('aba_km');

            if ($abastecimentos->count() >= 2) {
                $kmRodados = $abastecimentos->last() - $abastecimentos->first();
                if ($kmRodados > 0 && $motoristaData->total_litros > 0) {
                    $mediaKmL = $kmRodados / $motoristaData->total_litros;
                    $ranking[] = [
                        'nome' => $motoristaData->mot_nome,
                        'media' => round($mediaKmL, 2)
                    ];
                }
            }
        }

        // Ordenar por média decrescente e pegar top 10
        usort($ranking, fn($a, $b) => $b['media'] <=> $a['media']);
        return array_slice($ranking, 0, 10);
    }

    private function getRankingEficienciaVeiculos($startDate)
    {
        $veiculos = Veiculo::where('vei_emp_id', $this->idEmpresa)->where('vei_status', 1)->get();
        $ranking = [];

        foreach ($veiculos as $veiculo) {
            $abastecimentos = $veiculo->abastecimentos()
                ->where('aba_data', '>=', $startDate)
                ->orderBy('aba_km', 'asc')
                ->get();

            if ($abastecimentos->count() >= 2) {
                $primeiroKm = $abastecimentos->first()->aba_km;
                $ultimoKm = $abastecimentos->last()->aba_km;
                $kmRodados = $ultimoKm - $primeiroKm;
                $litrosConsumidos = $abastecimentos->sum('aba_qtd');

                if ($kmRodados > 0 && $litrosConsumidos > 0) {
                    $mediaKmL = $kmRodados / $litrosConsumidos;
                    $ranking[] = [
                        'placa' => $veiculo->vei_placa,
                        'modelo' => $veiculo->vei_modelo,
                        'media' => round($mediaKmL, 2)
                    ];
                }
            }
        }

        // Ordenar por média decrescente e pegar top 10
        usort($ranking, fn($a, $b) => $b['media'] <=> $a['media']);
        return array_slice($ranking, 0, 10);
    }

    public function getFuelingDetails($id)
    {
        return Abastecimento::where('aba_emp_id', $this->idEmpresa)
            ->where('aba_id', $id)
            ->with(['veiculo', 'fornecedor', 'reservas.motorista', 'user'])
            ->firstOrFail();
    }

    public function getReservationsDashboardData()
    {
        $idEmpresa = $this->idEmpresa;
        $hoje = Carbon::now()->format('Y-m-d');
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();
        $start12Months = Carbon::now()->subMonths(12)->startOfMonth();
        $proximos30Dias = Carbon::now()->addDays(30);

        // 5.1 - Cards de Status
        
        // Reservas Ativas (aprovada + em_uso)
        $ativasCount = Reserva::where('res_emp_id', $idEmpresa)
            ->whereIn('res_status', ['aprovada', 'em_uso'])
            ->count();

        // Reservas Concluídas no mês
        $concluidasCount = Reserva::where('res_emp_id', $idEmpresa)
            ->where('res_status', 'encerrada')
            ->whereBetween('res_data_fim', [$inicioMes, $fimMes])
            ->count();

        // Reservas Canceladas no mês
        $canceladasCount = Reserva::where('res_emp_id', $idEmpresa)
            ->whereIn('res_status', ['cancelada', 'rejeitada'])
            ->whereBetween('created_at', [$inicioMes, $fimMes])
            ->count();

        // Reservas Pendentes (pendente + em_revisao)
        $pendentesCount = Reserva::where('res_emp_id', $idEmpresa)
            ->whereIn('res_status', ['pendente', 'em_revisao'])
            ->count();

        // 5.2 - Dados para Calendário (próximos 30 dias)
        $reservasCalendario = Reserva::where('res_emp_id', $idEmpresa)
            ->where('res_data_inicio', '>=', $hoje)
            ->where('res_data_inicio', '<=', $proximos30Dias)
            ->whereIn('res_status', ['pendente', 'aprovada', 'em_uso'])
            ->with(['veiculo', 'motorista'])
            ->orderBy('res_data_inicio', 'asc')
            ->get();

        // 5.3 - Lista de Reservas (últimas 50)
        $listaReservas = Reserva::where('res_emp_id', $idEmpresa)
            ->with(['veiculo', 'motorista', 'solicitante'])
            ->orderBy('res_data_inicio', 'desc')
            ->take(50)
            ->get();

        // 5.4 - Gráficos

        // Evolução de reservas por mês (últimos 12 meses)
        $evolucaoReservas = $this->getEvolucaoReservasPorMes($start12Months);

        // Reservas por tipo
        $reservasPorTipo = Reserva::where('res_emp_id', $idEmpresa)
            ->where('created_at', '>=', $start12Months)
            ->select('res_tipo', DB::raw('count(*) as total'))
            ->groupBy('res_tipo')
            ->get()
            ->mapWithKeys(function ($item) {
                $tipo = match($item->res_tipo) {
                    'viagem' => 'Viagem',
                    'manutencao' => 'Manutenção',
                    'evento' => 'Evento',
                    'outros' => 'Outros',
                    default => ucfirst($item->res_tipo ?? 'Não especificado')
                };
                return [$tipo => $item->total];
            });

        // KM Previsto vs Real (últimos 6 meses com reservas concluídas)
        $kmPrevistoVsReal = $this->getKmPrevistoVsReal();

        // Top 10 Veículos Mais Reservados
        $veiculosMaisReservados = DB::table('reservas')
            ->join('veiculos', 'reservas.res_vei_id', '=', 'veiculos.vei_id')
            ->where('reservas.res_emp_id', $idEmpresa)
            ->where('reservas.created_at', '>=', $start12Months)
            ->select('veiculos.vei_placa', DB::raw('count(*) as total_reservas'))
            ->groupBy('veiculos.vei_id', 'veiculos.vei_placa')
            ->orderByDesc('total_reservas')
            ->take(10)
            ->get();

        // Top 10 Motoristas com Mais Reservas
        $motoristasMaisReservas = DB::table('reservas')
            ->join('motoristas', 'reservas.res_mot_id', '=', 'motoristas.mot_id')
            ->where('reservas.res_emp_id', $idEmpresa)
            ->where('reservas.created_at', '>=', $start12Months)
            ->whereNotNull('reservas.res_mot_id')
            ->select('motoristas.mot_nome', DB::raw('count(*) as total_reservas'))
            ->groupBy('motoristas.mot_id', 'motoristas.mot_nome')
            ->orderByDesc('total_reservas')
            ->take(10)
            ->get();

        return [
            'cards' => [
                'ativas' => $ativasCount,
                'concluidas' => $concluidasCount,
                'canceladas' => $canceladasCount,
                'pendentes' => $pendentesCount
            ],
            'calendario' => $reservasCalendario,
            'lista' => $listaReservas,
            'graficos' => [
                'evolucao_reservas' => $evolucaoReservas,
                'por_tipo' => $reservasPorTipo,
                'km_previsto_vs_real' => $kmPrevistoVsReal,
                'veiculos_mais_reservados' => $veiculosMaisReservados,
                'motoristas_mais_reservas' => $motoristasMaisReservas
            ]
        ];
    }

    private function getEvolucaoReservasPorMes($startDate)
    {
        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonthsNoOverflow($i);
            $labels[] = $date->format('M/y');
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $total = Reserva::where('res_emp_id', $this->idEmpresa)
                ->whereBetween('res_data_inicio', [$start, $end])
                ->count();

            $data[] = $total;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getKmPrevistoVsReal()
    {
        $labels = [];
        $kmPrevisto = [];
        $kmReal = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonthsNoOverflow($i);
            $labels[] = $date->format('M/y');
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $reservas = Reserva::where('res_emp_id', $this->idEmpresa)
                ->where('res_status', 'encerrada')
                ->whereBetween('res_data_fim', [$start, $end])
                ->get();

            $totalPrevisto = 0;
            $totalReal = 0;

            foreach ($reservas as $reserva) {
                // KM previsto pode ser estimado pela diferença de origem/destino
                // ou por um campo específico se houver
                // Por enquanto, vamos usar a diferença entre km_inicio e km_fim como "previsto esperado"
                
                if ($reserva->res_km_fim && $reserva->res_km_inicio) {
                    $totalReal += ($reserva->res_km_fim - $reserva->res_km_inicio);
                    // Assumindo que o "previsto" seria um valor médio ou estimado
                    // Como não temos campo específico, vamos usar o real como base
                    $totalPrevisto += ($reserva->res_km_fim - $reserva->res_km_inicio);
                }
            }

            $kmPrevisto[] = round($totalPrevisto, 0);
            $kmReal[] = round($totalReal, 0);
        }

        return [
            'labels' => $labels,
            'previsto' => $kmPrevisto,
            'real' => $kmReal
        ];
    }

    public function getReservationDetails($id)
    {
        return Reserva::where('res_emp_id', $this->idEmpresa)
            ->where('res_id', $id)
            ->with([
                'veiculo',
                'motorista',
                'solicitante',
                'revisor',
                'fornecedor',
                'abastecimentos.fornecedor',
                'manutencoes.fornecedor',
                'passageiros',
                'pedagios',
                'auditLogs.user'
            ])
            ->firstOrFail();
    }
}
