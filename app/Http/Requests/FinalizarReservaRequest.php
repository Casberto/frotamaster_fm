<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FinalizarReservaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            // CORREÇÃO: Nome do campo padronizado para o banco de dados
            'res_km_fim' => 'required|integer|min:0',
            'res_comb_fim' => 'required|string',
            'res_hora_chegada' => 'required|date',
            'res_obs_finais' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'res_km_fim.required' => 'A quilometragem de chegada é obrigatória.',
            'res_km_fim.integer' => 'A quilometragem deve ser um número inteiro.',
            'res_comb_fim.required' => 'Informe o nível de combustível na chegada.',
        ];
    }
}