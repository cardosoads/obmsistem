<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Combustivel extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'combustiveis';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'base_id',
        'convenio',
        'preco_litro',
        'active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'preco_litro' => 'decimal:3',
        'active' => 'boolean',
    ];

    /**
     * Relacionamento com base
     */
    public function base(): BelongsTo
    {
        return $this->belongsTo(Base::class);
    }

    /**
     * Scope para combustíveis ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para filtrar por base
     */
    public function scopeByBase($query, $baseId)
    {
        return $query->where('base_id', $baseId);
    }

    /**
     * Scope para filtrar por convênio
     */
    public function scopeByConvenio($query, $convenio)
    {
        return $query->where('convenio', 'like', '%' . $convenio . '%');
    }

    /**
     * Accessor para preço formatado
     */
    public function getPrecoFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->preco_litro, 3, ',', '.');
    }

    /**
     * Accessor para status formatado
     */
    public function getStatusFormatadoAttribute()
    {
        return $this->active ? 'Ativo' : 'Inativo';
    }

    /**
     * Accessor para nome completo (base + convênio)
     */
    public function getNomeCompletoAttribute()
    {
        return $this->base->name . ' - ' . $this->convenio;
    }
}
