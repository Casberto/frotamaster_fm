<?php
// app/Models/Licenca.php (MODIFICADO)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licenca extends Model
{
    use HasFactory;

    protected $table = 'licencas';

    protected $fillable = [
        'id_empresa',
        'plano', // Modificado de id_plano
        'id_usuario_criador',
        'valor_pago',
        'data_inicio',
        'data_vencimento',
        'status',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_vencimento' => 'date',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    // A relação plano() foi removida

    public function criador()
    {
        return $this->belongsTo(User::class, 'id_usuario_criador');
    }
}