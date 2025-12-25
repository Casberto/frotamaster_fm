<?php

namespace App\Models\Oficina;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClienteOficina extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clientes_oficina';
    protected $primaryKey = 'clo_id';

    protected $guarded = ['clo_id'];

    public function veiculos()
    {
        return $this->hasMany(VeiculoTerceiro::class, 'vct_clo_id', 'clo_id');
    }

    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class, 'clo_emp_id', 'id');
    }
}
