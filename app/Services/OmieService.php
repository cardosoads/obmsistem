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
        $this->appKey = config('services.omie.app_key');
        $this->appSecret = config('services.omie.app_secret');
        $this->apiUrl = config('services.omie.api_url');
    }

    /**
     * Testa a conectividade com a API Omie
     * 
     * @return array
     */
    public function testConnection()
    {
        try {
            // Teste simples com ListarClientes com parâmetros mínimos
            $response = $this->makeRequest('geral/clientes/', 'ListarClientes', [
                'pagina' => 1,
                'registros_por_pagina' => 1
            ]);
            
            if ($response['success']) {
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
            Log::error('Erro ao testar conexão Omie: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Erro de conexão: ' . $e->getMessage(),
                'error' => $e->getMessage()
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
        try {
            $url = rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');
            
            $payload = [
                'call' => $call,
                'app_key' => $this->appKey,
                'app_secret' => $this->appSecret,
                'param' => [$param] // Sempre enviar como array, mesmo se vazio
            ];

            Log::info('Omie API Request', [
                'url' => $url,
                'call' => $call,
                'app_key' => $this->appKey
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Omie API Response Success', ['data' => $data]);
                
                return [
                    'success' => true,
                    'data' => $data,
                    'status_code' => $response->status()
                ];
            }

            $errorData = $response->json();
            Log::error('Omie API Error Response', [
                'status' => $response->status(),
                'body' => $errorData
            ]);

            return [
                'success' => false,
                'message' => $errorData['faultstring'] ?? 'Erro na API',
                'error' => $errorData,
                'status_code' => $response->status()
            ];

        } catch (Exception $e) {
            Log::error('Omie API Exception', [
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