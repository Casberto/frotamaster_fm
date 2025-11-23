<?php

namespace App\Http\Controllers\Reserva;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Manutencao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReservaManutencaoController extends Controller
{
    /**
     * Vincula uma manutenção à reserva.
     */
    public function store(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);

        if ($reserva->res_tipo !== 'manutencao') {
            return back()->with('error', 'Manutenções só podem ser vinculadas a reservas do tipo Manutenção.');
        }

        $validated = $request->validate([
            'manutencao_id' => [
                'required',
                Rule::exists('manutencoes', 'man_id')->where('man_emp_id', Auth::user()->id_empresa)
            ],
        ]);

        // Verifica duplicidade
        if ($reserva->manutencoes()->where('man_id', $validated['manutencao_id'])->exists()) {
            return back()->with('error', 'Manutenção já vinculada.');
        }

        // Vincula (Pivot)
        $reserva->manutencoes()->attach($validated['manutencao_id'], [
            'rma_emp_id' => $reserva->res_emp_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Manutenção vinculada.');
    }

    /**
     * Desvincula uma manutenção.
     */
    public function destroy(Reserva $reserva, Manutencao $manutencao)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);

        $reserva->manutencoes()->detach($manutencao->man_id);

        return back()->with('success', 'Manutenção desvinculada.');
    }
}