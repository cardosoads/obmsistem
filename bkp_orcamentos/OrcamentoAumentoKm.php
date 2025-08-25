<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrcamentoAumentoKm extends Model
{
    protected $table = 'orcamento_aumento_km';

    protected $fillable = [
        'orcamento_id',
        'km_dia',
        'qtd_dias',
        'km_total_mes',
        'combustivel_km_litro',
        'total_combustivel',
        'valor_combustivel',
        'hora_extra',
        'custo_total_combustivel_he',
        'lucro_percentual',
        'valor_lucro',
        'impostos_percentual',
        'valor_impostos',
        'valor_total',
        'observacoes'
    ];

    protected $casts = [
        'km_dia' => 'decimal:2',
        'km_total_mes' => 'decimal:2',
        'combustivel_km_litro' => 'decimal:2',
        'total_combustivel' => 'decimal:2',
        'valor_combustivel' => 'decimal:2',
        'hora_extra' => 'decimal:2',
        'custo_total_combustivel_he' => 'decimal:2',
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
     * Calcula o valor total adicional baseado em KM e valor por KM
     */
    public function calcularValorTotalAdicional(): float
    {
        return $this->km_adicional * $this->valor_km_adicional;
    }

    /**
     * Accessor para KM adicional formatado
     */
    public function getKmAdicionalFormattedAttribute(): string
    {
        return number_format($this->km_adicional, 2, ',', '.') . ' km';
    }

    /**
     * Accessor para valor por KM adicional formatado
     */
    public function getValorKmAdicionalFormattedAttribute(): string
    {
        return 'R$ ' . number_format($this->valor_km_adicional, 2, ',', '.');
    }

    /**
     * Accessor para valor total adicional formatado
     */
    public function getValorTotalAdicionalFormattedAttribute(): string
    {
        return 'R$ ' . number_format($this->valor_total_adicional, 2, ',', '.');
    }
}
