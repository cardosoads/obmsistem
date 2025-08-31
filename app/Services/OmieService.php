<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class OmieService
{
    private $appKey;
    private $appSecret;
    private $apiUrl;

    public function __construct()
    {
        // Carrega as configurações do banco de dados ou variáveis de ambiente
        $this->appKey = \App\Models\Setting::get('omie_app_key') ?? env('OMIE_APP_KEY');
        $this->appSecret = \App\Models\Setting::get('omie_app_secret') ?? env('OMIE_APP_SECRET');
        $this->apiUrl = config('services.omie.api_url', 'https://app.omie.com.br/api/v1/');
    }

    /**
     * Testa a conectividade com a API Omie
     */
    public function testConnection(): array
    {
        try {
            if (empty($this->appKey) || empty($this->appSecret)) {
                Log::warning('Tentativa de teste de conexão Omie sem chaves configuradas');
                return [
                    'success' => false,
                    'message' => 'A chave de acesso não está preenchida ou não é válida. Configure as chaves da API Omie nas configurações do sistema.',
                    'error' => 'Chaves da API não configuradas',
                    'debug_info' => [
                        'app_key_configured' => !empty($this->appKey),
                        'app_secret_configured' => !empty($this->appSecret),
                        'api_url' => $this->apiUrl
                    ]
                ];
            }
            
            Log::info('Iniciando teste de conexão com API Omie');
            
            // Teste simples com ListarClientes
            $response = $this->makeRequest('geral/clientes/', 'ListarClientes', [
                'pagina' => 1,
                'registros_por_pagina' => 1
            ]);
            
            if ($response['success']) {
                Log::info('Teste de conexão Omie bem-sucedido');
                return [
                    'success' => true,
                    'message' => 'Conexão estabelecida com sucesso!',
                    'data' => $response['data']
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Falha na conexão: ' . ($response['message'] ?? 'Erro desconhecido'),
                'error' => $response['error'] ?? null
            ];
            
        } catch (Exception $e) {
            Log::error('Exceção ao testar conexão Omie', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Erro de conexão: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Lista todos os clientes com paginação
     */
    public function listClients(int $page = 1, int $perPage = 50): array
    {
        $cacheKey = "omie_clients_list_{$page}_{$perPage}";
        
        return Cache::remember($cacheKey, 300, function () use ($page, $perPage) {
            $response = $this->makeRequest('geral/clientes/', 'ListarClientes', [
                'pagina' => $page,
                'registros_por_pagina' => $perPage,
                'apenas_importado_api' => 'N'
            ]);
            
            return $response['success'] ? $response['data'] : [];
        });
    }

    /**
     * Busca cliente por ID Omie
     */
    public function getClientById(int $omieId): ?array
    {
        $cacheKey = "omie_client_{$omieId}";
        
        return Cache::remember($cacheKey, 600, function () use ($omieId) {
            try {
                $response = $this->makeRequest('geral/clientes/', 'ConsultarCliente', [
                    'codigo_cliente_omie' => $omieId
                ]);
                
                return $response['success'] ? $response['data'] : null;
            } catch (Exception $e) {
                Log::warning('Cliente não encontrado', ['omie_id' => $omieId, 'error' => $e->getMessage()]);
                return null;
            }
        });
    }

    /**
     * Busca cliente por documento (CPF/CNPJ)
     */
    public function getClientByDocument(string $document): ?array
    {
        $cleanDocument = $this->cleanDocument($document);
        
        if (!$this->isValidDocument($cleanDocument)) {
            return null;
        }

        $cacheKey = "omie_client_doc_{$cleanDocument}";
        
        return Cache::remember($cacheKey, 600, function () use ($cleanDocument) {
            try {
                $response = $this->makeRequest('geral/clientes/', 'ConsultarCliente', [
                    'cnpj_cpf' => $cleanDocument
                ]);
                
                return $response['success'] ? $response['data'] : null;
            } catch (Exception $e) {
                Log::warning('Cliente não encontrado por documento', ['document' => $cleanDocument, 'error' => $e->getMessage()]);
                return null;
            }
        });
    }

    /**
     * Busca clientes por termo (documento, razão social ou nome fantasia)
     * Corrigido conforme documentação da API Omie
     */
    public function searchClientsByTerm(string $term): array
    {
        if (empty($term) || strlen($term) < 2) {
            return [];
        }

        $cacheKey = "omie_clients_term_" . md5($term);
        
        return Cache::remember($cacheKey, 300, function () use ($term) {
            // Primeiro tenta buscar por documento se o termo parece ser um CPF/CNPJ
            $cleanTerm = $this->cleanDocument($term);
            if ($this->isValidDocument($cleanTerm)) {
                $client = $this->getClientByDocument($cleanTerm);
                if ($client) {
                    return [$client];
                }
            }

            // Busca usando filtros corretos conforme documentação da API
            $results = [];
            
            // Busca por razão social usando clientesFiltro
            try {
                $response = $this->makeRequest('geral/clientes/', 'ListarClientes', [
                    'pagina' => 1,
                    'registros_por_pagina' => 20,
                    'apenas_importado_api' => 'N',
                    'clientesFiltro' => [
                        'razao_social' => $term
                    ]
                ]);
                
                if ($response['success'] && isset($response['data']['clientes_cadastro'])) {
                    $results = array_merge($results, $response['data']['clientes_cadastro']);
                }
            } catch (Exception $e) {
                Log::warning('Erro na busca por razão social', ['term' => $term, 'error' => $e->getMessage()]);
            }
            
            // Busca por nome fantasia se não encontrou resultados suficientes
            if (count($results) < 10) {
                try {
                    $response = $this->makeRequest('geral/clientes/', 'ListarClientes', [
                        'pagina' => 1,
                        'registros_por_pagina' => 20,
                        'apenas_importado_api' => 'N',
                        'clientesFiltro' => [
                            'nome_fantasia' => $term
                        ]
                    ]);
                    
                    if ($response['success'] && isset($response['data']['clientes_cadastro'])) {
                        $newResults = $response['data']['clientes_cadastro'];
                        // Remove duplicatas baseado no código do cliente
                        foreach ($newResults as $newClient) {
                            $exists = false;
                            foreach ($results as $existingClient) {
                                if (($existingClient['codigo_cliente_omie'] ?? '') === ($newClient['codigo_cliente_omie'] ?? '')) {
                                    $exists = true;
                                    break;
                                }
                            }
                            if (!$exists) {
                                $results[] = $newClient;
                            }
                        }
                    }
                } catch (Exception $e) {
                    Log::warning('Erro na busca por nome fantasia', ['term' => $term, 'error' => $e->getMessage()]);
                }
            }
            
            // Limita os resultados para evitar sobrecarga
            return array_slice($results, 0, 20);
        });
    }

    /**
     * Busca clientes com filtros locais (para compatibilidade)
     */
    public function searchClientes(string $search): array
    {
        if (empty($search)) {
            return [];
        }

        // Usa o método otimizado de busca por termo
        return $this->searchClientsByTerm($search);
    }

    /**
     * Lista clientes (método genérico)
     */
    public function listarClientes(array $filtros = []): array
    {
        $defaultFiltros = [
            'pagina' => 1,
            'registros_por_pagina' => 50,
            'apenas_importado_api' => 'N'
        ];
        
        $filtros = array_merge($defaultFiltros, $filtros);
        
        $response = $this->makeRequest('geral/clientes/', 'ListarClientes', $filtros);
        return $response['success'] ? $response['data'] : [];
    }

    /**
     * Lista clientes com paginação (método específico para o controller)
     */
    public function listarClientesPaginado(array $filtros = []): array
    {
        $defaultFiltros = [
            'pagina' => 1,
            'registros_por_pagina' => 20,
            'apenas_importado_api' => 'N'
        ];
        
        $filtros = array_merge($defaultFiltros, $filtros);
        
        $response = $this->makeRequest('geral/clientes/', 'ListarClientes', $filtros);
        
        return $response;
    }

    /**
     * Consulta dados específicos de um cliente
     */
    public function consultarCliente($omieId): array
    {
        $response = $this->makeRequest('geral/clientes/', 'ConsultarCliente', [
            'codigo_cliente_omie' => $omieId
        ]);
        
        return $response['success'] ? $response['data'] : [];
    }

    /**
     * Lista fornecedores (via API de clientes)
     */
    public function listarFornecedores(array $filtros = []): array
    {
        $defaultFiltros = [
            'pagina' => 1,
            'registros_por_pagina' => 50
        ];
        
        $filtros = array_merge($defaultFiltros, $filtros);
        
        $response = $this->makeRequest('geral/clientes/', 'ListarClientes', $filtros);
        return $response['success'] ? $response['data'] : [];
    }

    /**
     * Busca fornecedores por termo (codigo_cliente_omie, documento, razão social ou nome fantasia)
     * @param string $term Termo de busca
     * @param string $type Tipo de busca: 'codigo', 'nome' ou vazio para busca geral
     */
    public function searchSuppliersByTerm(string $term, string $type = ''): array
    {
        // Validação específica baseada no tipo
        if ($type === 'codigo') {
            if (empty($term) || strlen($term) < 1) {
                return [];
            }
        } else {
            if (empty($term) || strlen($term) < 2) {
                return [];
            }
        }

        $cacheKey = "omie_suppliers_term_" . md5($term . '_' . $type);
        
        return Cache::remember($cacheKey, 300, function () use ($term, $type) {
            $results = [];
            
            // Lógica específica baseada no tipo de busca
            if ($type === 'codigo') {
                // Busca específica por código - otimizada
                return $this->searchByCode($term);
            } elseif ($type === 'nome') {
                // Busca específica por nome - otimizada
                return $this->searchByName($term);
            }
            
            // Busca geral (mantém lógica original)
            // Busca por codigo_cliente_omie se o termo for numérico
            // Busca em todos os clientes, não apenas fornecedores
            if (is_numeric($term)) {
                try {
                    // Primeiro tenta buscar diretamente pelo ID
                    $client = $this->getClientById((int)$term);
                    if ($client) {
                        $results[] = $client;
                    }
                } catch (\Exception $e) {
                    \Log::warning('Erro na busca direta por codigo_cliente_omie', [
                        'term' => $term,
                        'error' => $e->getMessage()
                    ]);
                    
                    // Se falhar, tenta busca com filtro de fornecedor
                    try {
                        $response = $this->makeRequest('geral/clientes/', 'ListarClientes', [
                            'pagina' => 1,
                            'registros_por_pagina' => 20,
                            'apenas_importado_api' => 'N',
                            'fornecedorFiltro' => [
                                'codigo_cliente_omie' => (int)$term
                            ]
                        ]);
                        
                        if ($response['success'] && isset($response['data']['clientes_cadastro'])) {
                            $results = array_merge($results, $response['data']['clientes_cadastro']);
                        }
                    } catch (\Exception $e2) {
                        \Log::warning('Erro na busca de fornecedores por codigo_cliente_omie com filtro', [
                            'term' => $term,
                            'error' => $e2->getMessage()
                        ]);
                    }
                }
            }
            
            // Busca por razão social usando clientesFiltro (mais amplo que fornecedorFiltro)
            if (count($results) < 5) {
                try {
                    $response = $this->makeRequest('geral/clientes/', 'ListarClientes', [
                        'pagina' => 1,
                        'registros_por_pagina' => 20,
                        'apenas_importado_api' => 'N',
                        'clientesFiltro' => [
                            'razao_social' => $term
                        ]
                    ]);
                    
                    if ($response['success'] && isset($response['data']['clientes_cadastro'])) {
                        // Evitar duplicatas baseado no codigo_cliente_omie
                        $existingIds = array_column($results, 'codigo_cliente_omie');
                        $newResults = array_filter($response['data']['clientes_cadastro'], function($item) use ($existingIds) {
                            return !in_array($item['codigo_cliente_omie'] ?? null, $existingIds);
                        });
                        $results = array_merge($results, $newResults);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Erro na busca de fornecedores por razão social', [
                        'term' => $term,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Busca por nome fantasia se não encontrou resultados suficientes
            if (count($results) < 5) {
                try {
                    $response = $this->makeRequest('geral/clientes/', 'ListarClientes', [
                        'pagina' => 1,
                        'registros_por_pagina' => 20,
                        'apenas_importado_api' => 'N',
                        'clientesFiltro' => [
                            'nome_fantasia' => $term
                        ]
                    ]);
                    
                    if ($response['success'] && isset($response['data']['clientes_cadastro'])) {
                        // Evitar duplicatas baseado no codigo_cliente_omie
                        $existingIds = array_column($results, 'codigo_cliente_omie');
                        $newResults = array_filter($response['data']['clientes_cadastro'], function($item) use ($existingIds) {
                            return !in_array($item['codigo_cliente_omie'] ?? null, $existingIds);
                        });
                        $results = array_merge($results, $newResults);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Erro na busca de fornecedores por nome fantasia', [
                        'term' => $term,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Retorna no formato padrão da API Omie
            return [
                'clientes_cadastro' => $results,
                'total_de_registros' => count($results),
                'total_de_paginas' => 1,
                'registros_por_pagina' => count($results),
                'pagina_atual' => 1
            ];
        });
    }

    /**
     * Lista produtos
     */
    public function listarProdutos(array $filtros = []): array
    {
        $response = $this->makeRequest('geral/produtos/', 'ListarProdutos', $filtros);
        return $response['success'] ? $response['data'] : [];
    }

    /**
     * Lista empresas cadastradas
     */
    public function listarEmpresas(): array
    {
        $response = $this->makeRequest('geral/empresas/', 'ListarEmpresas', []);
        return $response['success'] ? $response['data'] : [];
    }

    /**
     * Limpa cache de clientes
     */
    public function clearCache(): void
    {
        try {
            $keys = Cache::getRedis()->keys('*omie_client*');
            foreach ($keys as $key) {
                Cache::forget(str_replace(config('cache.prefix') . ':', '', $key));
            }
        } catch (Exception $e) {
            Log::warning('Erro ao limpar cache de clientes', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Método genérico para fazer requisições à API Omie
     */
    private function makeRequest(string $endpoint, string $call, array $param = []): array
    {
        $startTime = microtime(true);
        
        try {
            // Verifica se as chaves estão configuradas
            if (empty($this->appKey) || empty($this->appSecret)) {
                throw new Exception('Chaves da API Omie não configuradas');
            }

            $url = rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');
            
            $payload = [
                'call' => $call,
                'app_key' => $this->appKey,
                'app_secret' => $this->appSecret,
                'param' => [$param] // Array aninhado conforme estrutura da API
            ];

            $response = Http::timeout(30) // Aumentado para 30 segundos
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Laravel-OBM-System/1.0'
                ])
                ->post($url, $payload);

            $duration = round((microtime(true) - $startTime) * 1000, 2);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Omie API Response Success', [
                    'call' => $call,
                    'duration_ms' => $duration,
                    'status_code' => $response->status()
                ]);
                
                return [
                    'success' => true,
                    'data' => $data,
                    'status_code' => $response->status(),
                    'duration_ms' => $duration
                ];
            }

            $errorData = $response->json();
            
            // Tratamento específico para erro de "não existem registros"
            $faultString = $errorData['faultstring'] ?? '';
            $faultCode = $errorData['faultcode'] ?? '';
            
            if (strpos($faultString, 'Não existem registros para a página') !== false || 
                $faultCode === 'SOAP-ENV:Client-5113') {
                Log::info('Omie API No Records Found', [
                    'call' => $call,
                    'duration_ms' => $duration,
                    'status_code' => $response->status()
                ]);
                
                return [
                    'success' => true, // Tratamos como sucesso, mas sem dados
                    'data' => ['clientes_cadastro' => []], // Retorna estrutura vazia
                    'status_code' => 200,
                    'duration_ms' => $duration,
                    'no_records' => true
                ];
            }
            
            Log::error('Omie API Error Response', [
                'call' => $call,
                'status_code' => $response->status(),
                'duration_ms' => $duration,
                'error_data' => $errorData
            ]);

            return [
                'success' => false,
                'message' => $faultString ?: 'Erro na API',
                'error' => $errorData,
                'status_code' => $response->status(),
                'duration_ms' => $duration
            ];

        } catch (Exception $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Omie API Exception', [
                'call' => $call ?? 'unknown',
                'message' => $e->getMessage(),
                'duration_ms' => $duration,
                'exception_type' => get_class($e)
            ]);

            return [
                'success' => false,
                'message' => 'Erro na API Omie: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'error_type' => 'exception',
                'duration_ms' => $duration
            ];
        }
    }

    /**
     * Remove formatação de documentos (CPF/CNPJ)
     */
    private function cleanDocument(string $document): string
    {
        return preg_replace('/[^0-9]/', '', $document);
    }

    /**
     * Formata documento (CPF/CNPJ)
     */
    public function formatDocument(string $document): string
    {
        $clean = $this->cleanDocument($document);
        
        if (strlen($clean) === 11) {
            // CPF: 000.000.000-00
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $clean);
        } elseif (strlen($clean) === 14) {
            // CNPJ: 00.000.000/0000-00
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $clean);
        }
        
        return $document;
    }

    /**
     * Busca otimizada por código do cliente
     */
    private function searchByCode(string $code): array
    {
        $results = [];
        
        // Se for numérico, busca por codigo_cliente_omie
        if (is_numeric($code)) {
            try {
                // Primeiro tenta buscar diretamente pelo ID
                $client = $this->getClientById((int)$code);
                if ($client) {
                    $results[] = $client;
                }
            } catch (\Exception $e) {
                \Log::warning('Erro na busca direta por codigo_cliente_omie', [
                    'code' => $code,
                    'error' => $e->getMessage()
                ]);
                
                // Se falhar, tenta busca com filtro de fornecedor
                try {
                    $response = $this->makeRequest('geral/clientes/', 'ListarClientes', [
                        'pagina' => 1,
                        'registros_por_pagina' => 20,
                        'apenas_importado_api' => 'N',
                        'fornecedorFiltro' => [
                            'codigo_cliente_omie' => (int)$code
                        ]
                    ]);
                    
                    if ($response['success'] && isset($response['data']['clientes_cadastro'])) {
                        $results = array_merge($results, $response['data']['clientes_cadastro']);
                    }
                } catch (\Exception $e2) {
                    \Log::warning('Erro na busca de fornecedores por codigo_cliente_omie com filtro', [
                        'code' => $code,
                        'error' => $e2->getMessage()
                    ]);
                }
            }
        } else {
            // Se não for numérico, busca por código interno ou outros campos de código
            try {
                $response = $this->makeRequest('geral/clientes/', 'ListarClientes', [
                    'pagina' => 1,
                    'registros_por_pagina' => 20,
                    'apenas_importado_api' => 'N',
                    'clientesFiltro' => [
                        'codigo_cliente_integracao' => $code
                    ]
                ]);
                
                if ($response['success'] && isset($response['data']['clientes_cadastro'])) {
                    $results = array_merge($results, $response['data']['clientes_cadastro']);
                }
            } catch (\Exception $e) {
                \Log::warning('Erro na busca por código de integração', [
                    'code' => $code,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $results;
    }

    /**
     * Busca otimizada por nome (razão social ou nome fantasia)
     */
    private function searchByName(string $name): array
    {
        $results = [];
        
        // Busca por razão social
        try {
            $response = $this->makeRequest('geral/clientes/', 'ListarClientes', [
                'pagina' => 1,
                'registros_por_pagina' => 15,
                'apenas_importado_api' => 'N',
                'clientesFiltro' => [
                    'razao_social' => $name
                ]
            ]);
            
            if ($response['success'] && isset($response['data']['clientes_cadastro'])) {
                $results = array_merge($results, $response['data']['clientes_cadastro']);
            }
        } catch (\Exception $e) {
            \Log::warning('Erro na busca por razão social', [
                'name' => $name,
                'error' => $e->getMessage()
            ]);
        }
        
        // Busca por nome fantasia se ainda temos espaço para mais resultados
        if (count($results) < 10) {
            try {
                $response = $this->makeRequest('geral/clientes/', 'ListarClientes', [
                    'pagina' => 1,
                    'registros_por_pagina' => 10,
                    'apenas_importado_api' => 'N',
                    'clientesFiltro' => [
                        'nome_fantasia' => $name
                    ]
                ]);
                
                if ($response['success'] && isset($response['data']['clientes_cadastro'])) {
                    // Evitar duplicatas baseado no codigo_cliente_omie
                    $existingIds = array_column($results, 'codigo_cliente_omie');
                    $newResults = array_filter($response['data']['clientes_cadastro'], function($item) use ($existingIds) {
                        return !in_array($item['codigo_cliente_omie'], $existingIds);
                    });
                    $results = array_merge($results, $newResults);
                }
            } catch (\Exception $e) {
                \Log::warning('Erro na busca por nome fantasia', [
                    'name' => $name,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $results;
     }

     /**
      * Valida documento (CPF/CNPJ)
      */
    private function isValidDocument(string $document): bool
    {
        $clean = $this->cleanDocument($document);
        return strlen($clean) === 11 || strlen($clean) === 14;
    }
}