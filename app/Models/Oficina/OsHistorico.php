<?php

namespace App\Models\Oficina;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OsHistorico extends Model
{
    protected $table = 'os_historico';
    protected $primaryKey = 'osh_id';

    protected $fillable = [
        'osh_osv_id',
        'osh_user_id',
        'osh_acao',
        'osh_descricao',
    ];

    public function os(): BelongsTo
    {
        return $this->belongsTo(OrdemServico::class, 'osh_osv_id', 'osv_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'osh_user_id', 'id');
    }
}
