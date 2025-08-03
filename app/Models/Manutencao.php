<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manutencao extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'manutencoes'; // Adicione esta linha

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_veiculo',
        'id_empresa',
        'id_user',
        'tipo_manutencao',
        'descricao_servico',
        'data_manutencao',
        'quilometragem',
        'custo_total',
        'custo_previsto',
        'nome_fornecedor',
        'responsavel',
        'observacoes',
        'proxima_revisao_data',
        'proxima_revisao_km',
        'status',
    ];

    /**
     * Define a relação com o Veículo.
     */
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'id_veiculo');
    }

    /**
     * Define a relação com a Empresas.
     */
    public function empresa()
    {
        return $this->belongsTo(Veiculo::class, 'id_empresa');
    }

    /**
     * Define a relação com o Usuário.
     */
    public function user()
    {
        return $this->belongsTo(Veiculo::class, 'id_user');
    }
}
