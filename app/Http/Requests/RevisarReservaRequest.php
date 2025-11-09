<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RevisarReservaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Garante que a reserva pertence à empresa
        $reserva = $this->route('reserva');
        if (!$reserva || $reserva->res_emp_id != Auth::user()->id_empresa) {
             return false;
        }

        // TODO: Lógica de permissão de PERFIL (apenas Gestor/Admin)
        // Ex: return Auth::user()->pode('reservas_revisar');

        return true; // Permitir por padrão por enquanto
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'acao' => 'required|string|in:encerrar,devolver',
            'res_obs_revisor' => [
                'nullable',
                'string',
                'max:2000',
                // Observação é obrigatória se a ação for 'devolver'
                Rule::requiredIf($this->input('acao') === 'devolver'),
            ],
        ];
    }

     /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'res_obs_revisor.required_if' => 'As observações são obrigatórias para devolver a reserva para ajuste.',
        ];
    }
}
