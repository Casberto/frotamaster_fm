<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fornecedores';
    protected $primaryKey = 'for_id';

    protected $fillable = [
        'for_emp_id',
        'for_nome_fantasia',
        'for_razao_social',
        'for_cnpj_cpf',
        'for_contato_email',
        'for_contato_telefone',
        'for_endereco',
        'for_observacoes',
    ];
}
