<?php

namespace App\Models\Oficina;

use App\Models\Oficina\OsHistorico;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OrdemServico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ordens_servico';
    protected $primaryKey = 'osv_id';

    protected $guarded = ['osv_id'];

    protected $casts = [
        'osv_checklist_entrada' => 'array',
        'osv_data_entrada' => 'datetime',
        'osv_previsao_entrega' => 'datetime',
        'osv_data_saida' => 'datetime',
        'osv_vencimento_garantia' => 'date',
        'osv_gerar_orcamento' => 'boolean',
        'osv_data_pagamento' => 'date',
        'osv_data_compensacao' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->osv_token_acesso)) {
                $model->osv_token_acesso = (string) Str::uuid();
            }
        });
    }

    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class, 'osv_emp_id', 'id');
    }

    public function veiculo()
    {
        return $this->belongsTo(VeiculoTerceiro::class, 'osv_vct_id', 'vct_id');
    }

    public function historico(): HasMany
    {
        return $this->hasMany(OsHistorico::class, 'osh_osv_id', 'osv_id')->orderBy('created_at', 'desc');
    }

    public function registrarHistorico($acao, $descricao = null, $userId = null)
    {
        $this->historico()->create([
            'osh_user_id' => $userId ?? auth()->id(),
            'osh_acao' => $acao,
            'osh_descricao' => $descricao,
        ]);
    }

    public function itens()
    {
        return $this->hasMany(OsItem::class, 'osi_osv_id', 'osv_id');
    }

    public function atualizarFinanceiro()
    {
        // Carrega os itens atualizados
        $this->load('itens');

        $totalPecas = 0;
        $totalServicos = 0;
        $custoTotal = 0;

        foreach ($this->itens as $item) {
            $subtotalVenda = $item->osi_quantidade * $item->osi_valor_venda_unit;
            $subtotalCusto = $item->osi_quantidade * $item->osi_valor_custo_unit;

            if ($item->osi_tipo === 'peca') {
                $totalPecas += $subtotalVenda;
            } else {
                $totalServicos += $subtotalVenda;
            }

            $custoTotal += $subtotalCusto;
        }

        $this->update([
            'osv_valor_pecas' => $totalPecas,
            'osv_valor_mao_obra' => $totalServicos,
            'osv_valor_total' => $totalPecas + $totalServicos,
            'osv_valor_custo_total' => $custoTotal,
        ]);
    }
    public function osPai()
    {
        return $this->belongsTo(OrdemServico::class, 'osv_pai_id', 'osv_id');
    }

    public function garantias()
    {
        return $this->hasMany(OrdemServico::class, 'osv_pai_id', 'osv_id');
    }

    public function scopeEmGarantia($query)
    {
        return $query->where('osv_status', 'entregue')
                     ->whereNotNull('osv_vencimento_garantia')
                     ->where('osv_vencimento_garantia', '>=', now());
    }

    public function estaEmGarantia(): bool
    {
        return $this->osv_vencimento_garantia && $this->osv_vencimento_garantia->isFuture();
    }
}
