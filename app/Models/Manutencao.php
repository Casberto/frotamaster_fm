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
        'tipo_manutencao',
        'descricao_servico',
        'data_manutencao',
        'quilometragem',
        'custo_total',
        'nome_fornecedor',
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
}
