<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguroCobertura extends Model
{
    use HasFactory;

    protected $table = 'seguros_cobertura';
    protected $primaryKey = 'sco_id';

    protected $fillable = [
        'sco_seg_id',
        'sco_titulo',
        'sco_descricao',
        'sco_valor',
    ];

    public function apolice() {
        return $this->belongsTo(SeguroApolice::class, 'sco_seg_id', 'seg_id');
    }
}
