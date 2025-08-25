<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OmieService
{
    private $appKey;
    private $appSecret;
    private $apiUrl;

    public function __construct()
    {
        // Carrega as configurações do banco de dados
        $this->appKey = \App\Models\Setting::get('omie_app_key');
        $this->appSecret = \App\Models\Setting::get('omie_app_secret');
        $this->apiUrl = config('services.omie.api_url', 'https://app.omie.com.br/api/v1/');
    }

    /**
     * Testa a conectividade com a API Omie
     * 
     * @return array
     */
    public function testConnection()
    {
        try {
            // Verifica se as chaves estão configuradas
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
            
            Log::info('Iniciando teste de conexão com API Omie', [
                'api_url' => $this->apiUrl,
                'app_key_length' => strlen($this->appKey)
            ]);
            
            // Teste simples com ListarClientes com parâmetros mínimos
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
            
            Log::error('Falha no teste de conexão Omie', [
                'response' => $response,
                'status_code' => $response['status_code'] ?? null
            ]);
            
            return [
                'success' => false,
                'message' => 'Falha na conexão: ' . ($response['message'] ?? 'Erro desconhecido'),
                'error' => $response['error'] ?? null,
                'debug_info' => [
                    'status_code' => $response['status_code'] ?? null,
                    'api_url' => $this->apiUrl
                ]
            ];
            
        } catch (Exception $e) {
            Log::error('Exceção ao testar conexão Omie', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Erro de conexão: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'debug_info' => [
                    'exception_type' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    /**
     * Faz uma requisição para a API Omie
     * 
     * @param string $endpoint
     * @param string $call
     * @param array $param
     * @return array
     */
    private function makeRequest($endpoint, $call, $param = [])
    {
        $startTime = microtime(true);
        
        try {
            $url = rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');
            
            $payload = [
                'call' => $call,
                'app_key' => $this->appKey,
                'app_secret' => $this->appSecret,
                'param' => $param // Enviar parâmetros diretamente, não como array aninhado
            ];

            Log::info('Omie API Request iniciada', [
                'url' => $url,
                'call' => $call,
                'app_key_length' => strlen($this->appKey),
                'payload_size' => strlen(json_encode($payload)),
                'php_version' => PHP_VERSION,
                'curl_version' => curl_version()['version'] ?? 'N/A'
            ]);

            // Teste de conectividade básica antes da requisição
            $parsedUrl = parse_url($url);
            $host = $parsedUrl['host'] ?? '';
            $port = $parsedUrl['port'] ?? ($parsedUrl['scheme'] === 'https' ? 443 : 80);
            
            if (!empty($host)) {
                $connection = @fsockopen($host, $port, $errno, $errstr, 5);
                if (!$connection) {
                    Log::error('Falha na conectividade básica com API Omie', [
                        'host' => $host,
                        'port' => $port,
                        'errno' => $errno,
                        'errstr' => $errstr
                    ]);
                } else {
                    fclose($connection);
                    Log::info('Conectividade básica com API Omie OK', [
                        'host' => $host,
                        'port' => $port
                    ]);
                }
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Laravel-OBM-System/1.0'
                ])
                ->post($url, $payload);

            $duration = round((microtime(true) - $startTime) * 1000, 2);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Omie API Response Success', [
                    'status_code' => $response->status(),
                    'duration_ms' => $duration,
                    'response_size' => strlen($response->body()),
                    'data_keys' => is_array($data) ? array_keys($data) : 'not_array'
                ]);
                
                return [
                    'success' => true,
                    'data' => $data,
                    'status_code' => $response->status(),
                    'duration_ms' => $duration
                ];
            }

            $errorData = $response->json();
            Log::error('Omie API Error Response', [
                'status_code' => $response->status(),
                'duration_ms' => $duration,
                'error_data' => $errorData,
                'response_headers' => $response->headers()
            ]);

            return [
                'success' => false,
                'message' => $errorData['faultstring'] ?? 'Erro na API',
                'error' => $errorData,
                'status_code' => $response->status(),
                'duration_ms' => $duration
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Omie API Connection Exception', [
                'message' => $e->getMessage(),
                'duration_ms' => $duration,
                'url' => $url ?? 'N/A',
                'exception_type' => 'ConnectionException',
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Erro de conexão com a API Omie: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'error_type' => 'connection',
                'duration_ms' => $duration
            ];
            
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Omie API Request Exception', [
                'message' => $e->getMessage(),
                'duration_ms' => $duration,
                'url' => $url ?? 'N/A',
                'exception_type' => 'RequestException',
                'response_status' => $e->response ? $e->response->status() : 'N/A',
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Erro na requisição para API Omie: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'error_type' => 'request',
                'status_code' => $e->response ? $e->response->status() : null,
                'duration_ms' => $duration
            ];
            
        } catch (Exception $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Omie API General Exception', [
                'message' => $e->getMessage(),
                'duration_ms' => $duration,
                'url' => $url ?? 'N/A',
                'exception_type' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Erro inesperado: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'error_type' => 'general',
                'exception_type' => get_class($e),
                'duration_ms' => $duration
            ];
        }
    }

    /**
     * Lista empresas cadastradas
     * 
     * @return array
     */
    public function listarEmpresas()
    {
        return $this->makeRequest('geral/empresas/', 'ListarEmpresas', []);
    }

    /**
     * Lista clientes
     * 
     * @param array $filtros
     * @return array
     */
    public function listarClientes($filtros = [])
    {
        return $this->makeRequest('geral/clientes/', 'ListarClientes', $filtros);
    }

    /**
     * Lista produtos
     * 
     * @param array $filtros
     * @return array
     */
    public function listarProdutos($filtros = [])
    {
        return $this->makeRequest('geral/produtos/', 'ListarProdutos', $filtros);
    }

    /**
     * Lista fornecedores
     * 
     * @param array $filtros
     * @return array
     */
    public function listarFornecedores($filtros = [])
    {
        $defaultFiltros = [
            'pagina' => 1,
            'registros_por_pagina' => 50
        ];
        
        $filtros = array_merge($defaultFiltros, $filtros);
        
        return $this->makeRequest('geral/fornecedor/', 'ListarFornecedores', $filtros);
    }

    /**
     * Consulta dados específicos de um cliente
     * 
     * @param string $omieId
     * @return array
     */
    public function consultarCliente($omieId)
    {
        return $this->makeRequest('geral/clientes/', 'ConsultarCliente', [
            'codigo_cliente_omie' => $omieId
        ]);
    }

    /**
     * Consulta dados específicos de um fornecedor
     * 
     * @param string $omieId
     * @return array
     */
    public function consultarFornecedor($omieId)
    {
        return $this->makeRequest('geral/fornecedor/', 'ConsultarFornecedor', [
            'codigo_fornecedor_omie' => $omieId
        ]);
    }

    /**
     * Lista clientes com paginação melhorada
     * 
     * @param array $filtros
     * @return array
     */
    public function listarClientesPaginado($filtros = [])
    {
        $defaultFiltros = [
            'pagina' => 1,
            'registros_por_pagina' => 50
        ];
        
        $filtros = array_merge($defaultFiltros, $filtros);
        
        return $this->makeRequest('geral/clientes/', 'ListarClientes', $filtros);
    }
}