<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class OmieApiService
{
    private string $baseUrl;
    private string $appKey;
    private string $appSecret;

    public function __construct()
    {
        // Carrega as configurações das variáveis de ambiente
        $this->baseUrl = config('services.omie.api_url', 'https://app.omie.com.br/api/v1/');
        $this->appKey = config('services.omie.app_key');
        $this->appSecret = config('services.omie.app_secret');
    }

    /**
     * Buscar clientes da API OMIE
     */
    public function getClientes(array $filters = []): array
    {
        // Verifica se as chaves estão configuradas
        if (empty($this->appKey) || empty($this->appSecret)) {
            Log::error('Chaves da API Omie não configuradas');
            return [];
        }
        
        $cacheKey = 'omie_clientes_' . md5(serialize($filters));
        
        return Cache::remember($cacheKey, 300, function () use ($filters) {
            try {
                $response = Http::timeout(30)->post($this->baseUrl . 'geral/clientes/', [
                    'call' => 'ListarClientes',
                    'app_key' => $this->appKey,
                    'app_secret' => $this->appSecret,
                    'param' => [
                        'pagina' => $filters['pagina'] ?? 1,
                        'registros_por_pagina' => $filters['registros_por_pagina'] ?? 50,
                        'apenas_importado_api' => 'N'
                    ]
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    \Log::info('Resposta da API OMIE: ', ['data' => $data]);
                    $clientes = $data['clientes_cadastro'] ?? [];
                    \Log::info('Clientes extraídos: ', ['count' => count($clientes)]);
                    return $clientes;
                }

                Log::error('Erro na API OMIE - Listar Clientes', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return [];
            } catch (\Exception $e) {
                Log::error('Exceção na API OMIE - Listar Clientes', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return [];
            }
        });
    }

    /**
     * Buscar cliente específico por ID
     */
    public function getCliente(int $omieId): ?array
    {
        // Verifica se as chaves estão configuradas
        if (empty($this->appKey) || empty($this->appSecret)) {
            Log::error('Chaves da API Omie não configuradas');
            return null;
        }
        
        $cacheKey = "omie_cliente_{$omieId}";
        
        return Cache::remember($cacheKey, 600, function () use ($omieId) {
            try {
                $response = Http::timeout(30)->post($this->baseUrl . 'geral/clientes/', [
                    'call' => 'ConsultarCliente',
                    'app_key' => $this->appKey,
                    'app_secret' => $this->appSecret,
                    'param' => [
                        'codigo_cliente_omie' => $omieId
                    ]
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('Erro na API OMIE - Consultar Cliente', [
                    'omie_id' => $omieId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return null;
            } catch (\Exception $e) {
                Log::error('Exceção na API OMIE - Consultar Cliente', [
                    'omie_id' => $omieId,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return null;
            }
        });
    }

    /**
     * Buscar clientes com filtro de texto
     */
    public function searchClientes(string $search): array
    {
        $clientes = $this->getClientes();
        
        if (empty($search)) {
            return $clientes;
        }

        return array_filter($clientes, function ($cliente) use ($search) {
            $searchLower = strtolower($search);
            
            return str_contains(strtolower($cliente['razao_social'] ?? ''), $searchLower) ||
                   str_contains(strtolower($cliente['nome_fantasia'] ?? ''), $searchLower) ||
                   str_contains($cliente['cnpj_cpf'] ?? '', $search);
        });
    }

    /**
     * Limpar cache de clientes
     */
    public function clearClientesCache(): void
    {
        $keys = Cache::getRedis()->keys('*omie_clientes_*');
        foreach ($keys as $key) {
            Cache::forget(str_replace(config('cache.prefix') . ':', '', $key));
        }
    }

    /**
     * Método genérico para fazer requisições à API Omie
     */
    public function makeRequest(string $endpoint, string $method, array $params = []): array
    {
        // Verifica se as chaves estão configuradas
        if (empty($this->appKey) || empty($this->appSecret)) {
            throw new \Exception('Chaves da API Omie não configuradas');
        }

        try {
            $response = Http::timeout(30)->post($this->baseUrl . $endpoint, [
                'call' => $method,
                'app_key' => $this->appKey,
                'app_secret' => $this->appSecret,
                'param' => $params
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Erro na API OMIE', [
                'endpoint' => $endpoint,
                'method' => $method,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            throw new \Exception('Erro na API Omie: ' . $response->status() . ' - ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Exceção na API OMIE', [
                'endpoint' => $endpoint,
                'method' => $method,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }
}