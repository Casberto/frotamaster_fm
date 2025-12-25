<?php

namespace App\Http\Controllers\Oficina;

use App\Http\Controllers\Controller;
use App\Models\Oficina\OrdemServico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PainelController extends Controller
{
    public function index()
    {
        // Busca todas as OS ativas (não entregues)
        // Ordena por prioridade (Urgente primeiro) e depois por data
        $ordens = OrdemServico::with(['veiculo.cliente', 'empresa'])
            ->where('osv_emp_id', Auth::user()->id_empresa) // Assumindo escopo de empresa
            ->where('osv_status', '!=', 'entregue') // Entregues somem do painel principal
            ->orderByRaw("FIELD(osv_prioridade, 'urgente', 'alta', 'normal', 'baixa')")
            ->orderBy('created_at', 'asc') // Mais antigas primeiro (FIFO)
            ->get();

        // Mapeamento de Status para Labels e Cores (Tailwind classes)
        $statusMap = [
            'aguardando' => ['label' => 'Aguardando', 'color' => 'gray', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            'diagnostico' => ['label' => 'Diagnóstico', 'color' => 'blue', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
            'aprovacao' => ['label' => 'Aprovação', 'color' => 'yellow', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            'aprovado' => ['label' => 'Aprovado', 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            'pecas' => ['label' => 'Peças', 'color' => 'purple', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            'execucao' => ['label' => 'Execução', 'color' => 'orange', 'icon' => 'M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z'],
            'pronto' => ['label' => 'Pronto', 'color' => 'teal', 'icon' => 'M3 21v-8a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9'],
        ];

        return view('oficina.painel.index', compact('ordens', 'statusMap'));
    }

    public function historico()
    {
        $ordens = OrdemServico::with(['veiculo.cliente', 'empresa'])
            ->where('osv_emp_id', Auth::user()->id_empresa)
            ->whereIn('osv_status', ['entregue', 'cancelado'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('oficina.painel.historico', compact('ordens'));
    }

    public function updateStatus(Request $request)
    {
        // Implementaremos a lógica de mover o card depois
        // Mas a rota já existe.
    }
}
