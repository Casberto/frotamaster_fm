<?php

namespace App\Http\Controllers\Reserva;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinalizarReservaRequest;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

class FinalizarReservaController extends Controller
{
    const PERM_FINALIZAR = 38;

    public function __invoke(FinalizarReservaRequest $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);

        if (!Auth::user()->temPermissaoId(self::PERM_FINALIZAR)) {
            abort(403, 'Sem permissão para finalizar viagens.');
        }

        if ($reserva->res_status !== 'em_uso') {
            return back()->with('error', 'Apenas reservas em andamento podem ser finalizadas.');
        }

        // Dados validados (garantindo que o Request valide 'res_km_fim')
        $dados = $request->validated();
        
        $dados['res_status'] = 'em_revisao';
        $dados['res_data_chegada_real'] = now();

        // CORREÇÃO: Usar 'res_km_fim' em vez de 'res_km_chegada'
        if ($reserva->veiculo && isset($dados['res_km_fim'])) {
            // Atualiza o KM do veículo se for maior que o atual
            if ($dados['res_km_fim'] > $reserva->veiculo->vei_km_atual) {
                $reserva->veiculo->update([
                    'vei_km_atual' => $dados['res_km_fim'],
                    'vei_status' => 1 // Volta para Ativo/Disponível
                ]);
            } else {
                // Apenas libera o status
                $reserva->veiculo->update(['vei_status' => 1]);
            }
        }

        $reserva->update($dados);

        // CORREÇÃO: Redireciona para o INDEX mantendo o ID selecionado (Master-Detail)
        return redirect()->route('reservas.index', ['selected_id' => $reserva->res_id])
            ->with('success', 'Viagem finalizada. Enviada para revisão.');
    }
}