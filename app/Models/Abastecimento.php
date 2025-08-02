<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abastecimento extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_veiculo',
        'id_empresa',
        'id_user',
        'data_abastecimento',
        'quilometragem',
        'unidade_medida',
        'quantidade',
        'valor_por_unidade',
        'custo_total',
        'nome_posto',
        'tipo_combustivel',
        'nivel_tanque_chegada',
        'nivel_tanque_saida',
        'tanque_cheio',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_abastecimento' => 'date',
        'tanque_cheio' => 'boolean',
    ];

    /**
     * Define a relação: um abastecimento pertence a um veículo.
     */
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'id_veiculo');
    }

    /**
     * Define a relação: um abastecimento pertence a uma empresa.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    /**
     * Define a relação: um abastecimento é registrado por um usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
