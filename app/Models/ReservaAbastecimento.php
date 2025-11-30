<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ReservaAbastecimento extends Pivot
{
    /**
     * A tabela associada ao model pivô.
     *
     * @var string
     */
    protected $table = 'reserva_abastecimentos';

    /**
     * A chave primária associada com a tabela.
     *
     * @var string
     */
    protected $primaryKey = 'rab_id';

    /**
     * Indica se o modelo deve ter timestamps (created_at, updated_at).
     *
     * @var bool
     */
    public $timestamps = false; // A migration só tem created_at
    const UPDATED_AT = null;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rab_res_id',
        'rab_abs_id',
        'rab_mot_id',
        'rab_emp_id',
        'rab_forma_pagto',
        'rab_reembolso',
        'created_at',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rab_reembolso' => 'boolean',
    ];

    // ---
    // RELACIONAMENTOS (Opcionais no pivô, mas úteis)
    // ---

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class, 'rab_res_id', 'res_id');
    }

    public function abastecimento(): BelongsTo
    {
        return $this->belongsTo(Abastecimento::class, 'rab_abs_id', 'aba_id');
    }

    public function motorista(): BelongsTo
    {
        return $this->belongsTo(Motorista::class, 'rab_mot_id', 'mot_id');
    }
}
