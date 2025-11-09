<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ReservaManutencao extends Pivot
{
    /**
     * A tabela associada ao model pivô.
     *
     * @var string
     */
    protected $table = 'reserva_manutencoes';

    /**
     * A chave primária associada com a tabela.
     *
     * @var string
     */
    protected $primaryKey = 'rma_id';

    /**
     * Indica se o modelo deve ter timestamps (created_at, updated_at).
     *
     * @var bool
     */
    public $timestamps = false; // A migration só tem created_at

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rma_res_id',
        'rma_man_id',
        'rma_mot_id',
        'rma_emp_id',
    ];

    // ---
    // RELACIONAMENTOS (Opcionais no pivô, mas úteis)
    // ---

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class, 'rma_res_id', 'res_id');
    }

    public function manutencao(): BelongsTo
    {
        return $this->belongsTo(Manutencao::class, 'rma_man_id', 'man_id');
    }

    public function motorista(): BelongsTo
    {
        return $this->belongsTo(Motorista::class, 'rma_mot_id', 'mot_id');
    }
}
