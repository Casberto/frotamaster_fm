<?php

namespace App\Http\Controllers\Oficina;

use App\Http\Controllers\Controller;
use App\Models\Oficina\OrdemServico;
use Illuminate\Http\Request;

class PublicOsController extends Controller
{
    public function show($token)
    {
        $os = OrdemServico::with(['empresa', 'itens', 'veiculo', 'cliente'])
            ->where('osv_token_acesso', $token)
            ->firstOrFail();

        return view('oficina.os.public.approval', compact('os'));
    }

    public function approve($token)
    {
        $os = OrdemServico::where('osv_token_acesso', $token)->firstOrFail();

        if ($os->osv_status === 'aprovado') {
            return redirect()->back()->with('status', 'already_approved');
        }

        $os->update(['osv_status' => 'aprovado']);

        // Aqui poderíamos notificar a oficina (Email/Notification)
        
        return redirect()->back()->with('success', 'Orçamento aprovado com sucesso! A oficina já foi notificada.');
    }
}
