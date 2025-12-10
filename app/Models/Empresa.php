<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

 use HasFactory;

    protected $fillable = [
        'tipo',
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'email_contato',
        'telefone_contato',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
    ];

    public function licencas()
    {
        return $this->hasMany(Licenca::class, 'id_empresa');
    }

    public function activeLicense()
    {
        return $this->hasOne(Licenca::class, 'id_empresa')->where('status', 'ativo')->latest('data_vencimento');
    }

    public function configuracoes()
    {
        return $this->hasMany(ConfiguracaoEmpresa::class, 'cfe_emp_id');
    }
}

