<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class VeiDocumento extends Model
{
    use HasFactory;

    protected $table = 'vei_documentos';
    protected $primaryKey = 'doc_id';

    protected $fillable = [
        'doc_vei_id',
        'doc_emp_id',
        'doc_tipo',
        'doc_descricao',
        'doc_path_arquivo',
        'doc_data_emissao',
        'doc_data_validade',
    ];

    protected $casts = [
        'doc_data_emissao' => 'date',
        'doc_data_validade' => 'date',
    ];

    protected $appends = ['tipo_texto'];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'doc_vei_id', 'vei_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'doc_emp_id', 'id');
    }

    /**
     * Retorna o texto correspondente ao tipo de documento.
     * 1-CRV, 2-CRLV, 3-Apólice Seguro, 4-Manual, 5-Outro
     */
    public function tipoTexto(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->doc_tipo) {
                1 => 'CRV',
                2 => 'CRLV',
                3 => 'Apólice Seguro',
                4 => 'Manual',
                5 => 'Outro',
                default => 'Desconhecido',
            }
        );
    }
}
