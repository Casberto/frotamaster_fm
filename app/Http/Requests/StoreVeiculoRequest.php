<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreVeiculoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Apenas usuários logados e associados a uma empresa podem fazer a requisição.
        return Auth::check() && Auth::user()->id_empresa !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $idEmpresa = Auth::user()->id_empresa;
        // O ID do veículo é obtido da rota durante a edição.
        // ex: /veiculos/1
        $veiculoId = $this->route('veiculo') ? $this->route('veiculo')->vei_id : null;

        return [
            'vei_placa' => ['required', 'string', 'max:8', Rule::unique('veiculos', 'vei_placa')->where(fn ($query) => $query->where('vei_emp_id', $idEmpresa))->ignore($veiculoId, 'vei_id')],
            'vei_fabricante' => 'required|string|max:50',
            'vei_modelo' => 'required|string|max:50',
            'vei_cor_predominante' => 'required|string|max:30',
            'vei_ano_fab' => 'required|digits:4|integer|min:1940',
            'vei_ano_mod' => 'required|digits:4|integer|gte:vei_ano_fab',
            'vei_tipo' => 'required|integer',
            'vei_especie' => 'required|integer',
            'vei_carroceria' => 'required|integer',
            'vei_segmento' => 'required|integer|in:1,2,3,4',
            'vei_chassi' => ['nullable', 'string', 'size:17', Rule::unique('veiculos', 'vei_chassi')->where(fn ($query) => $query->where('vei_emp_id', $idEmpresa))->ignore($veiculoId, 'vei_id')],
            'vei_renavam' => ['nullable', 'string', 'min:9', 'max:11', Rule::unique('veiculos', 'vei_renavam')->where(fn ($query) => $query->where('vei_emp_id', $idEmpresa))->ignore($veiculoId, 'vei_id')],
            'vei_km_inicial' => 'required|integer|min:0|max:9999999',
            'vei_km_atual' => 'required|integer|gte:vei_km_inicial|max:9999999',
            'vei_combustivel' => 'required|integer|in:1,2,3,4,5,6,7',
            'vei_cap_tanque' => 'nullable|numeric|min:0',
            'vei_potencia' => 'nullable|string|max:10',
            'vei_cilindradas' => 'nullable|string|max:10',
            'vei_num_motor' => 'nullable|string|max:30',
            'vei_crv' => 'nullable|string|max:12',
            'vei_data_licenciamento' => 'nullable|date',
            'vei_antt' => 'nullable|string|max:20',
            'vei_tara' => 'nullable|integer|min:0',
            'vei_lotacao' => 'nullable|integer|min:0',
            'vei_pbt' => 'nullable|integer|min:0',
            'vei_data_aquisicao' => 'required|date',
            'vei_valor_aquisicao' => 'nullable|numeric|min:0',
            'vei_data_venda' => 'nullable|date|after_or_equal:vei_data_aquisicao',
            'vei_valor_venda' => 'nullable|numeric|min:0',
            'vei_status' => ['required', 'integer', Rule::in([1, 2, 3, 4])],
            'vei_obs' => 'nullable|string',
        ];
    }
}
