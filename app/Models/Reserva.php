<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reserva extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'res_id';

    protected $fillable = [
        'res_emp_id',
        'res_codigo',
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
        'res_revisor_id',
        'res_data_revisao',
        'res_obs_revisor',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'res_data_inicio' => 'datetime',
        'res_data_fim' => 'datetime',
        'res_dia_todo' => 'boolean',
        'res_hora_saida' => 'datetime',
        'res_hora_chegada' => 'datetime',
        'res_data_revisao' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reserva) {
            if (Auth::check()) {
                $user = Auth::user();
                $reserva->res_emp_id = $reserva->res_emp_id ?: $user->id_empresa;
                $reserva->res_sol_id = $reserva->res_sol_id ?: $user->id;
                $reserva->created_by = $reserva->created_by ?: $user->id;
                
                if (!$reserva->res_status) {
                    $reserva->res_status = 'pendente';
                }

                if (!$reserva->res_codigo) {
                    $maxCodigo = static::where('res_emp_id', $reserva->res_emp_id)->max('res_codigo');
                    $reserva->res_codigo = $maxCodigo ? $maxCodigo + 1 : 1;
                }
            }
        });

        static::updating(function ($reserva) {
            if (Auth::check()) {
                $reserva->updated_by = Auth::id();
            }
        });
    }

    public function getTituloAttribute(): string
    {
        $placa = 'Veículo a definir';
        if ($this->relationLoaded('veiculo') && $this->veiculo) {
            $placa = $this->veiculo->vei_placa;
        }
        return "#{$this->res_codigo} - " . $placa;
    }

    public function isPendente() { return $this->res_status === 'pendente'; }
    public function isAprovada() { return $this->res_status === 'aprovada'; }
    public function isEmUso() { return $this->res_status === 'em_uso'; }
    public function isEmRevisao() { return $this->res_status === 'em_revisao'; }
    public function isEncerrada() { return $this->res_status === 'encerrada'; }
    public function isCancelada() { return $this->res_status === 'cancelada'; }
    public function isRejeitada() { return $this->res_status === 'rejeitada'; }

    public function empresa(): BelongsTo { return $this->belongsTo(Empresa::class, 'res_emp_id', 'id'); }
    public function veiculo(): BelongsTo { return $this->belongsTo(Veiculo::class, 'res_vei_id', 'vei_id'); }
    public function solicitante(): BelongsTo { return $this->belongsTo(User::class, 'res_sol_id', 'id'); }
    public function motorista(): BelongsTo { return $this->belongsTo(Motorista::class, 'res_mot_id', 'mot_id'); }
    public function fornecedor(): BelongsTo { return $this->belongsTo(Fornecedor::class, 'res_for_id', 'for_id'); }
    public function revisor(): BelongsTo { return $this->belongsTo(User::class, 'res_revisor_id', 'id'); }
    
    public function pedagios(): HasMany { return $this->hasMany(ReservaPedagio::class, 'rpe_res_id', 'res_id'); }
    public function passageiros(): HasMany { return $this->hasMany(ReservaPassageiro::class, 'rpa_res_id', 'res_id'); }
    public function auditLogs(): HasMany { return $this->hasMany(ReservaAuditLog::class, 'ral_res_id', 'res_id'); }

    public function abastecimentos(): BelongsToMany
    {
        return $this->belongsToMany(Abastecimento::class, 'reserva_abastecimentos', 'rab_res_id', 'rab_abs_id')
                    ->using(ReservaAbastecimento::class)
                    ->withPivot('rab_mot_id', 'rab_emp_id', 'rab_forma_pagto', 'rab_reembolso', 'created_at');
    }

    public function manutencoes(): BelongsToMany
    {
        return $this->belongsToMany(Manutencao::class, 'reserva_manutencoes', 'rma_res_id', 'rma_man_id')
                    ->using(ReservaManutencao::class)
                    ->withPivot('rma_mot_id', 'rma_emp_id', 'created_at');
    }
}