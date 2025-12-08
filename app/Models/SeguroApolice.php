<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguroApolice extends Model
{
    use HasFactory;

    protected $table = 'seguros_apolice';
    protected $primaryKey = 'seg_id';
    
    protected $fillable = [
        'seg_emp_id',
        'seg_vei_id',
        'seg_for_id',
        'seg_numero',
        'seg_inicio',
        'seg_fim',
        'seg_valor_total',
        'seg_parcelas',
        'seg_tipo',
        'seg_franquia',
        'seg_obs',
        'seg_status',
        'seg_arquivo',
    ];

    protected $casts = [
        'seg_inicio' => 'date',
        'seg_fim' => 'date',
    ];

    public function empresa() {
        return $this->belongsTo(Empresa::class, 'seg_emp_id', 'emp_id');
    }

    public function veiculo() {
        return $this->belongsTo(Veiculo::class, 'seg_vei_id', 'vei_id');
    }

    public function fornecedor() {
        return $this->belongsTo(Fornecedor::class, 'seg_for_id', 'for_id');
    }

    public function coberturas() {
        return $this->hasMany(SeguroCobertura::class, 'sco_seg_id', 'seg_id');
    }

    public function sinistros() {
        return $this->hasMany(SeguroSinistro::class, 'ssi_seg_id', 'seg_id');
    }
}
