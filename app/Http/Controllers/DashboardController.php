<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\DashboardService;
use App\Models\Veiculo;
use Exception;

class DashboardController extends Controller
{
    /**
     * O serviço que contém a lógica de negócio do dashboard.
     */
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Exibe o dashboard principal.
     */
    public function index()
    {
        if (!Auth::user()->id_empresa) {
            return view('dashboard.index'); // Para super-admin sem empresa
        }

        try {
            $data = $this->dashboardService->getDashboardData();
            return view('dashboard.index', $data);
        } catch (Exception $e) {
            // Adiciona tratamento de erro para falhas na busca de dados
            // Redireciona de volta com uma mensagem de erro
            return back()->with('error', 'Não foi possível carregar os dados do dashboard. Erro: ' . $e->getMessage());
        }
    }

    /**
     * Retorna o histórico de um veículo para chamadas AJAX.
     */
    public function getVeiculoHistorico($id)
    {
        try {
            $idEmpresa = Auth::user()->id_empresa;

            $veiculo = Veiculo::where('vei_id', $id)->where('vei_emp_id', $idEmpresa)->firstOrFail();

            $manutencoes = $veiculo->manutencoes()->with(['fornecedor', 'servicos'])->latest('man_data_inicio')->take(5)->get();
            $abastecimentos = $veiculo->abastecimentos()->with('fornecedor')->latest('aba_data')->take(5)->get();
            
            return response()->json([
                'manutencoes' => $manutencoes,
                'abastecimentos' => $abastecimentos
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => 'Ocorreu um erro no servidor.'], 500);
        }
    }

    /**
     * Retorna os dados para os gráficos para chamadas AJAX.
     */
    public function getChartData(Request $request)
    {
        try {
            $data = $this->dashboardService->getChartData($request);
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => 'Não foi possível carregar os dados dos gráficos.'], 500);
        }
    }
}
