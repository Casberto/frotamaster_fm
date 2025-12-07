<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreManutencaoRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     * Apenas usuários logados e vinculados a uma empresa podem criar/editar manutenções.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->id_empresa !== null;
    }

    /**
     * Obtém as regras de validação que se aplicam à requisição.
     * Toda a lógica de validação que estava no controller é movida para cá.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $idEmpresa = Auth::user()->id_empresa;

        return [
            'man_vei_id' => ['required', Rule::exists('veiculos', 'vei_id')->where('vei_emp_id', $idEmpresa)],
            'man_for_id' => ['nullable', Rule::exists('fornecedores', 'for_id')->where('for_emp_id', $idEmpresa)],
            'man_tipo' => ['required', Rule::in(['preventiva', 'corretiva', 'preditiva', 'outra'])],
            'man_data_inicio' => ['required', 'date', 'before_or_equal:today'],
            'man_data_fim' => ['nullable', 'date', 'after_or_equal:man_data_inicio'],
            'man_km' => ['required', 'integer', 'min:0'],
            'man_custo_pecas' => ['nullable', 'string'],
            'man_custo_mao_de_obra' => ['nullable', 'string'],
            'man_responsavel' => ['nullable', 'string', 'max:255'],
            'man_nf' => ['nullable', 'string', 'max:255'],
            'man_observacoes' => ['nullable', 'string'],
            'man_prox_revisao_data' => [
                'nullable',
                'date',
                'after_or_equal:man_data_inicio',
                Rule::requiredIf(fn () => $this->input('man_tipo') === 'preventiva' && $this->input('man_status') === 'concluida' && empty($this->input('man_prox_revisao_km'))),
            ],
            'man_prox_revisao_km' => [
                'nullable',
                'integer',
                'min:' . $this->input('man_km', 0),
                Rule::requiredIf(fn () => $this->input('man_tipo') === 'preventiva' && $this->input('man_status') === 'concluida' && empty($this->input('man_prox_revisao_data'))),
            ],
            'man_status' => ['required', Rule::in(['agendada', 'em_andamento', 'concluida', 'cancelada'])],
            'servicos' => ['nullable', 'array'],
            'servicos.*.id' => ['required_with:servicos', 'integer', Rule::exists('servicos', 'ser_id')->where('ser_emp_id', $idEmpresa)],
            'servicos.*.custo' => ['required_with:servicos', 'string'],
            'servicos.*.garantia' => ['nullable', 'date'],
        ];
    }
}
