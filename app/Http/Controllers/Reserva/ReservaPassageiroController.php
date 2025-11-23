<?php

namespace App\Http\Controllers\Reserva;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\ReservaPassageiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaPassageiroController extends Controller
{
    public function store(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);
        if (!in_array($reserva->res_status, ['em_uso', 'em_revisao'])) return back()->with('error', 'Status inválido.');

        $validated = $request->validate([
            'rpa_nome' => 'required|string|max:255',
            'rpa_doc' => 'nullable|string|max:50',
            'rpa_entrou_em' => 'required|string|max:255',
        ]);

        $reserva->passageiros()->create($validated);

        return back()->with('success', 'Passageiro adicionado.');
    }

    public function destroy(Reserva $reserva, ReservaPassageiro $passageiro)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);
        if (!in_array($reserva->res_status, ['em_uso', 'em_revisao'])) return back()->with('error', 'Status inválido.');

        $passageiro->delete();
        return back()->with('success', 'Passageiro removido.');
    }
}