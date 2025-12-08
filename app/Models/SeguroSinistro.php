<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguroSinistro extends Model
{
    use HasFactory;

    protected $table = 'seguros_sinistro';
    protected $primaryKey = 'ssi_id';

    protected $fillable = [
        'ssi_seg_id',
        'ssi_data',
        'ssi_tipo',
        'ssi_valor_prejuizo',
        'ssi_valor_coberto',
        'ssi_status',
        'ssi_obs',
        'ssi_anexos',
    ];
    
    protected $casts = [
        'ssi_anexos' => 'array',
        'ssi_data' => 'date',
    ];

    public function apolice() {
        return $this->belongsTo(SeguroApolice::class, 'ssi_seg_id', 'seg_id');
    }

    public function fotos()
    {
        return $this->hasMany(SeguroSinistroFoto::class, 'ssf_ssi_id', 'ssi_id');
    }

    public function getSsiAnexosAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }
}
