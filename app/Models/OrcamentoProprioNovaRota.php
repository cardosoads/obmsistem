<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrcamentoProprioNovaRota extends Model
{
    protected $table = 'orcamento_proprio_nova_rota';

    protected $fillable = [
        'orcamento_id',
        'nova_origem',
        'novo_destino',
        'km_nova_rota',
        'valor_km_nova_rota',
        'valor_total_nova_rota',
        'motivo_alteracao',
        'observacoes'
    ];

    protected $casts = [
        'km_nova_rota' => 'decimal:2',
        'valor_km_nova_rota' => 'decimal:2',
        'valor_total_nova_rota' => 'decimal:2',
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
     * Calcula o valor total da nova rota baseado em KM e valor por KM
     */
    public function calcularValorTotalNovaRota(): float
    {
        return $this->km_nova_rota * $this->valor_km_nova_rota;
    }

    /**
     * Accessor para descrição completa da nova rota
     */
    public function getNovaRotaCompletaAttribute(): string
    {
        return "{$this->nova_origem} → {$this->novo_destino}";
    }

    /**
     * Accessor para KM da nova rota formatado
     */
    public function getKmNovaRotaFormattedAttribute(): string
    {
        return number_format($this->km_nova_rota, 2, ',', '.') . ' km';
    }

    /**
     * Accessor para valor por KM da nova rota formatado
     */
    public function getValorKmNovaRotaFormattedAttribute(): string
    {
        return 'R$ ' . number_format($this->valor_km_nova_rota, 2, ',', '.');
    }

    /**
     * Accessor para valor total da nova rota formatado
     */
    public function getValorTotalNovaRotaFormattedAttribute(): string
    {
        return 'R$ ' . number_format($this->valor_total_nova_rota, 2, ',', '.');
    }
}
