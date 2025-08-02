<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Veiculo;
use App\Models\Manutencao; // Certifique-se de que o modelo Manutencao está importado
use App\Models\Abastecimento; // Certifique-se de que o modelo Abastecimento está importado
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $id_empresa = $user->id_empresa;

        if (!$id_empresa) {
            return view('dashboard');
        }

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $veiculosAtivos = Veiculo::where('id_empresa', $id_empresa)->where('status', 'ativo')->count();

        $manutencoesVencidas = Manutencao::where('id_empresa', $id_empresa)
            ->where('data_manutencao', '<', now())
            ->where('status', '!=', 'concluida')
            ->count();
            
        $alertasProximos = Manutencao::where('id_empresa', $id_empresa)
            ->whereBetween('data_manutencao', [now(), now()->addDays(7)])
            ->where('status', '!=', 'concluida')
            ->count();

        $frota = Veiculo::where('id_empresa', $id_empresa)
            ->where('status', 'ativo')
            ->with(['ultimoAbastecimento']) // essa não precisa de filtro
            ->get();

        $custoTotalMes = 0;

        foreach ($frota as $veiculo) {
            // Somar abastecimentos do mês atual
            $custoAbastecimento = Abastecimento::where('id_veiculo', $veiculo->id)
                ->whereBetween('data_abastecimento', [$startOfMonth, $endOfMonth])
                ->sum('custo_total');

            // Somar manutenções do mês atual
            $custoManutencao = Manutencao::where('id_veiculo', $veiculo->id)
                ->whereBetween('data_manutencao', [$startOfMonth, $endOfMonth])
                ->sum('custo_total');

            $veiculo->custo_mensal_abastecimento = $custoAbastecimento;
            $veiculo->custo_mensal_manutencao = $custoManutencao;
            $veiculo->custo_total_mensal = $custoAbastecimento + $custoManutencao;

            $custoTotalMes += $veiculo->custo_total_mensal;
        }

        $proximosLembretes = Manutencao::where('id_empresa', $id_empresa)
            ->where('data_manutencao', '>', now())
            ->where('data_manutencao', '<=', now()->addDays(30))
            ->where('status', '!=', 'concluida')
            ->with('veiculo')
            ->orderBy('data_manutencao', 'asc')
            ->take(5)
            ->get();

        return view('dashboard', [
            'veiculosAtivos' => $veiculosAtivos,
            'manutencoesVencidas' => $manutencoesVencidas,
            'alertasProximos' => $alertasProximos,
            'custoMensal' => $custoTotalMes,
            'frota' => $frota,
            'proximosLembretes' => $proximosLembretes,
        ]);
    }

}
