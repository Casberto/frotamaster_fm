<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $table = 'perfis';
    protected $primaryKey = 'per_id';

    protected $fillable = [
        'per_emp_id',
        'per_nome',
        'per_descricao',
        'per_status',
    ];

    public function getRouteKeyName()
    {
        return 'per_id';
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'per_emp_id');
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'usuario_perfis', 'usp_per_id', 'usp_usr_id');
    }

    public function permissoes()
    {
        return $this->belongsToMany(Permissao::class, 'perfil_permissoes', 'ppr_per_id', 'ppr_prm_id');
    }
}
