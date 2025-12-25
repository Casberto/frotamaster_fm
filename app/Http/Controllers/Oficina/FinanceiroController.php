<?php

namespace App\Http\Controllers\Oficina;

use App\Http\Controllers\Controller;
use App\Models\Oficina\OrdemServico;
use App\Models\Oficina\OsItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceiroController extends Controller
{
    public function index()
    {
        $empId = Auth::user()->id_empresa;
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();

        // 1. Totais do Mês (Competência: Data de Saída/Entrega)
        // Considera apenas OS ENTREGUES (Encerradas)
        $osQuery = OrdemServico::where('osv_emp_id', $empId)
            ->whereBetween('osv_data_saida', [$inicioMes, $fimMes])
            ->where('osv_status', 'entregue');

        $faturamentoTotal = $osQuery->sum('osv_valor_total');
        $custosTotais = $osQuery->sum('osv_valor_custo_total');
        $lucroLiquido = $faturamentoTotal - $custosTotais;
        $margem = $faturamentoTotal > 0 ? ($lucroLiquido / $faturamentoTotal) * 100 : 0;
        $qtdOs = $osQuery->count();
        $ticketMedio = $qtdOs > 0 ? $faturamentoTotal / $qtdOs : 0;

        // 2. Lucro Futuro Estimado (O que tem pra receber nos próximos dias)
        // Baseado na data de compensação ser futura > hoje
        // Pode incluir OS de meses anteriores que ainda não compensaram?
        // O usuário pediu "Lucro futuro estimado", no contexto do dashboard do mês.
        // Mas contas a receber são globais. Vamos pegar globalmente o que está para entrar.
        $aReceberQuery = OrdemServico::where('osv_emp_id', $empId)
            ->where('osv_status', 'entregue')
            ->where('osv_status_pagamento', 'pago') // Marcado como pago no sistema, mas data compensação futura
            ->whereDate('osv_data_compensacao', '>', Carbon::now());

        $faturamentoFuturo = $aReceberQuery->sum('osv_valor_total');
        $custoFuturo = $aReceberQuery->sum('osv_valor_custo_total');
        $lucroFuturo = $faturamentoFuturo - $custoFuturo;
        
        // 3. Últimas Entregas (Receitas Recentes)
        $ultimasOs = OrdemServico::where('osv_emp_id', $empId)
            ->with(['veiculo.cliente'])
            ->where('osv_status', 'entregue')
            ->orderBy('osv_data_saida', 'desc')
            ->take(5)
            ->get();

        $resumo = (object) [
            'faturamento' => $faturamentoTotal,
            'custos' => $custosTotais,
            'qtd_os' => $qtdOs,
            'ticket_medio' => $ticketMedio
        ];

        return view('oficina.financeiro.index', compact('resumo', 'lucroLiquido', 'margem', 'ultimasOs', 'lucroFuturo', 'faturamentoFuturo'));
    }

    public function listaComprasDia()
    {
        $empId = Auth::user()->id_empresa;
        
        // Busca peças de OS que estão "Aprovadas" ou "Aguardando Peças"
        // Agrupa por nome da peça para facilitar a compra (ex: 2x Filtro de Óleo)
        $pecas = OsItem::whereHas('ordemServico', function($q) use ($empId) {
                $q->where('osv_emp_id', $empId)
                  ->whereIn('osv_status', ['aprovado', 'pecas']); 
            })
            ->where('osi_tipo', 'peca')
            ->select('osi_descricao', DB::raw('SUM(osi_quantidade) as qtd_total'))
            ->groupBy('osi_descricao')
            ->orderBy('osi_descricao')
            ->get();

        return view('oficina.financeiro.compras', compact('pecas'));
    }
}
