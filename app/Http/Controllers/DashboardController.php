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
        if (Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        try {
            $data = $this->dashboardService->getDashboardData();
            $executiveData = $this->dashboardService->getExecutiveDashboardData();
            $maintenanceData = $this->dashboardService->getMaintenanceDashboardData();
            $fuelingData = $this->dashboardService->getFuelingDashboardData();
            $reservationsData = $this->dashboardService->getReservationsDashboardData();
            
            // Merge arrays
            $finalData = array_merge(
                $data, 
                $executiveData, 
                ['maintenanceData' => $maintenanceData],
                ['fuelingData' => $fuelingData],
                ['reservationsData' => $reservationsData]
            );
            
            return view('dashboard.index', $finalData);
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
            $data = $this->dashboardService->getVehicleDetails($id);
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ocorreu um erro ao buscar os detalhes do veículo: ' . $e->getMessage()], 500);
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

    /**
     * Retorna os detalhes de uma manutenção para chamadas AJAX.
     */
    public function getMaintenanceDetails($id)
    {
        try {
            $data = $this->dashboardService->getMaintenanceDetails($id);
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao buscar detalhes da manutenção: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Retorna os detalhes de um abastecimento para chamadas AJAX.
     */
    public function getFuelingDetails($id)
    {
        try {
            $data = $this->dashboardService->getFuelingDetails($id);
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao buscar detalhes do abastecimento: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Retorna os detalhes de uma reserva para chamadas AJAX.
     */
    public function getReservationDetails($id)
    {
        try {
            $data = $this->dashboardService->getReservationDetails($id);
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao buscar detalhes da reserva: ' . $e->getMessage()], 500);
        }
    }
}
