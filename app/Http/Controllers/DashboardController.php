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

        if ($user->role === 'super-admin') {
            return redirect()->route('admin.dashboard');
        }

        if (!$user->id_empresa) {
            return view('dashboard');
        }

        $idEmpresa = $user->id_empresa;

        // --- DADOS PARA OS CARDS ---

        $veiculosAtivos = Veiculo::where('id_empresa', $idEmpresa)->where('status', 'ativo')->count();

        $alertasProximos = Manutencao::where('id_empresa', $idEmpresa)
            ->whereNotNull('proxima_revisao_data')
            ->whereBetween('proxima_revisao_data', [Carbon::now(), Carbon::now()->addDays(30)])
            ->count();
        
        // --- LÓGICA CORRIGIDA E MAIS ROBUSTA ---
        $manutencoesVencidas = Manutencao::where('id_empresa', $idEmpresa)
            ->where(function ($query) {
                // Condição 1: Vencido por data
                $query->where(function ($subQuery) {
                    $subQuery->whereNotNull('proxima_revisao_data')
                             ->where('proxima_revisao_data', '<', Carbon::now());
                })
                // OU Condição 2: Vencido por quilometragem
                ->orWhereHas('veiculo', function ($veiculoQuery) {
                    $veiculoQuery->whereNotNull('manutencoes.proxima_revisao_km')
                                 ->whereColumn('veiculos.quilometragem_atual', '>=', 'manutencoes.proxima_revisao_km');
                });
            })
            ->count();
            
        $custoManutencoes = Manutencao::where('id_empresa', $idEmpresa)->whereMonth('data_manutencao', Carbon::now()->month)->sum('custo_total');
        $custoAbastecimentos = Abastecimento::where('id_empresa', $idEmpresa)->whereMonth('data_abastecimento', Carbon::now()->month)->sum('custo_total');
        $custoMensal = $custoManutencoes + $custoAbastecimentos;

        // --- DADOS PARA AS LISTAS ---

        $frota = Veiculo::where('id_empresa', $idEmpresa)->where('status', 'ativo')->orderBy('placa')->get();

        $proximosLembretes = Manutencao::with('veiculo')
            ->where('id_empresa', $idEmpresa)
            ->whereNotNull('proxima_revisao_data')
            ->where('proxima_revisao_data', '>=', Carbon::now())
            ->orderBy('proxima_revisao_data', 'asc')
            ->take(5)
            ->get();


        return view('dashboard', compact(
            'veiculosAtivos',
            'alertasProximos',
            'manutencoesVencidas',
            'custoMensal',
            'frota',
            'proximosLembretes'
        ));
    }
}
