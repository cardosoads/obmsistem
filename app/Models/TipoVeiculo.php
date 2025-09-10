<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoVeiculo extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'tipos_veiculos';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'codigo',
        'consumo_km_litro',
        'tipo_combustivel',
        'descricao',
        'active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'consumo_km_litro' => 'decimal:2',
        'active' => 'boolean',
    ];

    /**
     * Relacionamento com frotas
     */
    public function frotas(): HasMany
    {
        return $this->hasMany(Frota::class);
    }

    /**
     * Scope para tipos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para filtrar por tipo de combustível
     */
    public function scopeByTipoCombustivel($query, $tipo)
    {
        return $query->where('tipo_combustivel', $tipo);
    }

    /**
     * Accessor para formatar o consumo
     */
    public function getConsumoFormatadoAttribute()
    {
        return number_format($this->consumo_km_litro, 2, ',', '.') . ' km/l';
    }

    /**
     * Accessor para status formatado
     */
    public function getStatusFormatadoAttribute()
    {
        return $this->active ? 'Ativo' : 'Inativo';
    }
}
