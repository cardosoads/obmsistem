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
    public function scopeCriado($query)
    {
        return $query->where('acao', 'criado');
    }

    /**
     * Scope para ações de edição
     */
    public function scopeEditado($query)
    {
        return $query->where('acao', 'editado');
    }

    /**
     * Scope para ações de aprovação
     */
    public function scopeAprovado($query)
    {
        return $query->where('acao', 'aprovado');
    }

    /**
     * Scope para ações de envio
     */
    public function scopeEnviado($query)
    {
        return $query->where('acao', 'enviado');
    }

    /**
     * Accessor para ação formatada
     */
    public function getAcaoFormattedAttribute(): string
    {
        $acaoMap = [
            'criado' => 'Criado',
            'editado' => 'Editado',
            'enviado' => 'Enviado',
            'aprovado' => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            'cancelado' => 'Cancelado'
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
