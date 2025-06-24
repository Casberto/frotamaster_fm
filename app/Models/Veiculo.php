<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_empresa',
        'placa',
        'marca',
        'modelo',
        'ano_fabricacao',
        'ano_modelo',
        'cor',
        'chassi',
        'renavam',
        'tipo_veiculo',
        'tipo_combustivel',
        'quilometragem_atual',
        'data_aquisicao',
        'status',
        'observacoes',
    ];
}
