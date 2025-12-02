<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeiculoFoto extends Model
{
    protected $table = 'veiculos_fotos';
    protected $primaryKey = 'vef_id';
    public $timestamps = false;

    protected $fillable = [
        'vef_vei_id',
        'arquivo',
        'vef_criado_em',
    ];

    protected $casts = [
        'vef_criado_em' => 'datetime',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'vef_vei_id', 'vei_id');
    }
}
