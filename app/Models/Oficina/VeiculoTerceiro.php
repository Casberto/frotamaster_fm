<?php

namespace App\Models\Oficina;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VeiculoTerceiro extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'veiculos_terceiros';
    protected $primaryKey = 'vct_id';

    protected $guarded = ['vct_id'];

    public function cliente()
    {
        return $this->belongsTo(ClienteOficina::class, 'vct_clo_id', 'clo_id');
    }

    public function ordensServico()
    {
        return $this->hasMany(OrdemServico::class, 'osv_vct_id', 'vct_id');
    }
}
