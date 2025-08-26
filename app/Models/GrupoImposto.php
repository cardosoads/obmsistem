<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection;

class GrupoImposto extends Model
{
    use HasFactory;

    protected $table = 'grupos_impostos';

    protected $fillable = [
        'nome',
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    /**
     * Relacionamento many-to-many com impostos
     */
    public function impostos(): BelongsToMany
    {
        return $this->belongsToMany(Imposto::class, 'grupo_imposto_imposto');
    }

    /**
     * Relacionamento com impostos ativos apenas
     */
    public function impostosAtivos(): BelongsToMany
    {
        return $this->impostos()->where('impostos.ativo', true);
    }

    /**
     * Scope para buscar apenas grupos ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Calcula o percentual total dos impostos do grupo
     */
    public function getPercentualTotalAttribute(): float
    {
        return $this->impostosAtivos->sum('percentual');
    }

    /**
     * Calcula o valor total dos impostos do grupo sobre um valor base
     */
    public function calcularValorTotal(float $valorBase): float
    {
        $valorTotal = 0;
        
        foreach ($this->impostosAtivos as $imposto) {
            $valorTotal += $imposto->calcularValor($valorBase);
        }
        
        return $valorTotal;
    }

    /**
     * Retorna o percentual total formatado
     */
    public function getPercentualTotalFormatadoAttribute(): string
    {
        return number_format($this->percentual_total, 2, ',', '.') . '%';
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

    /**
     * Retorna a quantidade de impostos ativos no grupo
     */
    public function getQuantidadeImpostosAttribute(): int
    {
        return $this->impostosAtivos->count();
    }

    /**
     * Retorna detalhes dos impostos para exibição
     */
    public function getDetalhesImpostosAttribute(): Collection
    {
        return $this->impostosAtivos->map(function ($imposto) {
            return [
                'id' => $imposto->id,
                'nome' => $imposto->nome,
                'percentual' => $imposto->percentual,
                'percentual_formatado' => $imposto->percentual_formatado
            ];
        });
    }

    /**
     * Calcula breakdown detalhado dos impostos sobre um valor base
     */
    public function calcularBreakdown(float $valorBase): array
    {
        $breakdown = [];
        $totalImpostos = 0;
        
        foreach ($this->impostosAtivos as $imposto) {
            $valorImposto = $imposto->calcularValor($valorBase);
            $totalImpostos += $valorImposto;
            
            $breakdown[] = [
                'imposto_id' => $imposto->id,
                'nome' => $imposto->nome,
                'percentual' => $imposto->percentual,
                'valor' => $valorImposto,
                'valor_formatado' => 'R$ ' . number_format($valorImposto, 2, ',', '.')
            ];
        }
        
        return [
            'impostos' => $breakdown,
            'total_impostos' => $totalImpostos,
            'total_formatado' => 'R$ ' . number_format($totalImpostos, 2, ',', '.'),
            'valor_com_impostos' => $valorBase + $totalImpostos,
            'valor_com_impostos_formatado' => 'R$ ' . number_format($valorBase + $totalImpostos, 2, ',', '.')
        ];
    }
}