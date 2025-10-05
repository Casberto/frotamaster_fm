<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Veiculo;
use App\Models\Manutencao;
use App\Models\Abastecimento;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $idEmpresa = Auth::user()->id_empresa;
        if (!$idEmpresa) {
            // Retorna uma view simples para usuários sem empresa (como super-admin na home)
            return view('dashboard');
        }

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

        $custoMensal = Manutencao::where('man_emp_id', $idEmpresa)
            ->whereBetween('man_data_inicio', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('man_custo_total');

        // --- Frota de Veículos com dados agregados ---
        $frota = Veiculo::where('vei_emp_id', $idEmpresa)
            ->with(['manutencoes.servicos']) // Carrega manutenções e os serviços de cada uma
            ->where('vei_status', 1)
            ->get();

        // Calcula os custos mensais para cada veículo
        $frota->each(function ($veiculo) {
            $veiculo->custo_mensal_manutencao = $veiculo->manutencoes()
                ->whereBetween('man_data_inicio', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('man_custo_total');
            
            // Simulação de custo de abastecimento (módulo a ser implementado com novos campos)
            // $veiculo->custo_mensal_abastecimento = $veiculo->abastecimentos()->...
            $veiculo->custo_mensal_abastecimento = 0; // Placeholder

            $veiculo->custo_total_mensal = $veiculo->custo_mensal_manutencao + $veiculo->custo_mensal_abastecimento;
        });


        // --- Próximos Lembretes ---
        $proximosLembretes = Manutencao::with('veiculo.empresa') // Carrega o veículo e a empresa para evitar N+1
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
            'custoMensal',
            'frota',
            'proximosLembretes'
        ));
    }
}

