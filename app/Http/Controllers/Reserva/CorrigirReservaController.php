<?php

namespace App\Http\Controllers\Reserva;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CorrigirReservaController extends Controller
{
    public function __invoke(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);

        // Permissão de Editar é necessária para corrigir
        if (!Auth::user()->hasPermission('Reservas', 'Editar')) {
            abort(403, 'Sem permissão para corrigir reservas.');
        }

        if ($reserva->res_status !== 'pendente_ajuste') {
            return back()->with('error', 'Apenas reservas pendentes de ajuste podem ser corrigidas.');
        }

        // Retorna para revisão
        $reserva->res_status = 'em_revisao';
        $reserva->save();

        return back()->with('success', 'Correções concluídas. Reserva enviada para revisão.');
    }
}
