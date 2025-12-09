<?php

namespace App\Http\Controllers\Reserva;

use App\Http\Controllers\Controller;
use App\Http\Requests\RevisarReservaRequest;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

class RevisarReservaController extends Controller
{
    public function __invoke(RevisarReservaRequest $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);

        if (!Auth::user()->temPermissao('RES009')) {
            abort(403, 'Sem permissão para revisar/encerrar reservas.');
        }

        if ($reserva->res_status !== 'em_revisao') {
            return back()->with('error', 'Apenas reservas em revisão podem ser processadas.');
        }

        $reserva->res_revisor_id = Auth::id();
        $reserva->res_data_revisao = now();
        $reserva->res_obs_revisor = $request->input('res_obs_revisor');

        if ($request->input('acao') === 'encerrar') {
            $reserva->res_status = 'encerrada';
            $msg = 'Reserva encerrada com sucesso.';
        } else {
            $reserva->res_status = 'pendente_ajuste';
            $msg = 'Reserva devolvida para ajuste.';
        }

        $reserva->save();
        return back()->with('success', $msg);
    }
}