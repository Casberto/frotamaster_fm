<?php

namespace App\Http\Controllers\Oficina;

use App\Http\Controllers\Controller;
use App\Models\Oficina\OrdemServico;
use Illuminate\Http\Request;

class AprovacaoClienteController extends Controller
{
    public function show($token)
    {
        // Busca a OS pelo token UUID (Segurança: Impossível adivinhar)
        $os = OrdemServico::where('osv_token_acesso', $token)
            ->with(['empresa', 'veiculo.cliente', 'itens'])
            ->firstOrFail();

        // Verifica itens pendentes
        $temPendencias = $os->itens()->where('osi_aprovado', false)->exists();

        // Se já estiver aprovado E não tiver pendências, mostra view de "Já Aprovado"
        if (!$temPendencias && in_array($os->osv_status, ['aprovado', 'pecas', 'execucao', 'pronto', 'entregue'])) {
            return view('oficina.public.already-approved', compact('os'));
        }

        return view('oficina.public.approval', compact('os'));
    }

    public function aceitar(Request $request, $token)
    {
        $os = OrdemServico::where('osv_token_acesso', $token)->firstOrFail();

        // Aprova TODOS os itens pendentes
        $os->itens()->update(['osi_aprovado' => true]);

        // Atualiza status da OS se não estiver aprovada/execucao/etc
        if (!in_array($os->osv_status, ['aprovado', 'pecas', 'execucao', 'pronto'])) {
            $os->update(['osv_status' => 'aprovado']);
        }
        
        // Loga histórico (User ID null = Cliente/Sistema)
        $os->registrarHistorico('Aprovado pelo Cliente', 'Cliente aprovou o orçamento via link público.', null);
        
        // Se já estava aprovada, mantemos o status (apenas aprovou os adicionais)

        return redirect()->route('oficina.os.public.show', $token)->with('success', 'Orçamento aprovado com sucesso!');
    }
}
