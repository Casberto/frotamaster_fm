<?php

namespace App\Http\Controllers\Oficina;

use App\Http\Controllers\Controller;
use App\Models\Oficina\VeiculoTerceiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VeiculoTerceiroController extends Controller
{
    public function buscarPlaca(Request $request)
    {
        $placa = $request->get('placa');

        // Busca veículo da empresa do usuário
        // Assumimos que um carro pode ser atendido por várias oficinas, 
        // mas aqui buscamos se JÁ esteve na NOSSA oficina.
        // Se a regra for base única, remova o whereHas.
        $veiculo = VeiculoTerceiro::where('vct_placa', $placa)
            ->whereHas('cliente', function($q) {
                $q->where('clo_emp_id', Auth::user()->id_empresa);
            })
            ->with('cliente')
            ->first();

        if ($veiculo) {
            return response()->json([
                'encontrado' => true,
                'veiculo' => $veiculo,
                'cliente' => $veiculo->cliente
            ]);
        }

        return response()->json(['encontrado' => false]);
    }
}
