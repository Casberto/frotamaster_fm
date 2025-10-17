<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motorista extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'motoristas';
    protected $primaryKey = 'mot_id';

    protected $fillable = [
        'mot_emp_id',
        'mot_user_id',
        'mot_nome',
        'mot_apelido',
        'mot_data_nascimento',
        'mot_genero',
        'mot_nacionalidade',
        'mot_estado_civil',
        'mot_nome_mae',
        'mot_nome_pai',
        'mot_cpf',
        'mot_rg',
        'mot_orgao_emissor_rg',
        'mot_data_emissao_rg',
        'mot_pis',
        'mot_ctps_numero',
        'mot_ctps_serie',
        'mot_titulo_eleitor',
        'mot_zona_eleitoral',
        'mot_secao_eleitoral',
        'mot_cnh_numero',
        'mot_cnh_categoria',
        'mot_cnh_data_emissao',
        'mot_cnh_data_validade',
        'mot_cnh_primeira_habilitacao',
        'mot_cnh_uf',
        'mot_cnh_observacoes',
        'mot_email',
        'mot_telefone1',
        'mot_telefone2',
        'mot_cep',
        'mot_endereco',
        'mot_numero',
        'mot_complemento',
        'mot_bairro',
        'mot_cidade',
        'mot_estado',
        'mot_data_admissao',
        'mot_data_demissao',
        'mot_tipo_contrato',
        'mot_categoria_profissional',
        'mot_matricula_interna',
        'mot_observacoes',
        'mot_banco',
        'mot_agencia',
        'mot_conta',
        'mot_tipo_conta',
        'mot_chave_pix',
        'mot_status',
    ];

    protected $casts = [
        'mot_data_nascimento' => 'date',
        'mot_data_emissao_rg' => 'date',
        'mot_cnh_data_emissao' => 'date',
        'mot_cnh_data_validade' => 'date',
        'mot_cnh_primeira_habilitacao' => 'date',
        'mot_data_admissao' => 'date',
        'mot_data_demissao' => 'date',
        'mot_status' => 'boolean',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'mot_emp_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'mot_user_id');
    }
}
