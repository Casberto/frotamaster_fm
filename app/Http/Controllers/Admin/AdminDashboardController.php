<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(AdminDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $overview = $this->dashboardService->getOverviewData();
        $clients = $this->dashboardService->getClientsData();
        $licensing = $this->dashboardService->getLicensingData();
        $infrastructure = $this->dashboardService->getInfrastructureData();
        $logs = $this->dashboardService->getLogsData();

        return view('admin.dashboard.index', compact(
            'overview',
            'clients',
            'licensing',
            'infrastructure',
            'logs'
        ));
    }
    public function stats()
    {
        $data = $this->dashboardService->getRealTimeServerData();
        return response()->json($data);
    }
}
