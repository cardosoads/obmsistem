<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrcamentoProprioNovaRota extends Model
{
    use HasFactory;

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
        'valor_total_nova_rota' => 'decimal:2'
    ];

    /**
     * Relacionamento com Orcamento
     */
    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    /**
     * Calcula o valor total da nova rota
     */
    public function calcularValorTotalNovaRota()
    {
        $km = $this->km_nova_rota ?? 0;
        $valorKm = $this->valor_km_nova_rota ?? 0;
        
        return $km * $valorKm;
    }

    /**
     * Accessor para rota completa
     */
    public function getNovaRotaCompletaAttribute()
    {
        $origem = $this->nova_origem ?? '';
        $destino = $this->novo_destino ?? '';
        
        if ($origem && $destino) {
            return $origem . ' → ' . $destino;
        }
        
        return $origem . $destino;
    }

    /**
     * Accessor para KM da nova rota formatado
     */
    public function getKmNovaRotaFormattedAttribute()
    {
        return number_format($this->km_nova_rota ?? 0, 2, ',', '.') . ' km';
    }

    /**
     * Accessor para valor por KM da nova rota formatado
     */
    public function getValorKmNovaRotaFormattedAttribute()
    {
        return 'R$ ' . number_format($this->valor_km_nova_rota ?? 0, 2, ',', '.');
    }

    /**
     * Accessor para valor total da nova rota formatado
     */
    public function getValorTotalNovaRotaFormattedAttribute()
    {
        return 'R$ ' . number_format($this->valor_total_nova_rota ?? 0, 2, ',', '.');
    }
}