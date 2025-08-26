<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_encrypted'
    ];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    /**
     * Busca uma configuração por chave
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = "setting_{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            if ($setting->is_encrypted) {
                try {
                    $value = Crypt::decryptString($setting->value);
                    // Se o valor ainda estiver serializado, deserializar
                    if (is_string($value) && (strpos($value, 's:') === 0 || strpos($value, 'a:') === 0 || strpos($value, 'i:') === 0)) {
                        $value = unserialize($value);
                    }
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    // Se não conseguir descriptografar, retorna o valor padrão
                    return $default;
                }
            } else {
                $value = $setting->value;
            }
            
            return static::castValue($value, $setting->type);
        });
    }

    /**
     * Define uma configuração
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general', bool $encrypt = false, string $description = null)
    {
        $processedValue = $encrypt ? Crypt::encrypt($value) : $value;
        
        if ($type === 'json') {
            $processedValue = json_encode($value);
        }
        
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $processedValue,
                'type' => $type,
                'group' => $group,
                'is_encrypted' => $encrypt,
                'description' => $description
            ]
        );
        
        // Limpa o cache
        Cache::forget("setting_{$key}");
    }

    /**
     * Converte o valor para o tipo correto
     */
    protected static function castValue($value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Busca configurações por grupo
     */
    public static function getByGroup(string $group)
    {
        return static::where('group', $group)->get()->mapWithKeys(function ($setting) {
            if ($setting->is_encrypted) {
                try {
                    $value = Crypt::decryptString($setting->value);
                    // Se o valor ainda estiver serializado, deserializar
                    if (is_string($value) && (strpos($value, 's:') === 0 || strpos($value, 'a:') === 0 || strpos($value, 'i:') === 0)) {
                        $value = unserialize($value);
                    }
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    // Se não conseguir descriptografar, pula esta configuração
                    return [];
                }
            } else {
                $value = $setting->value;
            }
            return [$setting->key => static::castValue($value, $setting->type)];
        });
    }
}
