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
        $user = Auth::user();
        $id_empresa = $user->id_empresa;

        if (!$id_empresa) {
            return view('dashboard');
        }

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // --- CORREÇÃO 1: Usar 'vei_status' com valor numérico (1 = Ativo) ---
        $veiculosAtivos = Veiculo::where('vei_emp_id', $id_empresa)->where('vei_status', 1)->count();

        $manutencoesVencidas = Manutencao::where('id_empresa', $id_empresa)
            ->where('data_manutencao', '<', now())
            ->where('status', '!=', 'concluida')
            ->count();
            
        $alertasProximos = Manutencao::where('id_empresa', $id_empresa)
            ->whereBetween('data_manutencao', [now(), now()->addDays(7)])
            ->where('status', '!=', 'concluida')
            ->count();

        // --- CORREÇÃO 2: Usar 'vei_status' com valor numérico (1 = Ativo) ---
        $frota = Veiculo::where('vei_emp_id', $id_empresa)
            ->where('vei_status', 1)
            ->with(['ultimoAbastecimento'])
            ->get();

        $custoTotalMes = 0;

        foreach ($frota as $veiculo) {
            // --- CORREÇÃO 3: Usar a nova chave primária 'vei_id' ---
            $custoAbastecimento = Abastecimento::where('id_veiculo', $veiculo->vei_id)
                ->whereBetween('data_abastecimento', [$startOfMonth, $endOfMonth])
                ->sum('custo_total');

            // --- CORREÇÃO 4: Usar a nova chave primária 'vei_id' ---
            $custoManutencao = Manutencao::where('id_veiculo', $veiculo->vei_id)
                ->whereBetween('data_manutencao', [$startOfMonth, $endOfMonth])
                ->sum('custo_total');

            // Atribuição dos custos para serem usados na view
            $veiculo->custo_mensal_abastecimento = $custoAbastecimento;
            $veiculo->custo_mensal_manutencao = $custoManutencao;
            
            // O atributo 'custo_total_mensal' já é calculado automaticamente na Model
            $custoTotalMes += $veiculo->custo_total_mensal;
        }

        $proximosLembretes = Manutencao::where('id_empresa', $id_empresa)
            ->where('data_manutencao', '>', now())
            ->where('data_manutencao', '<=', now()->addDays(30))
            ->where('status', '!=', 'concluida')
            ->with('veiculo') // O relacionamento buscará o veículo corretamente
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
