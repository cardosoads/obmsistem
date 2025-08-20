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
        'mercado'
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Orçamentos deste centro de custo
     */
    public function orcamentos(): HasMany
    {
        return $this->hasMany(Orcamento::class);
    }

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
}
