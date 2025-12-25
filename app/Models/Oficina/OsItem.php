<?php

namespace App\Models\Oficina;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OsItem extends Model
{
    use HasFactory;

    protected $table = 'os_itens';
    protected $primaryKey = 'osi_id';

    protected $guarded = ['osi_id'];

    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class, 'osi_osv_id', 'osv_id');
    }
}
