<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrcamentoHistorico extends Model
{
    protected $table = 'orcamento_historico';

    protected $fillable = [
        'orcamento_id',
        'user_id',
        'acao',
        'status_anterior',
        'status_novo',
        'dados_alterados',
        'observacoes',
        'data_acao'
    ];

    protected $casts = [
        'dados_alterados' => 'array',
        'data_acao' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relacionamento com orçamento
     */
    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }

    /**
     * Relacionamento com usuário que executou a ação
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para ações de criação
     */
    public function scopeCriacao($query)
    {
        return $query->where('acao', 'criacao');
    }

    /**
     * Scope para ações de edição
     */
    public function scopeEdicao($query)
    {
        return $query->where('acao', 'edicao');
    }

    /**
     * Scope para ações de aprovação
     */
    public function scopeAprovacao($query)
    {
        return $query->where('acao', 'aprovacao');
    }

    /**
     * Scope para ações de envio
     */
    public function scopeEnvio($query)
    {
        return $query->where('acao', 'envio');
    }

    /**
     * Accessor para ação formatada
     */
    public function getAcaoFormattedAttribute(): string
    {
        $acaoMap = [
            'criacao' => 'Criação',
            'edicao' => 'Edição',
            'aprovacao' => 'Aprovação',
            'rejeicao' => 'Rejeição',
            'envio' => 'Envio',
            'cancelamento' => 'Cancelamento',
            'expiracao' => 'Expiração'
        ];

        return $acaoMap[$this->acao] ?? $this->acao;
    }

    /**
     * Accessor para mudança de status formatada
     */
    public function getMudancaStatusAttribute(): ?string
    {
        if ($this->status_anterior && $this->status_novo) {
            return "{$this->status_anterior} → {$this->status_novo}";
        }

        return null;
    }

    /**
     * Verifica se houve alteração de dados
     */
    public function temAlteracaoDados(): bool
    {
        return !empty($this->dados_alterados);
    }
}
