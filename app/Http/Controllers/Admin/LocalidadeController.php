<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LocalidadeController extends Controller
{
    private $ibgeBaseUrl = 'https://servicodados.ibge.gov.br/api/v1/localidades';
    
    /**
     * Buscar todos os estados do Brasil
     */
    public function estados()
    {
        try {
            $estados = Cache::remember('ibge_estados', 3600, function () {
                $response = Http::timeout(10)->get($this->ibgeBaseUrl . '/estados?orderBy=nome');
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                throw new \Exception('Erro ao buscar estados: ' . $response->status());
            });
            
            return response()->json([
                'success' => true,
                'data' => $estados,
                'message' => 'Estados carregados com sucesso'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao buscar estados do IBGE: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Erro ao carregar estados. Tente novamente.'
            ], 500);
        }
    }
    
    /**
     * Buscar municípios por UF
     */
    public function municipiosPorUf($uf)
    {
        try {
            $municipios = Cache::remember("ibge_municipios_{$uf}", 3600, function () use ($uf) {
                $response = Http::timeout(10)->get($this->ibgeBaseUrl . "/estados/{$uf}/municipios?orderBy=nome");
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                throw new \Exception('Erro ao buscar municípios: ' . $response->status());
            });
            
            return response()->json([
                'success' => true,
                'data' => $municipios,
                'message' => 'Municípios carregados com sucesso'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro ao buscar municípios da UF {$uf}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Erro ao carregar municípios. Tente novamente.'
            ], 500);
        }
    }
    
    /**
     * Buscar município específico por ID
     */
    public function municipio($id)
    {
        try {
            $municipio = Cache::remember("ibge_municipio_{$id}", 3600, function () use ($id) {
                $response = Http::timeout(10)->get($this->ibgeBaseUrl . "/municipios/{$id}");
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                throw new \Exception('Erro ao buscar município: ' . $response->status());
            });
            
            return response()->json([
                'success' => true,
                'data' => $municipio,
                'message' => 'Município carregado com sucesso'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro ao buscar município {$id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Erro ao carregar município. Tente novamente.'
            ], 500);
        }
    }
    
    /**
     * Buscar todas as regiões do Brasil
     */
    public function regioes()
    {
        try {
            $regioes = Cache::remember('ibge_regioes', 3600, function () {
                $response = Http::timeout(10)->get($this->ibgeBaseUrl . '/regioes?orderBy=nome');
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                throw new \Exception('Erro ao buscar regiões: ' . $response->status());
            });
            
            return response()->json([
                'success' => true,
                'data' => $regioes,
                'message' => 'Regiões carregadas com sucesso'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao buscar regiões do IBGE: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Erro ao carregar regiões. Tente novamente.'
            ], 500);
        }
    }
    
    /**
     * Limpar cache das localidades
     */
    public function limparCache()
    {
        try {
            Cache::forget('ibge_estados');
            Cache::forget('ibge_regioes');
            
            // Limpar cache de municípios (todas as UFs)
            $ufs = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
            
            foreach ($ufs as $uf) {
                Cache::forget("ibge_municipios_{$uf}");
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Cache das localidades limpo com sucesso'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao limpar cache das localidades: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar cache. Tente novamente.'
            ], 500);
        }
    }
}