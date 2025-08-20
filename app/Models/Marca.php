<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marca extends Model
{
    protected $fillable = [
        'name',
        'mercado',
        'active'
    ];

    /**
     * Mutator para garantir que o nome seja sempre maiúsculo e sem acentuação
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $this->sanitizeText($value);
    }

    /**
     * Mutator para garantir que o mercado seja sempre maiúsculo e sem acentuação
     */
    public function setMercadoAttribute($value)
    {
        $this->attributes['mercado'] = $this->sanitizeText($value);
    }

    /**
     * Sanitiza o texto removendo acentos e convertendo para maiúsculo
     */
    private function sanitizeText($text)
    {
        // Remove acentos
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        // Remove caracteres especiais restantes
        $text = preg_replace('/[^A-Za-z0-9\s]/', '', $text);
        // Converte para maiúsculo
        return strtoupper(trim($text));
    }

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Orçamentos de prestador que usam esta marca
     */
    public function orcamentosPrestador(): HasMany
    {
        return $this->hasMany(OrcamentoPrestador::class);
    }

    /**
     * Scope para marcas ativas
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
