<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class UpdateReservaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    protected function prepareForValidation()
    {
        // Se for dia todo, ajusta os horários para 00:00 e 23:59
        if ($this->boolean('res_dia_todo')) {
            $this->merge([
                'res_data_inicio' => substr($this->input('res_data_inicio'), 0, 10) . ' 00:00:00',
                'res_data_fim' => substr($this->input('res_data_fim'), 0, 10) . ' 23:59:59',
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $empresaId = Auth::user()->id_empresa;
        $reservaId = $this->route('reserva')->res_id; 

        return [
            'res_vei_id' => [
                'required',
                Rule::exists('veiculos', 'vei_id')->where('vei_emp_id', $empresaId)
            ],
            'res_mot_id' => [
                'nullable',
                Rule::exists('motoristas', 'mot_id')->where('mot_emp_id', $empresaId)
            ],
            'res_for_id' => [
                'nullable',
                Rule::exists('fornecedores', 'for_id')->where('for_emp_id', $empresaId)
            ],
            'res_tipo' => 'required|in:viagem,manutencao',
            'res_data_inicio' => 'required|date',
            'res_data_fim' => 'required|date|after_or_equal:res_data_inicio',
            'res_dia_todo' => 'nullable|boolean',
            'res_origem' => 'nullable|string|max:255',
            'res_destino' => 'nullable|string|max:255',
            'res_just' => 'nullable|string',
            'res_obs' => 'nullable|string',
            'force_create' => 'nullable|boolean', // Permite o campo de forçar
        ];
    }

    /**
     * O método after() foi REMOVIDO daqui e movido para o Controller.
     */
}