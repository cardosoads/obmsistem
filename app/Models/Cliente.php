<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $fillable = [
        'omie_id',
        'name',
        'document',
        'email',
        'phone',
        'active'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Scope para clientes ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }



    /**
     * Accessor para tipo de documento
     */
    public function getDocumentTypeAttribute(): string
    {
        if (!$this->document) {
            return 'N/A';
        }
        
        $cleanDocument = preg_replace('/\D/', '', $this->document);
        return strlen($cleanDocument) === 11 ? 'CPF' : 'CNPJ';
    }

    /**
     * Accessor para documento formatado
     */
    public function getFormattedDocumentAttribute(): string
    {
        if (!$this->document) {
            return 'N/A';
        }
        
        $cleanDocument = preg_replace('/\D/', '', $this->document);
        
        if (strlen($cleanDocument) === 11) {
            // CPF: 000.000.000-00
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cleanDocument);
        } elseif (strlen($cleanDocument) === 14) {
            // CNPJ: 00.000.000/0000-00
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cleanDocument);
        }
        
        return $this->document;
    }
}
