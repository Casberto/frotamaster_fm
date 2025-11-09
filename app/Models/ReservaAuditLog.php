<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservaAuditLog extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'reservas_audit_logs';

    /**
     * A chave primária associada com a tabela.
     *
     * @var string
     */
    protected $primaryKey = 'ral_id';

    /**
     * Indica se o modelo deve ter timestamps (created_at, updated_at).
     *
     * @var bool
     */
    public $timestamps = false; // A migration só tem created_at (definido como useCurrent())

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ral_res_id',
        'ral_user_id',
        'ral_acao',
        'ral_before_json',
        'ral_after_json',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ral_before_json' => 'array',
        'ral_after_json' => 'array',
    ];

    /**
     * Relação: Um log pertence a uma Reserva.
     */
    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class, 'ral_res_id', 'res_id');
    }

    /**
     * Relação: Um log foi gerado por um Usuário.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ral_user_id', 'id');
    }
}
