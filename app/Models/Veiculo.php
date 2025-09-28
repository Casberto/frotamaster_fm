<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Number;

class Veiculo extends Model
{
    use HasFactory;

    /**
     * A chave primária associada com a tabela.
     *
     * @var string
     */
    protected $primaryKey = 'vei_id';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vei_emp_id',
        'vei_user_id',
        'vei_segmento',
        'vei_placa',
        'vei_chassi',
        'vei_renavam',
        'vei_ano_fab',
        'vei_ano_mod',
        'vei_fabricante',
        'vei_modelo',
        'vei_tipo',
        'vei_especie',
        'vei_carroceria',
        'vei_combustivel',
        'vei_cor_predominante',
        'vei_potencia',
        'vei_cilindradas',
        'vei_num_motor',
        'vei_cap_tanque',
        'vei_km_inicial',
        'vei_km_atual',
        'vei_crv',
        'vei_data_licenciamento',
        'vei_venc_licenciamento',
        'vei_antt',
        'vei_tara',
        'vei_lotacao',
        'vei_pbt',
        'vei_data_aquisicao',
        'vei_valor_aquisicao',
        'vei_data_venda',
        'vei_valor_venda',
        'vei_status',
        'vei_obs',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'vei_data_aquisicao' => 'date',
        'vei_data_venda' => 'date',
        'vei_data_licenciamento' => 'date',
        'vei_venc_licenciamento' => 'date',
        'vei_valor_aquisicao' => 'decimal:2',
        'vei_valor_venda' => 'decimal:2',
        'vei_cap_tanque' => 'decimal:2',
    ];

    // ---
    // Bloco de Accessors (Atributos Calculados e Formatados)
    // ---

    /**
     * Retorna o texto correspondente ao status do veículo.
     */
    public function statusTexto(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->vei_status) {
                1 => 'Ativo',
                2 => 'Inativo',
                3 => 'Em Manutenção',
                4 => 'Vendido',
                default => 'Desconhecido',
            }
        );
    }

    /**
     * Retorna o texto correspondente ao tipo de combustível.
     */
    public function combustivelTexto(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->vei_combustivel) {
                1 => 'Gasolina',
                2 => 'Álcool',
                3 => 'Diesel',
                4 => 'GNV',
                5 => 'Elétrico',
                6 => 'Flex',
                default => 'Outro',
            }
        );
    }

    /**
     * Retorna uma string combinando placa e modelo.
     */
    public function placaModelo(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->vei_placa} - {$this->vei_modelo}"
        );
    }

    /**
     * Calcula o custo total mensal.
     * ATENÇÃO: Depende que os atributos 'custo_mensal_abastecimento' e 'custo_mensal_manutencao'
     * sejam adicionados ao modelo dinamicamente (geralmente no Controller).
     */
    public function custoTotalMensal(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->custo_mensal_abastecimento ?? 0) + ($this->custo_mensal_manutencao ?? 0)
        );
    }

    /**
     * Formata o custo mensal de abastecimento para o padrão brasileiro.
     */
    public function custoMensalAbastecimentoFormatado(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::format($this->custo_mensal_abastecimento ?? 0, precision: 2, locale: 'pt_BR')
        );
    }

    /**
     * Formata o custo mensal de manutenção para o padrão brasileiro.
     */
    public function custoMensalManutencaoFormatado(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::format($this->custo_mensal_manutencao ?? 0, precision: 2, locale: 'pt_BR')
        );
    }

    /**
     * Formata o custo total mensal para o padrão brasileiro.
     */
    public function custoTotalMensalFormatado(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::format($this->custo_total_mensal, precision: 2, locale: 'pt_BR')
        );
    }


    // ---
    // Bloco de Relacionamentos com outras Models.
    // ---

    /**
     * Define o relacionamento com a Empresa.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'vei_emp_id', 'id');
    }

    /**
     * Define o relacionamento com o Usuário que cadastrou.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vei_user_id', 'id');
    }

    /**
     * Define o relacionamento com os Abastecimentos.
     */
    public function abastecimentos(): HasMany
    {
        return $this->hasMany(Abastecimento::class, 'id_veiculo', 'vei_id');
    }

    /**
     * Obtém o registro de abastecimento mais recente para o veículo.
     */
    public function ultimoAbastecimento(): HasOne
    {
        // Chave estrangeira em Abastecimento -> Chave local em Veiculo
        return $this->hasOne(Abastecimento::class, 'id_veiculo', 'vei_id')->latestOfMany('data_abastecimento');
    }

    /**
     * Define o relacionamento com as Manutenções.
     */
    public function manutencoes(): HasMany
    {
        return $this->hasMany(Manutencao::class, 'id_veiculo', 'vei_id');
    }

    /**
     * Define o relacionamento com os Documentos do Veículo.
     */
    public function documentos(): HasMany
    {
        return $this->hasMany(VeiDocumento::class, 'doc_vei_id', 'vei_id');
    }
}

