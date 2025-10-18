<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfiguracaoEmpresa extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'configuracoes_empresas';

    /**
     * A chave primária para o model.
     *
     * @var string
     */
    protected $primaryKey = 'cfe_id';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cfe_emp_id',
        'cfe_cfp_id',
        'cfe_valor',
    ];

    /**
     * Define o relacionamento com a Empresa.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'cfe_emp_id');
    }

    /**
     * Define o relacionamento com a Configuração Padrão.
     */
    public function configuracaoPadrao(): BelongsTo
    {
        return $this->belongsTo(ConfiguracaoPadrao::class, 'cfe_cfp_id');
    }
}
