<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'email_contato',
        'telefone_contato',
        'status_pagamento',
        'data_vencimento_plano',
    ];
}
