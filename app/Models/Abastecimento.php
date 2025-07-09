<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abastecimento extends Model
{
    use HasFactory;
    protected $table = 'abastecimentos';

    protected $fillable = [
        'id_veiculo',
        'id_empresa',
        'data_abastecimento',
        'quilometragem',
        'tipo_combustivel',
        'unidade_medida',
        'quantidade',
        'valor_por_unidade',
        'custo_total',
        'nome_posto',
        'nivel_tanque_inicio',
        'tanque_cheio',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'id_veiculo');
    }
}
