<?php

namespace App\Http\Controllers\Reserva;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Abastecimento;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ReservaAbastecimentoController extends Controller
{
    /**
     * VINCULAR: Associa um abastecimento já existente à reserva.
     */
    public function store(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);
        
        if (!in_array($reserva->res_status, ['em_uso', 'em_revisao', 'pendente_ajuste'])) {
            return back()->with('error', 'Status inválido para adicionar registros.');
        }

        $validated = $request->validate([
            'abastecimento_id' => ['required', Rule::exists('abastecimentos', 'aba_id')->where('aba_emp_id', Auth::user()->id_empresa)],
            'forma_pagamento' => 'nullable|string|max:50', // Tornado nullable para simplificar
            'reembolso' => 'nullable|boolean', // Tornado nullable
        ]);

        // Verifica se já está vinculado
        if ($reserva->abastecimentos()->where('aba_id', $validated['abastecimento_id'])->exists()) {
            return back()->with('error', 'Este abastecimento já está vinculado.');
        }

        DB::table('reserva_abastecimentos')->insert([
            'rab_res_id' => $reserva->res_id,
            'rab_abs_id' => $validated['abastecimento_id'],
            'rab_mot_id' => $reserva->res_mot_id ?? Auth::id(),
            'rab_emp_id' => $reserva->res_emp_id,
            'rab_forma_pagto' => $validated['forma_pagamento'] ?? 'N/D',
            'rab_reembolso' => $validated['reembolso'] ?? false,
            'created_at' => now()
        ]);

        return back()->with('success', 'Abastecimento vinculado com sucesso.');
    }

    /**
     * REGISTRAR NOVO: Cria um abastecimento do zero e já vincula automaticamente.
     */
    public function storeNew(Request $request, Reserva $reserva)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);

        if (!in_array($reserva->res_status, ['em_uso', 'em_revisao', 'pendente_ajuste'])) {
            return back()->with('error', 'Status inválido para adicionar registros.');
        }

        $validated = $request->validate([
            'aba_data' => 'required|date',
            'aba_km' => 'required|integer|min:0',
            'aba_tipo_combustivel' => 'required|string', 
            'aba_qtd' => 'required|numeric|min:0.01',
            'aba_vlr_unit' => 'required|numeric|min:0',
            'aba_vlr_tot' => 'required|numeric|min:0',
            'forma_pagamento' => 'required|string|max:50',
            'reembolso' => 'nullable|boolean',
            'aba_tanque_cheio' => 'nullable|boolean',
            'aba_obs' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Mapeamento simples do tipo (Strings para IDs)
            $combustivelMap = [
                'Gasolina' => 1, 'Etanol' => 2, 'Diesel' => 3, 'GNV' => 4, 'Elétrico' => 5, 'Flex' => 6
            ];
            $combustivelId = $combustivelMap[$validated['aba_tipo_combustivel']] ?? 1;

            $abastecimento = Abastecimento::create([
                'aba_emp_id' => $reserva->res_emp_id,
                'aba_user_id' => Auth::id(),
                'aba_vei_id' => $reserva->res_vei_id,
                'aba_data' => $validated['aba_data'],
                'aba_km' => $validated['aba_km'],
                'aba_combustivel' => $combustivelId,
                'aba_und_med' => ($combustivelId == 5) ? 'kWh' : (($combustivelId == 4) ? 'm³' : 'L'),
                'aba_qtd' => $validated['aba_qtd'],
                'aba_vlr_und' => $validated['aba_vlr_unit'],
                'aba_vlr_tot' => $validated['aba_vlr_tot'],
                'aba_tanque_cheio' => $request->has('aba_tanque_cheio'),
                'aba_obs' => $validated['aba_obs'] ?? null,
            ]);

            // Atualiza KM do veículo se for maior
            if ($reserva->veiculo && $validated['aba_km'] > $reserva->veiculo->vei_km_atual) {
                $reserva->veiculo->update(['vei_km_atual' => $validated['aba_km']]);
            }

            // Vincula
            // Vincula
            DB::table('reserva_abastecimentos')->insert([
                'rab_res_id' => $reserva->res_id,
                'rab_abs_id' => $abastecimento->aba_id,
                'rab_mot_id' => $reserva->res_mot_id ?? Auth::id(),
                'rab_emp_id' => $reserva->res_emp_id,
                'rab_forma_pagto' => $validated['forma_pagamento'],
                'rab_reembolso' => $request->boolean('reembolso'),
                'created_at' => now()
            ]);

            DB::commit();
            return back()->with('success', 'Abastecimento registrado e vinculado.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao registrar: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Reserva $reserva, Abastecimento $abastecimento)
    {
        if ($reserva->res_emp_id !== Auth::user()->id_empresa) abort(403);
        
        $reserva->abastecimentos()->detach($abastecimento->aba_id);
        return back()->with('success', 'Abastecimento desvinculado.');
    }
}