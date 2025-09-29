<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'servicos';
    protected $primaryKey = 'ser_id';

    protected $fillable = [
        'ser_emp_id',
        'ser_nome',
        'ser_descricao',
    ];
}

