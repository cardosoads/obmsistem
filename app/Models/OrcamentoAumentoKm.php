<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrcamentoAumentoKm extends Model
{
    use HasFactory;

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
        'pedagio',
        'custo_total_combustivel_he',
        'lucro_percentual',
        'valor_lucro',
        'impostos_percentual',
        'valor_impostos',
        'grupo_imposto_id',
        'valor_total',
        'observacoes'
    ];

    protected $casts = [
        'km_dia' => 'decimal:2',
        'qtd_dias' => 'integer',
        'km_total_mes' => 'decimal:2',
        'combustivel_km_litro' => 'decimal:2',
        'total_combustivel' => 'decimal:2',
        'valor_combustivel' => 'decimal:2',
        'hora_extra' => 'decimal:2',
        'pedagio' => 'decimal:2',
        'custo_total_combustivel_he' => 'decimal:2',
        'lucro_percentual' => 'decimal:2',
        'valor_lucro' => 'decimal:2',
        'impostos_percentual' => 'decimal:2',
        'valor_impostos' => 'decimal:2',
        'valor_total' => 'decimal:2'
    ];

    /**
     * Relacionamento com Orcamento
     */
    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    /**
     * Relacionamento com GrupoImposto
     */
    public function grupoImposto()
    {
        return $this->belongsTo(GrupoImposto::class);
    }

    /**
     * Calcula o valor total adicional
     */
    public function calcularValorTotalAdicional()
    {
        $custoTotal = $this->custo_total_combustivel_he ?? 0;
        $valorLucro = $this->valor_lucro ?? 0;
        $valorImpostos = $this->valor_impostos ?? 0;
        
        return $custoTotal + $valorLucro + $valorImpostos;
    }

    /**
     * Accessor para KM adicional formatado
     */
    public function getKmAdicionalAttribute()
    {
        return number_format($this->km_total_mes ?? 0, 2, ',', '.');
    }

    /**
     * Accessor para valor KM adicional formatado
     */
    public function getValorKmAdicionalAttribute()
    {
        return 'R$ ' . number_format($this->valor_combustivel ?? 0, 2, ',', '.');
    }

    /**
     * Accessor para valor total adicional formatado
     */
    public function getValorTotalAdicionalAttribute()
    {
        return 'R$ ' . number_format($this->valor_total ?? 0, 2, ',', '.');
    }

    /**
     * Calcula o KM total do mês
     */
    public function calcularKmTotalMes(): float
    {
        return $this->km_dia * $this->qtd_dias;
    }

    /**
     * Calcula o total de combustível necessário
     */
    public function calcularTotalCombustivel(): float
    {
        if ($this->combustivel_km_litro > 0) {
            return $this->km_total_mes / $this->combustivel_km_litro;
        }
        return 0;
    }

    /**
     * Calcula o custo total de combustível + hora extra + pedágio
     */
    public function calcularCustoTotalCombustivelHe(): float
    {
        return ($this->total_combustivel * $this->valor_combustivel) + $this->hora_extra + $this->pedagio;
    }

    /**
     * Calcula o valor do lucro
     */
    public function calcularValorLucro(): float
    {
        return $this->custo_total_combustivel_he * ($this->lucro_percentual / 100);
    }

    /**
     * Calcula o subtotal (custo + lucro)
     */
    public function calcularSubtotal(): float
    {
        return $this->custo_total_combustivel_he + $this->valor_lucro;
    }

    /**
     * Calcula o valor dos impostos sobre o subtotal
     */
    public function calcularValorImpostos(): float
    {
        $subtotal = $this->calcularSubtotal();
        
        // Se há um grupo de imposto selecionado, usa o percentual do grupo
        if ($this->grupo_imposto_id && $this->grupoImposto) {
            return $this->grupoImposto->calcularValorTotal($subtotal);
        }
        
        // Caso contrário, usa o percentual direto
        return $subtotal * ($this->impostos_percentual / 100);
    }

    /**
     * Calcula o valor total final
     */
    public function calcularValorTotal(): float
    {
        return $this->calcularSubtotal() + $this->calcularValorImpostos();
    }

    /**
     * Atualiza todos os valores calculados
     * Este é o método que o controller está chamando
     */
    public function calcularValores(): void
    {
        $this->km_total_mes = $this->calcularKmTotalMes();
        $this->total_combustivel = $this->calcularTotalCombustivel();
        $this->custo_total_combustivel_he = $this->calcularCustoTotalCombustivelHe();
        $this->valor_lucro = $this->calcularValorLucro();
        $this->valor_impostos = $this->calcularValorImpostos();
        $this->valor_total = $this->calcularValorTotal();
    }
}