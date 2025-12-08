<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguroSinistroFoto extends Model
{
    use HasFactory;

    protected $table = 'seguros_sinistros_fotos';
    protected $primaryKey = 'ssf_id';
    public $timestamps = false; // We use ssf_criado_em manually or default

    protected $fillable = [
        'ssf_ssi_id',
        'arquivo',
        'ssf_tipo',
        'ssf_criado_em'
    ];

    public function sinistro()
    {
        return $this->belongsTo(SeguroSinistro::class, 'ssf_ssi_id', 'ssi_id');
    }
}
