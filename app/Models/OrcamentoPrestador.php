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
        'grupo_imposto_id',
        'observacoes'
    ];

    protected $casts = [
        'valor_referencia' => 'decimal:2',
        'qtd_dias' => 'integer',
        'custo_fornecedor' => 'decimal:2',
        'lucro_percentual' => 'decimal:2',
        'valor_lucro' => 'decimal:2',
        'impostos_percentual' => 'decimal:2',
        'valor_impostos' => 'decimal:2',
        'valor_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com Orçamento
     */
    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function grupoImposto(): BelongsTo
    {
        return $this->belongsTo(GrupoImposto::class);
    }

    /**
     * Accessor para formatar o percentual de impostos
     */
    public function getImpostosPercentualFormatadoAttribute(): string
    {
        return number_format($this->impostos_percentual, 2, '.', '');
    }

    /**
     * Calcula o custo do fornecedor (valor_referencia * qtd_dias)
     */
    public function calcularCustoFornecedor(): float
    {
        return $this->valor_referencia * $this->qtd_dias;
    }

    /**
     * Calcula o valor do lucro
     */
    public function calcularValorLucro(): float
    {
        return $this->custo_fornecedor * ($this->lucro_percentual / 100);
    }

    /**
     * Calcula o subtotal (custo + lucro)
     */
    public function calcularSubtotal(): float
    {
        return $this->custo_fornecedor + $this->valor_lucro;
    }

    /**
     * Calcula o valor dos impostos sobre o subtotal
     */
    public function calcularValorImpostos(): float
    {
        return $this->calcularSubtotal() * ($this->impostos_percentual / 100);
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
     */
    public function atualizarCalculos()
    {
        $this->custo_fornecedor = $this->calcularCustoFornecedor();
        $this->valor_lucro = $this->calcularValorLucro();
        $this->valor_impostos = $this->calcularValorImpostos();
        $this->valor_total = $this->calcularValorTotal();
        $this->save();
    }

    /**
     * Atualiza apenas os valores calculados, preservando os percentuais
     */
    public function atualizarCalculosPreservandoPercentuais()
    {
        // Salva os percentuais atuais antes de recalcular, garantindo valores não-nulos
        $percentualLucroAtual = $this->lucro_percentual ?? 0;
        $percentualImpostosAtual = $this->impostos_percentual ?? 0;
        
        $this->custo_fornecedor = $this->calcularCustoFornecedor();
        $this->valor_lucro = $this->calcularValorLucro();
        $this->valor_impostos = $this->calcularValorImpostos();
        $this->valor_total = $this->calcularValorTotal();
        
        // Restaura os percentuais originais, garantindo que não sejam NULL
        $this->lucro_percentual = $percentualLucroAtual;
        $this->impostos_percentual = $percentualImpostosAtual;
        
        $this->save();
    }

    /**
     * Calcula a quantidade de dias baseada na frequência de atendimento do orçamento
     */
    public function calcularDiasFrequencia(): int
    {
        if (!$this->orcamento || !$this->orcamento->frequencia_atendimento) {
            return 1; // Valor padrão
        }

        $frequencia = $this->orcamento->frequencia_atendimento;
        
        // Se for array, conta os dias selecionados
        if (is_array($frequencia)) {
            return count($frequencia);
        }
        
        // Se for string, converte para array e conta
        if (is_string($frequencia)) {
            $dias = explode(',', $frequencia);
            return count(array_filter($dias));
        }
        
        return 1; // Valor padrão
    }

    /**
     * Define a quantidade inicial de dias baseada na frequência de atendimento
     */
    public function definirDiasIniciais()
    {
        if (!$this->qtd_dias) {
            $this->qtd_dias = $this->calcularDiasFrequencia();
        }
    }
}
