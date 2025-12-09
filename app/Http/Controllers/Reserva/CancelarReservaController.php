<?php

namespace App\Http\Controllers\Reserva;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CancelarReservaController extends Controller
{
    public function __invoke(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);

        if (!Auth::user()->temPermissao('RES004')) {
            abort(403, 'Sem permissão para cancelar reservas.');
        }

        if (in_array($reserva->res_status, ['em_uso', 'em_revisao', 'encerrada', 'finalizada'])) {
            return back()->with('error', 'Não é possível cancelar uma reserva em andamento ou finalizada.');
        }

        $request->validate(['motivo_cancelamento' => 'nullable|string|max:255']);

        $reserva->update([
            'res_status' => 'cancelada',
            'res_obs' => $request->input('motivo_cancelamento')
        ]);

        return redirect()->route('reservas.index')->with('success', 'Reserva cancelada.');
    }
}