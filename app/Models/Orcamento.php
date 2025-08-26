<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Orcamento extends Model
{
    protected $fillable = [
        'data_solicitacao',
        'centro_custo_id',
        'numero_orcamento',
        'nome_rota',
        'id_logcare',
        'cliente_omie_id',
        'cliente_nome',
        'horario',
        'frequencia_atendimento',
        'tipo_orcamento',
        'user_id',
        'data_orcamento',
        'valor_total',
        'valor_impostos',
        'valor_final',
        'percentual_lucro',
        'percentual_impostos',
        'status',
        'observacoes'
    ];

    protected $casts = [
        'data_solicitacao' => 'date',
        'data_orcamento' => 'date',
        'horario' => 'datetime:H:i',
        'valor_total' => 'decimal:2',
        'valor_impostos' => 'decimal:2',
        'valor_final' => 'decimal:2',
        'percentual_lucro' => 'decimal:2',
        'percentual_impostos' => 'decimal:2',
        'frequencia_atendimento' => 'array'
    ];

    // Relacionamentos
    public function centroCusto(): BelongsTo
    {
        return $this->belongsTo(CentroCusto::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orcamentoPrestador(): HasOne
    {
        return $this->hasOne(OrcamentoPrestador::class);
    }

    public function orcamentoAumentoKm(): HasOne
    {
        return $this->hasOne(OrcamentoAumentoKm::class);
    }

    public function orcamentoProprioNovaRota(): HasOne
    {
        return $this->hasOne(OrcamentoProprioNovaRota::class);
    }

    // Scopes
    public function scopeRascunho($query)
    {
        return $query->where('status', 'rascunho');
    }

    public function scopeEnviado($query)
    {
        return $query->where('status', 'enviado');
    }

    public function scopeAprovado($query)
    {
        return $query->where('status', 'aprovado');
    }

    // Métodos auxiliares

    public function calcularValorFinal(): float
    {
        return $this->valor_total + $this->valor_impostos;
    }

    public function getStatusFormattedAttribute(): string
    {
        $statusMap = [
            'rascunho' => 'Rascunho',
            'enviado' => 'Enviado',
            'aprovado' => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            'cancelado' => 'Cancelado'
        ];

        return $statusMap[$this->status] ?? $this->status;
    }

    // Geração automática do número do orçamento
    public static function gerarNumeroOrcamento(): string
    {
        return 'ORC-' . date('YmdHis');
    }

    // Boot method para auto-gerar número do orçamento e atualizar valores
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orcamento) {
            if (empty($orcamento->numero_orcamento)) {
                $orcamento->numero_orcamento = self::gerarNumeroOrcamento();
            }
            if (empty($orcamento->data_orcamento)) {
                $orcamento->data_orcamento = Carbon::now()->format('Y-m-d');
            }
        });

        // Atualizar valor_final automaticamente ao salvar
        static::saving(function ($orcamento) {
            if ($orcamento->valor_total !== null) {
                // Para orçamentos do tipo prestador, somar impostos ao valor total
                if ($orcamento->tipo_orcamento === 'prestador' && $orcamento->valor_impostos !== null) {
                    $orcamento->valor_final = $orcamento->valor_total + $orcamento->valor_impostos;
                } else {
                    // Para outros tipos de orçamento, valor_final é igual ao valor_total
                    $orcamento->valor_final = $orcamento->valor_total;
                }
            }
        });
    }
}
