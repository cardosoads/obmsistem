<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CentroCusto extends Model
{
    protected $table = 'centros_custo';

    protected $fillable = [
        'name',
        'codigo',
        'description',
        'active',
        'cliente_omie_id',
        'cliente_nome',
        'base_id',
        'regional',
        'sigla',
        'uf',
        'supervisor',
        'marca_id',
        'mercado',
        // Campos da API Omie
        'omie_codigo',
        'omie_estrutura',
        'omie_inativo',
        'sincronizado_em'
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'sincronizado_em' => 'datetime'
    ];



    /**
     * Relacionamento com Base
     */
    public function base(): BelongsTo
    {
        return $this->belongsTo(Base::class);
    }

    /**
     * Relacionamento com Marca
     */
    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    /**
     * Scope para centros de custo ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para centros de custo sincronizados com a API Omie
     */
    public function scopeSincronizados($query)
    {
        return $query->whereNotNull('omie_codigo');
    }

    /**
     * Scope para centros de custo não sincronizados
     */
    public function scopeNaoSincronizados($query)
    {
        return $query->whereNull('omie_codigo');
    }

    /**
     * Scope para centros de custo ativos na Omie (apenas sincronizados)
     */
    public function scopeAtivosOmie($query)
    {
        return $query->whereNotNull('omie_codigo')->where('omie_inativo', 'N');
    }

    /**
     * Scope para centros de custo inativos na Omie (apenas sincronizados)
     */
    public function scopeInativosOmie($query)
    {
        return $query->whereNotNull('omie_codigo')->where('omie_inativo', 'S');
    }

    /**
     * Accessor para nome completo (código + nome)
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->codigo} - {$this->name}";
    }

    /**
     * Mutator para código - converte para maiúsculo
     */
    public function setCodigoAttribute($value)
    {
        $this->attributes['codigo'] = strtoupper(trim($value));
    }

    /**
     * Atualiza os campos da base quando uma base é selecionada
     */
    public function updateBaseFields()
    {
        if ($this->base) {
            $this->regional = $this->base->regional;
            $this->sigla = $this->base->sigla;
            $this->uf = $this->base->uf;
            $this->supervisor = $this->base->supervisor;
        }
    }

    /**
     * Verifica se o centro de custo foi sincronizado com a API Omie
     */
    public function isSincronizado(): bool
    {
        return !is_null($this->omie_codigo);
    }

    /**
     * Verifica se o centro de custo está ativo na Omie
     */
    public function isAtivoOmie(): bool
    {
        return $this->omie_inativo === 'N';
    }

    /**
     * Verifica se o centro de custo precisa de preenchimento de dados
     */
    public function precisaPreenchimento(): bool
    {
        return $this->isSincronizado() && (empty($this->name) || empty($this->description));
    }

    /**
     * Atualiza dados vindos da API Omie
     */
    public function atualizarDadosOmie(array $dadosOmie): void
    {
        $this->update([
            'omie_codigo' => $dadosOmie['codigo'],
            'codigo' => $dadosOmie['descricao'], // descricao da API vira codigo local
            'omie_estrutura' => $dadosOmie['estrutura'] ?? null,
            'omie_inativo' => $dadosOmie['inativo'] ?? 'N',
            'sincronizado_em' => now(),
            'active' => ($dadosOmie['inativo'] ?? 'N') === 'N' // ativo se não estiver inativo na Omie
        ]);
    }
}
