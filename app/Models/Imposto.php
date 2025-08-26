<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Imposto extends Model
{
    use HasFactory;

    protected $table = 'impostos';

    protected $fillable = [
        'nome',
        'descricao',
        'percentual',
        'ativo'
    ];

    protected $casts = [
        'percentual' => 'decimal:2',
        'ativo' => 'boolean'
    ];

    /**
     * Relacionamento many-to-many com grupos de impostos
     */
    public function gruposImpostos(): BelongsToMany
    {
        return $this->belongsToMany(GrupoImposto::class, 'grupo_imposto_imposto');
    }

    /**
     * Scope para buscar apenas impostos ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Calcula o valor do imposto sobre um valor base
     */
    public function calcularValor(float $valorBase): float
    {
        return ($valorBase * $this->percentual) / 100;
    }

    /**
     * Formata o percentual para exibição
     */
    public function getPercentualFormatadoAttribute(): string
    {
        return number_format($this->percentual, 2, ',', '.') . '%';
    }

    /**
     * Retorna o status formatado
     */
    public function getStatusAttribute(): string
    {
        return $this->ativo ? 'Ativo' : 'Inativo';
    }

    /**
     * Retorna a classe CSS para o status
     */
    public function getStatusClassAttribute(): string
    {
        return $this->ativo ? 'badge-success' : 'badge-secondary';
    }
}