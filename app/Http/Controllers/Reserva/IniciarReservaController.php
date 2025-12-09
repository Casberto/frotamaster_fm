<?php

namespace App\Http\Controllers\Reserva;

use App\Http\Controllers\Controller;
use App\Http\Requests\IniciarReservaRequest;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

class IniciarReservaController extends Controller
{
    public function __invoke(IniciarReservaRequest $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);

        if (!Auth::user()->temPermissao('RES005')) {
            abort(403, 'Sem permissão para iniciar viagens.');
        }

        if ($reserva->res_status !== 'aprovada') {
            return back()->with('error', 'Apenas reservas aprovadas podem ser iniciadas.');
        }

        $dados = $request->validated();
        $dados['res_status'] = 'em_uso';
        $dados['res_data_saida_real'] = now();

        $reserva->update($dados);
        
        // Atualiza status do veiculo se necessário (opcional)
        // $reserva->veiculo->update(['vei_status' => 3]); // Em uso

        return back()->with('success', 'Viagem iniciada.');
    }
}