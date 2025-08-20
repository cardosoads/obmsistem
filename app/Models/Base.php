<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Base extends Model
{
    protected $table = 'bases';

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'zip_code',
        'latitude',
        'longitude',
        'uf',
        'regional',
        'sigla',
        'supervisor',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Orçamentos que têm esta base como origem
     */
    public function orcamentosOrigem(): HasMany
    {
        return $this->hasMany(Orcamento::class, 'base_origem_id');
    }

    /**
     * Orçamentos que têm esta base como destino
     */
    public function orcamentosDestino(): HasMany
    {
        return $this->hasMany(Orcamento::class, 'base_destino_id');
    }

    /**
     * Scope para bases ativas
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Mutator para sigla - converte para maiúsculo e remove acentos
     */
    public function setSiglaAttribute($value)
    {
        if ($value) {
            $this->attributes['sigla'] = $this->sanitizeText($value);
        }
    }

    /**
     * Função para sanitizar texto (maiúsculo e sem acentos)
     */
    private function sanitizeText($text)
    {
        // Remove acentos
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        // Remove caracteres especiais, mantém apenas letras, números e espaços
        $text = preg_replace('/[^A-Za-z0-9\s]/', '', $text);
        // Converte para maiúsculo
        return strtoupper(trim($text));
    }

    /**
     * Accessor para endereço completo
     */
    public function getFullAddressAttribute(): string
    {
        return trim("{$this->address}, {$this->city} - {$this->state}, {$this->zip_code}");
    }
}
