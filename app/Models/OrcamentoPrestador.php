<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrcamentoPrestador extends Model
{
    protected $table = 'orcamento_prestador';

    protected $fillable = [
        'orcamento_id',
        'fornecedor_omie_id',
        'fornecedor_nome',
        'valor_referencia',
        'qtd_dias',
        'custo_fornecedor',
        'lucro_percentual',
        'valor_lucro',
        'impostos_percentual',
        'valor_impostos',
        'valor_total',
        'observacoes'
    ];

    protected $casts = [
        'valor_referencia' => 'decimal:2',
        'custo_fornecedor' => 'decimal:2',
        'lucro_percentual' => 'decimal:2',
        'valor_lucro' => 'decimal:2',
        'impostos_percentual' => 'decimal:2',
        'valor_impostos' => 'decimal:2',
        'valor_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relacionamento com orçamento
     */
    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }

    /**
     * Dados do fornecedor (integração OMIE)
     * Não há mais relacionamento direto, dados vêm da API OMIE
     */

    /**
     * Relacionamento com marca
     */
    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    /**
     * Calcula o valor total baseado em KM e valor por KM
     */
    public function calcularValorTotal(): float
    {
        return $this->km_total * $this->valor_km;
    }

    /**
     * Accessor para descrição completa do veículo
     */
    public function getVeiculoCompletoAttribute(): string
    {
        return "{$this->marca->name} {$this->modelo_veiculo} {$this->ano_veiculo} - {$this->placa_veiculo}";
    }

    /**
     * Accessor para valor por KM formatado
     */
    public function getValorKmFormattedAttribute(): string
    {
        return 'R$ ' . number_format($this->valor_km, 2, ',', '.');
    }

    /**
     * Accessor para valor total formatado
     */
    public function getValorTotalFormattedAttribute(): string
    {
        return 'R$ ' . number_format($this->valor_total, 2, ',', '.');
    }
}
