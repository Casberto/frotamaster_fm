<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\Licenca;
use App\Models\Log;
use App\Models\User; // Assuming User model is needed for user activity
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardService
{
    /**
     * Retrieves data for the 'Visão Geral' (Overview) tab.
     */
    public function getOverviewData()
    {
        $totalClientes = Empresa::count();
        
        $clientesTrial = Licenca::where('plano', 'trial') // Assuming 'trial' is the plan name or check logic
                                ->where('status', 'ativo')
                                ->count();
                                
        // Licenças vencendo em até 30 dias
        $licencasVencendo = Licenca::where('status', 'ativo')
                                   ->where('data_vencimento', '>=', Carbon::now())
                                   ->where('data_vencimento', '<=', Carbon::now()->addDays(30))
                                   ->count();

        // Usuários ativos (logged in recently - approximation via logs or last_login if available)
        // Since User model doesn't show last_login, we'll use Logs if available or just count total active users
        $usuariosAtivos24h = Log::where('created_at', '>=', Carbon::now()->subDay())
                                ->distinct('user_id')
                                ->count('user_id');

        return [
            'total_clientes' => $totalClientes,
            'clientes_trial' => $clientesTrial,
            'licencas_vencendo' => $licencasVencendo,
            'usuarios_ativos_24h' => $usuariosAtivos24h,
            // Server stats will be fetched via separate method or helper
        ];
    }

    /**
     * Retrieves data for the 'Clientes' tab.
     */
    public function getClientsData()
    {
        $novosClientes = Empresa::with('activeLicense')
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

        // Expiring licenses details
        $vencendoProximo = Licenca::with('empresa')
                                  ->where('status', 'ativo')
                                  ->where('data_vencimento', '>=', Carbon::now())
                                  ->where('data_vencimento', '<=', Carbon::now()->addDays(30))
                                  ->orderBy('data_vencimento', 'asc')
                                  ->get();

        return [
            'novos_clientes' => $novosClientes,
            'vencendo_proximo' => $vencendoProximo,
        ];
    }

    /**
     * Retrieves data for the 'Licenciamento' tab.
     */
    public function getLicensingData()
    {
        // Count by plan
        $planosDistrib = Licenca::where('status', 'ativo')
                                ->select('plano', DB::raw('count(*) as total'))
                                ->groupBy('plano')
                                ->get();
        
        // Revenue estimation (optional, rough sum of value_pago)
        // This accepts that monthly plans might be just the last payment value
        $receitaEstimada = Licenca::where('status', 'ativo')->sum('valor_pago');

        return [
            'distribuicao_planos' => $planosDistrib,
            'receita_estimada' => $receitaEstimada,
        ];
    }

    /**
     * Retrieves server infrastructure data.
     * Note: PHP has limited access to OS stats without specific extensions or shell_exec.
     */
    public function getInfrastructureData()
    {
        return $this->getRealTimeServerData();
    }

    /**
     * Retrieves recent logs.
     */
    public function getLogsData()
    {
        return Log::with(['user', 'empresa'])
                  ->orderBy('created_at', 'desc')
                  ->take(20)
                  ->get();
    }

    /**
     * Helper to format bytes.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    /**
     * Retrieves real-time server statistics (CPU, RAM, Disk).
     * Uses shell_exec with Windows commands (WMIC/PowerShell).
     */
    public function getRealTimeServerData()
    {
        // CPU Usage
        $cpuLoad = 0;
        if (function_exists('shell_exec')) {
            try {
                $cpuOutput = shell_exec('wmic cpu get loadpercentage /value');
                if ($cpuOutput && preg_match('/LoadPercentage=(\d+)/', $cpuOutput, $matches)) {
                    $cpuLoad = (int)$matches[1];
                } else {
                    $psCpu = shell_exec("powershell -command \"Get-WmiObject Win32_Processor | Measure-Object -Property LoadPercentage -Average | Select-Object -ExpandProperty Average\"");
                    $cpuLoad = (int)trim($psCpu);
                }
            } catch (\Exception $e) {
                $cpuLoad = 0;
            }
        }

        // RAM Usage
        $totalRam = 0;
        $freeRam = 0;
        if (function_exists('shell_exec')) {
            try {
                $ramOutput = shell_exec('wmic OS get FreePhysicalMemory,TotalVisibleMemorySize /value');
                if ($ramOutput) {
                    preg_match('/FreePhysicalMemory=(\d+)/', $ramOutput, $freeMatches);
                    preg_match('/TotalVisibleMemorySize=(\d+)/', $ramOutput, $totalMatches);
                    
                    if (isset($totalMatches[1]) && isset($freeMatches[1])) {
                        $totalRam = (int)$totalMatches[1]; // KB
                        $freeRam = (int)$freeMatches[1];   // KB
                    }
                }
            } catch (\Exception $e) {
                 $totalRam = 0;
            }
        }

        $usedRam = $totalRam - $freeRam;
        $ramPercent = $totalRam > 0 ? round(($usedRam / $totalRam) * 100, 1) : 0;

        // Disk Usage (App Directory)
        $diskTotal = 0;
        $diskFree = 0;
        try {
            $path = base_path(); 
            // On Windows, verify path is valid driver
            $diskFree = disk_free_space($path);
            $diskTotal = disk_total_space($path);
        } catch (\Exception $e) {}
        
        $diskPercent = $diskTotal > 0 ? round((($diskTotal - $diskFree) / $diskTotal) * 100, 1) : 0;

        return [
            'cpu_load' => $cpuLoad,
            'ram_usage' => $this->formatBytes($usedRam * 1024), 
            'ram_total' => $this->formatBytes($totalRam * 1024),
            'ram_percent' => $ramPercent,
            'disk_percent' => $diskPercent,
            'disk_free' => $this->formatBytes($diskFree),
            'disk_total' => $this->formatBytes($diskTotal),
        ];
    }
}
