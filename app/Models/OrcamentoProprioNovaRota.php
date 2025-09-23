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
        'observacoes',
        // Campos funcionário
        'tem_funcionario',
        'recurso_humano_id',
        'cargo_funcionario',
        'base_funcionario_id',
        'valor_funcionario',
        // Campos veículo próprio
        'tem_veiculo_proprio',
        'frota_id',
        'valor_aluguel_veiculo',
        // Campos prestador
        'tem_prestador',
        'fornecedor_omie_id',
        'fornecedor_nome',
        'valor_referencia_prestador',
        'qtd_dias_prestador',
        'custo_prestador',
        'lucro_percentual_prestador',
        'valor_lucro_prestador',
        'impostos_percentual_prestador',
        'valor_impostos_prestador',
        'valor_total_prestador',
        'grupo_imposto_prestador_id',
        'valor_total_geral'
    ];

    protected $casts = [
        'km_nova_rota' => 'decimal:2',
        'valor_km_nova_rota' => 'decimal:2',
        'valor_total_nova_rota' => 'decimal:2',
        'tem_funcionario' => 'boolean',
        'valor_funcionario' => 'decimal:2',
        'tem_veiculo_proprio' => 'boolean',
        'valor_aluguel_veiculo' => 'decimal:2',
        'tem_prestador' => 'boolean',
        'valor_referencia_prestador' => 'decimal:2',
        'qtd_dias_prestador' => 'integer',
        'custo_prestador' => 'decimal:2',
        'lucro_percentual_prestador' => 'decimal:2',
        'valor_lucro_prestador' => 'decimal:2',
        'impostos_percentual_prestador' => 'decimal:2',
        'valor_impostos_prestador' => 'decimal:2',
        'valor_total_prestador' => 'decimal:2',
        'valor_total_geral' => 'decimal:2'
    ];

    /**
     * Relacionamento com Orcamento
     */
    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    /**
     * Relacionamento com RecursoHumano (funcionário)
     */
    public function recursoHumano()
    {
        return $this->belongsTo(RecursoHumano::class, 'recurso_humano_id');
    }

    /**
     * Relacionamento com Base (para funcionário)
     */
    public function baseFuncionario()
    {
        return $this->belongsTo(Base::class, 'base_funcionario_id');
    }

    /**
     * Relacionamento com Frota (para veículo próprio)
     */
    public function frota()
    {
        return $this->belongsTo(Frota::class);
    }

    /**
     * Relacionamento com Grupo de Impostos (para prestador)
     */
    public function grupoImpostoPrestador()
    {
        return $this->belongsTo(GrupoImposto::class, 'grupo_imposto_prestador_id');
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

    /**
     * Calcula o custo do prestador (valor_referencia * qtd_dias)
     */
    public function calcularCustoPrestador(): float
    {
        if (!$this->tem_prestador) {
            return 0;
        }
        return ($this->valor_referencia_prestador ?? 0) * ($this->qtd_dias_prestador ?? 0);
    }

    /**
     * Calcula o valor do lucro do prestador
     */
    public function calcularValorLucroPrestador(): float
    {
        if (!$this->tem_prestador) {
            return 0;
        }
        return ($this->custo_prestador ?? 0) * (($this->lucro_percentual_prestador ?? 0) / 100);
    }

    /**
     * Calcula o subtotal do prestador (custo + lucro)
     */
    public function calcularSubtotalPrestador(): float
    {
        if (!$this->tem_prestador) {
            return 0;
        }
        return ($this->custo_prestador ?? 0) + ($this->valor_lucro_prestador ?? 0);
    }

    /**
     * Calcula o valor dos impostos do prestador
     */
    public function calcularValorImpostosPrestador(): float
    {
        if (!$this->tem_prestador) {
            return 0;
        }
        return $this->calcularSubtotalPrestador() * (($this->impostos_percentual_prestador ?? 0) / 100);
    }

    /**
     * Calcula o valor total do prestador
     */
    public function calcularValorTotalPrestador(): float
    {
        if (!$this->tem_prestador) {
            return 0;
        }
        return $this->calcularSubtotalPrestador() + ($this->valor_impostos_prestador ?? 0);
    }

    /**
     * Calcula o valor total geral do orçamento
     */
    public function calcularValorTotalGeral(): float
    {
        $total = 0;
        
        // Adiciona valor da nova rota
        $total += $this->valor_total_nova_rota ?? 0;
        
        // Adiciona valor do funcionário
        if ($this->tem_funcionario) {
            $total += $this->valor_funcionario ?? 0;
        }
        
        // Adiciona valor do veículo próprio
        if ($this->tem_veiculo_proprio) {
            $total += $this->valor_aluguel_veiculo ?? 0;
        }
        
        // Adiciona valor do prestador
        if ($this->tem_prestador) {
            $total += $this->valor_total_prestador ?? 0;
        }
        
        return $total;
    }

    /**
     * Atualiza todos os cálculos automáticos
     */
    public function atualizarCalculos(): void
    {
        // Atualiza cálculos da nova rota
        $this->valor_total_nova_rota = $this->calcularValorTotalNovaRota();
        
        // Atualiza cálculos do prestador se habilitado
        if ($this->tem_prestador) {
            $this->custo_prestador = $this->calcularCustoPrestador();
            $this->valor_lucro_prestador = $this->calcularValorLucroPrestador();
            $this->valor_impostos_prestador = $this->calcularValorImpostosPrestador();
            $this->valor_total_prestador = $this->calcularValorTotalPrestador();
        }
        
        // Atualiza valor total geral
        $this->valor_total_geral = $this->calcularValorTotalGeral();
    }
}