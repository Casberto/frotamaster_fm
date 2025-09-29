<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manutencao extends Model
{
    use HasFactory;

    protected $table = 'manutencoes';
    protected $primaryKey = 'man_id';

    protected $fillable = [
        'man_vei_id',
        'man_emp_id',
        'man_user_id',
        'man_for_id',
        'man_tipo',
        'man_data_inicio',
        'man_data_fim',
        'man_km',
        'man_custo_previsto',
        'man_custo_pecas',
        'man_custo_mao_de_obra',
        'man_custo_total',
        'man_responsavel',
        'man_nf',
        'man_garantia',
        'man_observacoes',
        'man_prox_revisao_data',
        'man_prox_revisao_km',
        'man_status',
    ];

    /**
     * Relação: Uma manutenção pertence a um Veículo.
     */
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'man_vei_id');
    }

    /**
     * Relação: Uma manutenção pertence a um Fornecedor.
     */
    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'man_for_id');
    }

    /**
     * Relação: Uma manutenção é registrada por um Usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'man_user_id');
    }

    /**
     * Relação: Uma manutenção pode ter vários Serviços (Muitos para Muitos).
     */
    public function servicos()
    {
        return $this->belongsToMany(Servico::class, 'manutencao_servico', 'ms_man_id', 'ms_ser_id')
            ->withPivot('ms_custo')
            ->withTimestamps();
    }

    /**
     * Define a relação com a Empresas.
     */
    public function empresa()
    {
        return $this->belongsTo(Veiculo::class, 'id_empresa');
    }

}
