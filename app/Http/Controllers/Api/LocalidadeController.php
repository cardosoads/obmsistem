<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LocalidadeController extends Controller
{
    private const IBGE_API_BASE = 'https://servicodados.ibge.gov.br/api/v1/localidades';
    private const CACHE_TTL = 86400; // 24 horas

    /**
     * Busca todos os estados brasileiros
     */
    public function estados()
    {
        try {
            $estados = Cache::remember('ibge_estados', self::CACHE_TTL, function () {
                $response = Http::timeout(10)->get(self::IBGE_API_BASE . '/estados');
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Ordenar por nome
                    usort($data, function ($a, $b) {
                        return strcmp($a['nome'], $b['nome']);
                    });
                    
                    return collect($data)->map(function ($estado) {
                        return [
                            'id' => $estado['id'],
                            'sigla' => $estado['sigla'],
                            'nome' => $estado['nome'],
                            'regiao' => [
                                'id' => $estado['regiao']['id'],
                                'nome' => $estado['regiao']['nome'],
                                'sigla' => $estado['regiao']['sigla']
                            ]
                        ];
                    })->toArray();
                }
                
                throw new \Exception('Erro ao buscar estados na API do IBGE');
            });
            
            return response()->json([
                'success' => true,
                'data' => $estados
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao buscar estados IBGE', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar estados. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Busca municípios por UF
     */
    public function municipiosPorUf($uf)
    {
        try {
            $municipios = Cache::remember("ibge_municipios_{$uf}", self::CACHE_TTL, function () use ($uf) {
                $response = Http::timeout(15)->get(self::IBGE_API_BASE . "/estados/{$uf}/municipios");
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Ordenar por nome
                    usort($data, function ($a, $b) {
                        return strcmp($a['nome'], $b['nome']);
                    });
                    
                    return collect($data)->map(function ($municipio) {
                        return [
                            'id' => $municipio['id'],
                            'nome' => $municipio['nome'],
                            'microrregiao' => [
                                'id' => $municipio['microrregiao']['id'],
                                'nome' => $municipio['microrregiao']['nome'],
                                'mesorregiao' => [
                                    'id' => $municipio['microrregiao']['mesorregiao']['id'],
                                    'nome' => $municipio['microrregiao']['mesorregiao']['nome'],
                                    'UF' => [
                                        'id' => $municipio['microrregiao']['mesorregiao']['UF']['id'],
                                        'sigla' => $municipio['microrregiao']['mesorregiao']['UF']['sigla'],
                                        'nome' => $municipio['microrregiao']['mesorregiao']['UF']['nome'],
                                        'regiao' => [
                                            'id' => $municipio['microrregiao']['mesorregiao']['UF']['regiao']['id'],
                                            'nome' => $municipio['microrregiao']['mesorregiao']['UF']['regiao']['nome'],
                                            'sigla' => $municipio['microrregiao']['mesorregiao']['UF']['regiao']['sigla']
                                        ]
                                    ]
                                ]
                            ]
                        ];
                    })->toArray();
                }
                
                throw new \Exception('Erro ao buscar municípios na API do IBGE');
            });
            
            return response()->json([
                'success' => true,
                'data' => $municipios
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao buscar municípios IBGE', [
                'uf' => $uf,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar municípios. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Busca informações de um município específico
     */
    public function municipio($id)
    {
        try {
            $municipio = Cache::remember("ibge_municipio_{$id}", self::CACHE_TTL, function () use ($id) {
                $response = Http::timeout(10)->get(self::IBGE_API_BASE . "/municipios/{$id}");
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                throw new \Exception('Erro ao buscar município na API do IBGE');
            });
            
            return response()->json([
                'success' => true,
                'data' => $municipio
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao buscar município IBGE', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar município. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Busca regiões do Brasil
     */
    public function regioes()
    {
        try {
            $regioes = Cache::remember('ibge_regioes', self::CACHE_TTL, function () {
                $response = Http::timeout(10)->get(self::IBGE_API_BASE . '/regioes');
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Ordenar por nome
                    usort($data, function ($a, $b) {
                        return strcmp($a['nome'], $b['nome']);
                    });
                    
                    return $data;
                }
                
                throw new \Exception('Erro ao buscar regiões na API do IBGE');
            });
            
            return response()->json([
                'success' => true,
                'data' => $regioes
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao buscar regiões IBGE', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar regiões. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Limpa o cache das localidades
     */
    public function limparCache()
    {
        try {
            Cache::forget('ibge_estados');
            Cache::forget('ibge_regioes');
            
            // Limpar cache de municípios para todos os estados
            $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
            
            foreach ($estados as $uf) {
                Cache::forget("ibge_municipios_{$uf}");
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Cache limpo com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao limpar cache IBGE', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar cache.'
            ], 500);
        }
    }
}