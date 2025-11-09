<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\Pivot; // Import Pivot

class Reserva extends Model
{
    use HasFactory;

    protected $primaryKey = 'res_id';

    protected $fillable = [
        'res_emp_id',
        'res_vei_id',
        'res_sol_id',
        'res_mot_id',
        'res_for_id',
        'res_tipo',
        'res_data_inicio',
        'res_data_fim',
        'res_dia_todo',
        'res_origem',
        'res_destino',
        'res_just',
        'res_obs',
        'res_status',
        'res_km_inicio',
        'res_km_fim',
        'res_comb_inicio',
        'res_comb_fim',
        'res_hora_saida',
        'res_hora_chegada',
        'res_obs_finais',
        'res_revisor_id', // <-- Novo
        'res_data_revisao', // <-- Novo
        'res_obs_revisor', // <-- Novo
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'res_data_inicio' => 'datetime',
        'res_data_fim' => 'datetime',
        'res_dia_todo' => 'boolean',
        'res_hora_saida' => 'datetime',
        'res_hora_chegada' => 'datetime',
        'res_data_revisao' => 'datetime', // <-- Novo
    ];

    /**
     * Boot the model.
     * Define valores padrão para empresa, solicitante e criador.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reserva) {
            if (Auth::check()) {
                $reserva->res_emp_id = $reserva->res_emp_id ?: Auth::user()->id_empresa;
                $reserva->res_sol_id = $reserva->res_sol_id ?: Auth::id();
                $reserva->created_by = $reserva->created_by ?: Auth::id();
                // Status inicial sempre pendente ao criar
                $reserva->res_status = 'pendente';
            }
        });

        static::updating(function ($reserva) {
            if (Auth::check()) {
                $reserva->updated_by = $reserva->updated_by ?: Auth::id();
            }
        });
    }

    // --- Relacionamentos ---

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'res_emp_id', 'id');
    }

    public function veiculo(): BelongsTo
    {
        return $this->belongsTo(Veiculo::class, 'res_vei_id', 'vei_id');
    }

    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'res_sol_id', 'id');
    }

    public function motorista(): BelongsTo
    {
        return $this->belongsTo(Motorista::class, 'res_mot_id', 'mot_id');
    }

    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class, 'res_for_id', 'for_id');
    }

     /**
     * Relação com o usuário que revisou/encerrou a reserva.
     */
    public function revisor(): BelongsTo // <-- Nova Relação
    {
        return $this->belongsTo(User::class, 'res_revisor_id', 'id');
    }

    public function pedagios(): HasMany
    {
        return $this->hasMany(ReservaPedagio::class, 'rpe_res_id', 'res_id');
    }

    public function passageiros(): HasMany
    {
        return $this->hasMany(ReservaPassageiro::class, 'rpa_res_id', 'res_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(ReservaAuditLog::class, 'ral_res_id', 'res_id');
    }

     /**
     * Relação Muitos-para-Muitos com Abastecimentos, usando a classe Pivot customizada.
     */
    public function abastecimentos(): BelongsToMany
    {
        return $this->belongsToMany(Abastecimento::class, 'reserva_abastecimentos', 'rab_res_id', 'rab_abs_id')
                    ->using(ReservaAbastecimento::class) // Especifica a classe Pivot
                    ->withPivot('rab_mot_id', 'rab_emp_id', 'rab_forma_pagto', 'rab_reembolso', 'created_at');
    }

     /**
     * Relação Muitos-para-Muitos com Manutenções, usando a classe Pivot customizada.
     */
    public function manutencoes(): BelongsToMany
    {
        return $this->belongsToMany(Manutencao::class, 'reserva_manutencoes', 'rma_res_id', 'rma_man_id')
                    ->using(ReservaManutencao::class) // Especifica a classe Pivot
                    ->withPivot('rma_mot_id', 'rma_emp_id', 'created_at');
    }

}

