<?php

namespace App\Http\Controllers\Oficina;

use App\Http\Controllers\Controller;
use App\Models\Oficina\OrdemServico;
use App\Models\Oficina\OsItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OsItemController extends Controller
{
    public function store(Request $request, $os_id)
    {
        $request->validate([
            'tipo' => 'required|in:peca,servico',
            'descricao' => 'required|string|max:255',
            'quantidade' => 'required|integer|min:1',
            'valor_venda' => 'required|numeric|min:0',
            'valor_custo' => 'nullable|numeric|min:0', // Opcional, mas bom para lucro
        ]);

        $os = OrdemServico::where('osv_id', $os_id)
            ->where('osv_emp_id', Auth::user()->id_empresa)
            ->firstOrFail();

        // Lógica de Reversão de Status e Histórico
        $reverteuStatus = false;
        
        // Só reverte se a OS prevê orçamento. Se for "Direto", não volta status.
        if ($os->osv_gerar_orcamento && in_array($os->osv_status, ['aprovado', 'pecas', 'execucao', 'pronto'])) {
            $os->update(['osv_status' => 'aprovacao']);
            $reverteuStatus = true;
            $osi_aprovado = false; 
        } else {
            // Se não gera orçamento, o item já nasce aprovado
            if (!$os->osv_gerar_orcamento) {
                $osi_aprovado = true;
            } else {
                $osi_aprovado = ($os->osv_status == 'diagnostico'); 
            }
        }

        // Cria o item
        $item = OsItem::create([
            'osi_osv_id' => $os->osv_id,
            'osi_tipo' => $request->tipo, // peca ou servico
            'osi_descricao' => $request->descricao,
            'osi_quantidade' => $request->quantidade,
            'osi_valor_venda_unit' => $request->valor_venda,
            'osi_valor_custo_unit' => $request->valor_custo ?? 0,
            'osi_aprovado' => $osi_aprovado,
        ]);

        // Histórico
        $os->registrarHistorico(
            'Item Adicionado', 
            "{$item->osi_quantidade}x {$item->osi_descricao} (R$ {$item->osi_valor_venda_unit})"
        );

        // Recalcula totais da OS
        $os->atualizarFinanceiro();

        if ($reverteuStatus) {
            $os->registrarHistorico(
                'Status Revertido', 
                'OS voltou para Aprovação devido a adição de novos itens.'
            );
            return back()->with('success', 'Item adicionado! A OS voltou para APROVAÇÃO pois houve alteração.');
        }

        return redirect()->back()->with('success', 'Item adicionado!');
    }

    public function destroy($id)
    {
        $item = OsItem::findOrFail($id);
        
        // Verifica segurança (se o item pertence a uma OS da minha empresa)
        $os = $item->ordemServico; // Use the relationship defined in OsItem model
        
        // Double check relationship access, if $item->os is not defined use ->ordemServico
        if (!$os) {
             $os = OrdemServico::find($item->osi_osv_id);
        }

        if ($os->osv_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        $item->delete();
        $os->atualizarFinanceiro();

        return redirect()->back()->with('success', 'Item removido.');
    }
}
