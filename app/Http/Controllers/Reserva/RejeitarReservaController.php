<?php

namespace App\Http\Controllers\Reserva;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RejeitarReservaController extends Controller
{
    public function __invoke(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);

        if (!Auth::user()->temPermissao('RES008')) {
            abort(403, 'Sem permissão para reprovar reservas.');
        }

        if ($reserva->res_status !== 'pendente') {
            return back()->with('error', 'Apenas reservas pendentes podem ser rejeitadas.');
        }

        $validated = $request->validate([
            'motivo_rejeicao' => 'required|string|max:500',
        ]);

        $reserva->update([
            'res_status' => 'rejeitada',
            'res_obs' => $validated['motivo_rejeicao']
        ]);

        return redirect()->route('reservas.show', $reserva)->with('success', 'Reserva rejeitada.');
    }
}