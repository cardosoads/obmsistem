<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imposto extends Model
{
    protected $fillable = [
        'name',
        'percentual',
        'description',
        'active'
    ];

    protected $casts = [
        'percentual' => 'decimal:2',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Scope para impostos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Calcula o valor do imposto sobre um valor base
     */
    public function calcularImposto(float $valorBase): float
    {
        return ($valorBase * $this->percentual) / 100;
    }

    /**
     * Accessor para percentual formatado
     */
    public function getFormattedPercentualAttribute(): string
    {
        return number_format($this->percentual, 2, ',', '.') . '%';
    }
}
