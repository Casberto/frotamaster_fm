<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\DashboardService;
use App\Models\Veiculo;
use Exception;
use Carbon\Carbon;

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
    public function index(Request $request)
    {
        if (Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if (!Auth::user()->temPermissao('DAS001')) {
            abort(403, 'Sem permissão para visualizar o dashboard.');
        }

        try {
            // Captura as datas do filtro ou define o padrão (mês atual)
            $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
            $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

            $data = $this->dashboardService->getDashboardData();
            $executiveData = $this->dashboardService->getExecutiveDashboardData($startDate, $endDate);
            $maintenanceData = $this->dashboardService->getMaintenanceDashboardData();
            $fuelingData = $this->dashboardService->getFuelingDashboardData($startDate, $endDate);
            $reservationsData = $this->dashboardService->getReservationsDashboardData();
            
            // Novos Gráficos Financeiros (Despesas)
            $chartTopClientes = $this->dashboardService->getTopClientesChartData($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
            $expenseSummary = $this->dashboardService->getExpenseSummary($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));

            // Merge arrays
            $finalData = array_merge(
                $data, 
                $executiveData, 
                ['maintenanceData' => $maintenanceData],
                ['fuelingData' => $fuelingData],
                ['reservationsData' => $reservationsData],
                // Dados para os Gráficos
                ['chartTopClientes' => $chartTopClientes],
                $expenseSummary, // Contém totalDespesa, totalPecas, totalMO
                // Passa as datas para a view para manter o estado do filtro
                ['filterStartDate' => $startDate->format('Y-m-d'), 'filterEndDate' => $endDate->format('Y-m-d')]
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
