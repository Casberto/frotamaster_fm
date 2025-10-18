<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConfiguracaoPadrao extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'configuracoes_padrao';

    /**
     * A chave primária para o model.
     *
     * @var string
     */
    protected $primaryKey = 'cfp_id';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cfp_modulo',
        'cfp_chave',
        'cfp_valor',
        'cfp_tipo',
        'cfp_descricao',
    ];

    /**
     * Define o relacionamento com as configurações das empresas.
     */
    public function configuracoesEmpresa(): HasMany
    {
        return $this->hasMany(ConfiguracaoEmpresa::class, 'cfe_cfp_id');
    }
}
