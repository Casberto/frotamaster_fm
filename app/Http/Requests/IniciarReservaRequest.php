<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class IniciarReservaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Garante que a reserva pertence à empresa do usuário logado
        // A lógica de perfil (se é motorista ou gestor) será feita no controller
        return $this->reserva->res_emp_id == Auth::user()->id_empresa;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'res_km_inicio' => [
                'required', 
                'integer', 
                'min:0',
                // Validação para garantir que o KM de início é igual ou maior que o KM atual do veículo
                function ($attribute, $value, $fail) {
                    $veiculo_km_atual = $this->reserva->veiculo->vei_km_atual ?? 0;
                    if ($value < $veiculo_km_atual) {
                        $fail("O KM inicial ($value km) não pode ser menor que a quilometragem atual do veículo ($veiculo_km_atual km).");
                    }
                },
            ],
            'res_comb_inicio' => ['required', 'string', 'max:20'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'res_km_inicio' => 'KM Inicial',
            'res_comb_inicio' => 'Nível de Combustível Inicial',
        ];
    }
}
