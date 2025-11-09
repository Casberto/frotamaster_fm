<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Reserva; // Import Reserva

class FinalizarReservaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Verifica se a reserva pertence à empresa do usuário logado
        // O Laravel faz a injeção do model binding automaticamente
        $reserva = $this->route('reserva'); // Pega a reserva da rota
        if (!$reserva || $reserva->res_emp_id != Auth::user()->id_empresa) {
             return false; // Não autorizado se não encontrar ou não pertencer
        }

        // TODO: Adicionar lógica de permissão de perfil aqui (Motorista ou Gestor)
        // Exemplo: return Auth::user()->pode('reservas_finalizar') || $reserva->res_mot_id == Auth::user()->motorista->mot_id;

        return true; // Permitir por padrão por enquanto
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $reserva = $this->route('reserva'); // Pega a reserva da rota novamente
        $kmInicio = $reserva->res_km_inicio ?? 0; // Pega o KM inicial para validação

        return [
            'res_km_fim' => [
                'required',
                'integer',
                'min:' . $kmInicio, // KM final não pode ser menor que o inicial
            ],
            'res_comb_fim' => 'required|string|in:cheio,3/4,1/2,1/4,reserva',
            'res_hora_chegada' => 'required|date',
            'res_obs_finais' => 'nullable|string',
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
            'res_km_fim.min' => 'A quilometragem final não pode ser menor que a quilometragem inicial (:min km).',
        ];
    }
}
