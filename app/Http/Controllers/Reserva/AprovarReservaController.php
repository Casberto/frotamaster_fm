<?php

namespace App\Http\Controllers\Reserva;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Services\ReservaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AprovarReservaController extends Controller
{
    const PERM_APROVAR = 39;

    public function __construct(protected ReservaService $reservaService) {}

    public function __invoke(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);
        if (!Auth::user()->temPermissaoId(self::PERM_APROVAR)) abort(403);

        if ($reserva->res_status !== 'pendente') {
            return back()->with('error', 'Apenas reservas pendentes podem ser aprovadas.');
        }

        // Usa o input ou o valor já existente
        $veiculoId = $request->input('veiculo_id') ?: $reserva->res_vei_id;
        $motoristaId = $request->input('motorista_id') ?: $reserva->res_mot_id;

        // Validação manual usando o Validator do Laravel seria melhor, 
        // mas aqui usaremos withErrors para popular o $errors na view
        $errors = [];
        if (empty($veiculoId)) $errors['veiculo_id'] = 'Selecione um veículo válido.';
        if (empty($motoristaId) && $reserva->res_tipo !== 'manutencao') $errors['motorista_id'] = 'É necessário definir um motorista.';

        if (!empty($errors)) {
            // Retorna com erros para que o modal reabra
            return redirect()->route('reservas.index', ['selected_id' => $reserva->res_id])
                ->withErrors($errors);
        }

        $dadosVerificacao = [
            'res_vei_id' => $veiculoId,
            'res_data_inicio' => $reserva->res_data_inicio,
            'res_data_fim' => $reserva->res_data_fim,
            'res_dia_todo' => $reserva->res_dia_todo
        ];

        $conflitos = $this->reservaService->verificarConflitos($dadosVerificacao, $reserva->res_id);
        
        if ($conflitos && $conflitos->has('res_vei_id')) {
             return redirect()->route('reservas.index', ['selected_id' => $reserva->res_id])
                ->withErrors($conflitos);
        }

        $reserva->update([
            'res_vei_id' => $veiculoId,
            'res_mot_id' => $motoristaId,
            'res_status' => 'aprovada',
            'res_obs' => null 
        ]);

        // Redireciona mantendo o ID selecionado para permanecer no "show" dentro do index
        return redirect()->route('reservas.index', ['selected_id' => $reserva->res_id])
            ->with('success', 'Reserva aprovada com sucesso.');
    }
}