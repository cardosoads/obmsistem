<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'evento',
        'tipo_orcamento',
        'user_id',
        'data_orcamento',
        'data_validade',
        'data_aprovacao',
        'data_inicio',
        'data_exclusao',
        'data_envio',
        'detalhes',
        'valor_total',
        'valor_impostos',
        'valor_final',
        'status',
        'observacoes'
    ];

    protected $casts = [
        'data_solicitacao' => 'date',
        'data_orcamento' => 'date',
        'data_validade' => 'date',
        'data_aprovacao' => 'datetime',
        'data_inicio' => 'datetime',
        'data_exclusao' => 'datetime',
        'data_envio' => 'datetime',
        'valor_total' => 'decimal:2',
        'valor_impostos' => 'decimal:2',
        'valor_final' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Obter dados do cliente da API OMIE
     */
    public function getClienteOmieAttribute()
    {
        if (!$this->cliente_omie_id) {
            return null;
        }

        try {
            $omieService = app(\App\Services\OmieApiService::class);
            return $omieService->getCliente($this->cliente_omie_id);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar cliente OMIE: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Relacionamento com centro de custo
     */
    public function centroCusto(): BelongsTo
    {
        return $this->belongsTo(CentroCusto::class);
    }

    /**
     * Relacionamento com usuário responsável
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Orçamentos de prestador
     */
    public function orcamentosPrestador(): HasMany
    {
        return $this->hasMany(OrcamentoPrestador::class);
    }

    /**
     * Aumentos de KM
     */
    public function aumentosKm(): HasMany
    {
        return $this->hasMany(OrcamentoAumentoKm::class);
    }

    /**
     * Novas rotas próprias
     */
    public function novasRotasProprias(): HasMany
    {
        return $this->hasMany(OrcamentoProprioNovaRota::class);
    }

    /**
     * Histórico do orçamento
     */
    public function historico(): HasMany
    {
        return $this->hasMany(OrcamentoHistorico::class);
    }

    /**
     * Scopes para diferentes status
     */
    public function scopeRascunho($query)
    {
        return $query->where('status', 'rascunho');
    }

    public function scopeAprovado($query)
    {
        return $query->where('status', 'aprovado');
    }

    public function scopeEnviado($query)
    {
        return $query->where('status', 'enviado');
    }

    /**
     * Verifica se o orçamento está vencido
     */
    public function isVencido(): bool
    {
        return $this->data_validade && $this->data_validade->isPast();
    }

    /**
     * Calcula o valor total com impostos
     */
    public function calcularValorFinal(): float
    {
        return $this->valor_total + $this->valor_impostos;
    }

    /**
     * Accessor para status formatado
     */
    public function getStatusFormattedAttribute(): string
    {
        $statusMap = [
            'rascunho' => 'Rascunho',
            'enviado' => 'Enviado',
            'aguardando' => 'Aguardando',
            'aprovado' => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            'cancelado' => 'Cancelado',
            'expirado' => 'Expirado'
        ];

        return $statusMap[$this->status] ?? $this->status;
    }
}
