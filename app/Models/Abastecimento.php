<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abastecimento extends Model
{
    use HasFactory;

    protected $primaryKey = 'aba_id';

    protected $fillable = [
        'aba_emp_id',
        'aba_user_id',
        'aba_vei_id',
        'aba_for_id',
        'aba_data',
        'aba_km',
        'aba_und_med',
        'aba_qtd',
        'aba_vlr_und',
        'aba_vlr_tot',
        'aba_combustivel',
        'aba_tanque_cheio',
        'aba_tanque_inicio',
        'aba_pneus_calibrados',
        'aba_agua_verificada',
        'aba_oleo_verificado',
        'aba_obs',
    ];

    protected $casts = [
        'aba_data' => 'date',
        'aba_tanque_cheio' => 'boolean',
        'aba_pneus_calibrados' => 'boolean',
        'aba_agua_verificada' => 'boolean',
        'aba_oleo_verificado' => 'boolean',
        'aba_qtd' => 'decimal:3',
        'aba_vlr_und' => 'decimal:3',
        'aba_vlr_tot' => 'decimal:2',
    ];

/**
     * Define a relação: um abastecimento pertence a um Veículo.
     */
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'aba_vei_id', 'vei_id');
    }

    /**
     * Define a relação: um abastecimento pertence a uma Empresa.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'aba_emp_id', 'id');
    }

    /**
     * Define a relação: um abastecimento é registrado por um Usuário.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aba_user_id', 'id');
    }

    /**
     * Define a relação: um abastecimento foi feito em um Fornecedor (posto).
     */
    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class, 'aba_for_id', 'for_id');
    }
}
