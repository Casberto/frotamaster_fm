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
        // Se o usuário não pertence a uma empresa, exibe a view padrão do Breeze.
        if (!Auth::user()->id_empresa) {
            return view('dashboard'); // View padrão para usuários sem empresa (ex: super-admin)
        }

        $idEmpresa = Auth::user()->id_empresa;

        // 1. Cards de Resumo
        $veiculosAtivos = Veiculo::where('id_empresa', $idEmpresa)->where('status', 'ativo')->count();
        
        // Exemplo de como calcular manutenções vencidas
        $manutencoesVencidas = Manutencao::where('id_empresa', $idEmpresa)
            ->where('data_manutencao', '<', Carbon::now())
            ->where('status', 'pendente')
            ->count();
            
        // Exemplo de como calcular custo mensal (soma de manutenções e abastecimentos no mês atual)
        $custoManutencoes = Manutencao::where('id_empresa', $idEmpresa)
            ->whereMonth('data_manutencao', Carbon::now()->month)
            ->whereYear('data_manutencao', Carbon::now()->year)
            ->sum('custo_total');
            
        $custoAbastecimentos = Abastecimento::where('id_empresa', $idEmpresa)
            ->whereMonth('data_abastecimento', Carbon::now()->month)
            ->whereYear('data_abastecimento', Carbon::now()->year)
            ->sum('custo_total');
            
        $custoMensal = $custoManutencoes + $custoAbastecimentos;

        // 2. Lista da Frota para o Dropdown Interativo
        // Carregando os veículos com suas manutenções pendentes para otimizar a consulta.
        $frota = Veiculo::where('id_empresa', $idEmpresa)
                        ->where('status', 'ativo')
                        ->with(['manutencoes' => function ($query) {
                            $query->where('status', 'pendente')->orderBy('data_manutencao', 'asc');
                        }])
                        ->orderBy('marca')
                        ->orderBy('modelo')
                        ->get();

        // 3. Próximos Lembretes (exemplo)
        $proximosLembretes = Manutencao::where('id_empresa', $idEmpresa)
            ->where('data_manutencao', '>', Carbon::now())
            ->where('data_manutencao', '<=', Carbon::now()->addDays(30)) // Lembretes para os próximos 30 dias
            ->where('status', 'pendente')
            ->with('veiculo')
            ->orderBy('data_manutencao', 'asc')
            ->take(5)
            ->get();
            
        // O campo 'alertasProximos' pode ser a contagem de $proximosLembretes
        $alertasProximos = $proximosLembretes->count();


        return view('dashboard', compact(
            'veiculosAtivos',
            'manutencoesVencidas',
            'custoMensal',
            'alertasProximos',
            'frota',
            'proximosLembretes'
        ));
    }
}
