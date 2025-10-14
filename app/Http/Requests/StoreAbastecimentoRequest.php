<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Veiculo;

class StoreAbastecimentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->id_empresa !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $idEmpresa = Auth::user()->id_empresa;

        // Custom rule to check fuel compatibility
        $veiculo = Veiculo::find($this->input('aba_vei_id'));
        $combustivelRule = 'nullable';

        if ($veiculo && $veiculo->vei_combustivel != 5) { // Not electric
            $combustivelRule = Rule::requiredIf($veiculo->vei_combustivel == 6); // Flex
        }


        return [
            'aba_vei_id' => ['required', Rule::exists('veiculos', 'vei_id')->where('vei_emp_id', $idEmpresa)],
            'aba_for_id' => ['nullable', Rule::exists('fornecedores', 'for_id')->where('for_emp_id', $idEmpresa)],
            'aba_data' => ['required', 'date', 'before_or_equal:today'],
            'aba_km' => ['required', 'integer', 'min:0'],
            'aba_combustivel' => [$combustivelRule, 'integer'],
            'aba_vlr_tot' => ['required', 'string'],
            'aba_qtd' => ['required', 'string'],
            'aba_vlr_und' => ['required', 'string'],
            'aba_tanque_inicio' => ['nullable', 'string'],
            'aba_tanque_cheio' => ['nullable', 'boolean'],
            'aba_pneus_calibrados' => ['nullable', 'boolean'],
            'aba_agua_verificada' => ['nullable', 'boolean'],
            'aba_oleo_verificado' => ['nullable', 'boolean'],
            'aba_obs' => ['nullable', 'string'],
        ];
    }
}
