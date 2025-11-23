<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreReservaRequest extends FormRequest
{
    // IDs de Permissão (Constantes para facilitar leitura, mas são os fixos do banco)
    const PERM_CRIAR = 34;
    const PERM_APROVAR = 39; // Define quem é gestor

    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->temPermissaoId(self::PERM_CRIAR);
    }

    protected function prepareForValidation()
    {
        $user = Auth::user();

        // REGRA: Se o usuário NÃO tem permissão de aprovar (ID 39), ele é considerado um "Motorista/Comum".
        // Portanto, tentamos vincular automaticamente seu cadastro de motorista.
        if (!$user->temPermissaoId(self::PERM_APROVAR)) {
            
            $motorista = $user->motorista;
            
            if ($motorista) {
                $this->merge([
                    'res_mot_id' => $motorista->mot_id,
                ]);
            }
            // Se não tiver motorista vinculado, segue null ("A definir")
        }
        // Se TEM permissão 39, ele é gestor e tem liberdade total no form.
    }

    public function rules(): array
    {
        $user = Auth::user();
        $empresaId = $user->id_empresa;

        $tiposPermitidos = ['viagem'];
        
        // Apenas quem tem permissão de Aprovar (Gestor) pode criar manutenção
        if ($user->temPermissaoId(self::PERM_APROVAR)) {
            $tiposPermitidos[] = 'manutencao';
        }

        return [
            'res_tipo' => ['required', Rule::in($tiposPermitidos)],
            
            'res_vei_id' => [
                'nullable', 
                Rule::requiredIf($this->input('res_tipo') === 'manutencao'),
                Rule::exists('veiculos', 'vei_id')->where('vei_emp_id', $empresaId)
            ],
            'res_mot_id' => [
                'nullable',
                Rule::exists('motoristas', 'mot_id')->where('mot_emp_id', $empresaId)
            ],
            'res_for_id' => [
                'nullable',
                Rule::requiredIf($this->input('res_tipo') === 'manutencao'),
                Rule::exists('fornecedores', 'for_id')->where('for_emp_id', $empresaId)
            ],
            'res_data_inicio' => 'required|date|after_or_equal:today',
            'res_data_fim' => 'required|date|after:res_data_inicio',
            'res_dia_todo' => 'nullable|boolean',
            'res_origem' => 'nullable|string|max:255',
            'res_destino' => 'nullable|string|max:255',
            'res_just' => 'required|string',
            'res_obs' => 'nullable|string',
            'force_create' => 'nullable|boolean', 
        ];
    }

    public function messages()
    {
        return [
            'res_tipo.in' => 'Você não tem permissão para criar reservas do tipo Manutenção.',
            'res_vei_id.required_if' => 'Para manutenção, o veículo é obrigatório.',
            'res_for_id.required_if' => 'Para manutenção, o fornecedor é obrigatório.',
        ];
    }
}