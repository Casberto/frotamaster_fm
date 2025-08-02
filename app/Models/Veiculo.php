<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_empresa',
        'placa',
        'marca',
        'modelo',
        'ano_fabricacao',
        'ano_modelo',
        'cor',
        'chassi',
        'renavam',
        'tipo_veiculo',
        'tipo_combustivel',
        'data_aquisicao',
        'quilometragem_inicial', // <--- GARANTA QUE ESTA LINHA EXISTE
        'quilometragem_atual',
        'capacidade_tanque',
        'consumo_medio_fabricante',
        'consumo_medio_atual',
        'alerta_consumo_ativo',
        'status',
        'observacoes',
    ];

    /**
     * Define a relação: um veículo pertence a uma empresa.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    /**
     * Define a relação: um veículo pode ter muitas manutenções.
     */
    public function manutencoes()
    {
        return $this->hasMany(Manutencao::class, 'id_veiculo');
    }

    /**
     * Define a relação: um veículo pode ter muitos abastecimentos.
     */
    public function abastecimentos()
    {
        return $this->hasMany(Abastecimento::class, 'id_veiculo');
    }

    /**
     * Obtém o registro de abastecimento mais recente para o veículo.
     */
    public function ultimoAbastecimento()
    {
        return $this->hasOne(Abastecimento::class, 'id_veiculo')->latestOfMany('data_abastecimento');
    }

    public function getCustoTotalMensalAttribute()
    {
        return $this->custo_mensal_abastecimento + $this->custo_mensal_manutencao;
    }

    public function getCustoMensalAbastecimentoFormatadoAttribute()
    {
        return number_format($this->custo_mensal_abastecimento, 2, ',', '.');
    }

    public function getCustoMensalManutencaoFormatadoAttribute()
    {
        return number_format($this->custo_mensal_manutencao, 2, ',', '.');
    }

    public function getCustoTotalMensalFormatadoAttribute()
    {
        return number_format($this->custo_total_mensal, 2, ',', '.');
    }

}
