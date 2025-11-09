<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fornecedores';
    protected $primaryKey = 'for_id';

    protected $fillable = [
        'for_emp_id',
        'for_nome_fantasia',
        'for_razao_social',
        'for_cnpj_cpf',
        'for_tipo',
        'for_contato_email',
        'for_contato_telefone',
        'for_endereco',
        'for_observacoes',
        'for_ativo',
    ];


    protected $casts = [
        'for_ativo' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'for_id';
    }

    protected function tipoFormatado(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->for_tipo) {
                'oficina' => 'Oficina Mecânica',
                'posto' => 'Posto de Combustível',
                'ambos' => 'Oficina e Posto',
                'outro' => 'Outro',
                default => 'Não definido',
            }
        );
    }

    protected function statusFormatado(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->for_ativo ? 'Ativo' : 'Inativo'
        );
    }

    /**
     * Define o relacionamento com as Manutenções.
     */
    public function manutencoes(): HasMany
    {
        return $this->hasMany(Manutencao::class, 'man_for_id', 'for_id');
    }
    
    /**
     * Define o relacionamento com as Reservas (de manutenção).
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'res_for_id', 'for_id');
    }
}
