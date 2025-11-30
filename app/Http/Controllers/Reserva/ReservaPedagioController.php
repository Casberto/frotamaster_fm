<?php

namespace App\Http\Controllers\Reserva;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\ReservaPedagio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaPedagioController extends Controller
{
    public function store(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);
        if (!in_array($reserva->res_status, ['em_uso', 'em_revisao', 'pendente_ajuste'])) {
            return back()->with('error', 'Status inválido.');
        }

        $validated = $request->validate([
            'rpe_valor' => 'required|numeric|min:0',
            'rpe_desc' => 'required|string|max:255',
            'rpe_data_hora' => 'required|date',
            'rpe_forma_pagto' => 'required|string|max:50',
            'rpe_reembolso' => 'nullable|boolean',
        ]);

        $reserva->pedagios()->create([
            'rpe_desc' => $validated['rpe_desc'],
            'rpe_valor' => $validated['rpe_valor'],
            'rpe_forma_pagto' => $validated['rpe_forma_pagto'],
            'rpe_reembolso' => $request->boolean('rpe_reembolso'),
            'rpe_data_hora' => $validated['rpe_data_hora'],
        ]);

        return back()->with('success', 'Pedágio registrado.');
    }

    public function destroy(Reserva $reserva, ReservaPedagio $pedagio)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);
        if (!in_array($reserva->res_status, ['em_uso', 'em_revisao', 'pendente_ajuste'])) return back()->with('error', 'Status inválido.');
        
        $pedagio->delete();
        return back()->with('success', 'Pedágio removido.');
    }
}