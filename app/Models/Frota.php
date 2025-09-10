<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Frota extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tipo_veiculo_id',
        'fipe',
        'percentual_fipe',
        'aluguel_carro',
        'rastreador',
        'provisoes_avarias',
        'provisao_desmobilizacao',
        'provisao_diaria_rac',
        'custo_total',
        'active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'fipe' => 'decimal:2',
        'percentual_fipe' => 'decimal:2',
        'aluguel_carro' => 'decimal:2',
        'rastreador' => 'decimal:2',
        'provisoes_avarias' => 'decimal:2',
        'provisao_desmobilizacao' => 'decimal:2',
        'provisao_diaria_rac' => 'decimal:2',
        'custo_total' => 'decimal:2',
        'active' => 'boolean',
    ];

    /**
     * Relacionamento com tipo de veículo
     */
    public function tipoVeiculo(): BelongsTo
    {
        return $this->belongsTo(TipoVeiculo::class);
    }

    /**
     * Scope para frotas ativas
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para filtrar por tipo de veículo
     */
    public function scopeByTipoVeiculo($query, $tipoVeiculoId)
    {
        return $query->where('tipo_veiculo_id', $tipoVeiculoId);
    }

    /**
     * Calcula o custo total da frota
     */
    public function calcularCustoTotal()
    {
        $valorFipeComPercentual = $this->fipe + ($this->fipe * $this->percentual_fipe / 100);
        
        $this->custo_total = $valorFipeComPercentual + 
                           $this->aluguel_carro + 
                           $this->rastreador + 
                           $this->provisoes_avarias + 
                           $this->provisao_desmobilizacao + 
                           $this->provisao_diaria_rac;
        
        return $this->custo_total;
    }

    /**
     * Accessor para valor Fipe com percentual
     */
    public function getValorFipeComPercentualAttribute()
    {
        return $this->fipe + ($this->fipe * $this->percentual_fipe / 100);
    }

    /**
     * Accessor para status formatado
     */
    public function getStatusFormatadoAttribute()
    {
        return $this->active ? 'Ativo' : 'Inativo';
    }

    /**
     * Accessor para custo total formatado
     */
    public function getCustoTotalFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->custo_total, 2, ',', '.');
    }

    /**
     * Boot method para calcular custo total automaticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($frota) {
            $frota->calcularCustoTotal();
        });
    }
}
