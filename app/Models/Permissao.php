<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
    use HasFactory;

    protected $table = 'permissoes';
    protected $primaryKey = 'prm_id';

    protected $fillable = [
        'prm_codigo',
        'prm_modulo',
        'prm_acao',
        'prm_descricao',
    ];

    public function perfis()
    {
        return $this->belongsToMany(Perfil::class, 'perfil_permissoes', 'ppr_prm_id', 'ppr_per_id');
    }
}
