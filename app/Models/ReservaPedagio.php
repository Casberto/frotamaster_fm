<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservaPedagio extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'reserva_pedagios';

    /**
     * A chave primária associada com a tabela.
     *
     * @var string
     */
    protected $primaryKey = 'rpe_id';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rpe_res_id',
        'rpe_desc',
        'rpe_valor',
        'rpe_forma_pagto',
        'rpe_reembolso',
        'rpe_data_hora',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rpe_valor' => 'decimal:2',
        'rpe_reembolso' => 'boolean',
        'rpe_data_hora' => 'datetime',
    ];

    /**
     * Relação: Um pedágio pertence a uma Reserva.
     */
    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class, 'rpe_res_id', 'res_id');
    }
}
