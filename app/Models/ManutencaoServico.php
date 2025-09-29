<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * ManutencaoServico Model
 *
 * Este model representa a tabela pivô 'manutencao_servico',
 * que conecta as manutenções aos seus respectivos serviços,
 * armazenando também informações adicionais como o custo e
 * a garantia de cada serviço específico dentro da manutenção.
 */
class ManutencaoServico extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'manutencao_servico';

    /**
     * Indicates if the IDs are auto-incrementing.
     * A tabela pivô tem uma chave primária 'id'.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ms_man_id',
        'ms_ser_id',
        'ms_custo',
        'ms_garantia',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ms_garantia' => 'date',
        'ms_custo' => 'decimal:2',
    ];

    /**
     * Define o relacionamento com a Manutenção principal.
     */
    public function manutencao(): BelongsTo
    {
        return $this->belongsTo(Manutencao::class);
    }
}
