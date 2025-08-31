<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OmieClientService
{
    private string $baseUrl;
    private string $appKey;
    private string $appSecret;

    public function __construct()
    {
        $this->baseUrl = config('omie.base_url', 'https://app.omie.com.br/api/v1/geral/clientes/');
        $this->appKey = config('omie.app_key');
        $this->appSecret = config('omie.app_secret');

        if (!$this->appKey || !$this->appSecret) {
            throw new Exception('Omie credentials not configured. Check your .env file.');
        }
    }

    /**
     * Lista todos os clientes com paginação
     *
     * @param int $page
     * @param int $perPage
     * @param string $importedOnly
     * @return array
     */
    public function listClients(int $page = 1, int $perPage = 50, string $importedOnly = 'N'): array
    {
        $payload = [
            'call' => 'ListarClientes',
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
            'param' => [
                [
                    'pagina' => $page,
                    'registros_por_pagina' => $perPage,
                    'apenas_importado_api' => $importedOnly
                ]
            ]
        ];

        return $this->makeRequest($payload);
    }

    /**
     * Busca cliente por ID da Omie
     *
     * @param int $clientId
     * @return array
     */
    public function getClientById(int $clientId): array
    {
        $payload = [
            'call' => 'ConsultarCliente',
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
            'param' => [
                [
                    'codigo_cliente_omie' => $clientId
                ]
            ]
        ];

        return $this->makeRequest($payload);
    }

    /**
     * Busca cliente por CNPJ/CPF
     *
     * @param string $document
     * @return array
     */
    public function getClientByDocument(string $document): array
    {
        $payload = [
            'call' => 'ConsultarCliente',
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
            'param' => [
                [
                    'cnpj_cpf' => $this->cleanDocument($document)
                ]
            ]
        ];

        return $this->makeRequest($payload);
    }

    /**
     * Busca cliente por código interno
     *
     * @param string $internalCode
     * @return array
     */
    public function getClientByInternalCode(string $internalCode): array
    {
        $payload = [
            'call' => 'ConsultarCliente',
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
            'param' => [
                [
                    'codigo_cliente_integracao' => $internalCode
                ]
            ]
        ];

        return $this->makeRequest($payload);
    }

    /**
     * Busca clientes por filtros avançados
     *
     * @param array $filters
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function searchClients(array $filters = [], int $page = 1, int $perPage = 50): array
    {
        $params = [
            'pagina' => $page,
            'registros_por_pagina' => $perPage,
            'apenas_importado_api' => 'N'
        ];

        // Adiciona filtros específicos se fornecidos
        if (isset($filters['razao_social'])) {
            $params['filtrar_por_razao_social'] = $filters['razao_social'];
        }

        if (isset($filters['nome_fantasia'])) {
            $params['filtrar_por_nome_fantasia'] = $filters['nome_fantasia'];
        }

        if (isset($filters['cidade'])) {
            $params['filtrar_por_cidade'] = $filters['cidade'];
        }

        if (isset($filters['estado'])) {
            $params['filtrar_por_estado'] = $filters['estado'];
        }

        if (isset($filters['tag'])) {
            $params['filtrar_por_tag'] = $filters['tag'];
        }

        $payload = [
            'call' => 'ListarClientes',
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
            'param' => [$params]
        ];

        return $this->makeRequest($payload);
    }

    /**
     * Busca todos os clientes (com paginação automática)
     *
     * @param int $maxPages
     * @return array
     */
    public function getAllClients(int $maxPages = 10): array
    {
        $allClients = [];
        $page = 1;
        $totalPages = 1;

        do {
            try {
                $response = $this->listClients($page, 50);
                
                if (isset($response['clientes_cadastro'])) {
                    $allClients = array_merge($allClients, $response['clientes_cadastro']);
                    $totalPages = $response['total_de_paginas'] ?? 1;
                }

                $page++;
            } catch (Exception $e) {
                Log::error('Error fetching clients page ' . $page, ['error' => $e->getMessage()]);
                break;
            }
        } while ($page <= $totalPages && $page <= $maxPages);

        return $allClients;
    }

    /**
     * Busca cliente por termo genérico (razão social, nome fantasia, CNPJ/CPF)
     *
     * @param string $searchTerm
     * @return array
     */
    public function searchClientsByTerm(string $searchTerm): array
    {
        $searchTerm = trim($searchTerm);
        
        // Se o termo parece ser um documento (apenas números)
        if (preg_match('/^\d+$/', str_replace(['.', '/', '-'], '', $searchTerm))) {
            try {
                $result = $this->getClientByDocument($searchTerm);
                return isset($result['codigo_cliente_omie']) ? [$result] : [];
            } catch (Exception $e) {
                Log::info('Document search failed, trying text search', ['term' => $searchTerm]);
            }
        }

        // Busca por razão social
        try {
            $response = $this->searchClients(['razao_social' => $searchTerm]);
            if (isset($response['clientes_cadastro']) && count($response['clientes_cadastro']) > 0) {
                return $response['clientes_cadastro'];
            }
        } catch (Exception $e) {
            Log::info('Razao social search failed', ['term' => $searchTerm]);
        }

        // Busca por nome fantasia
        try {
            $response = $this->searchClients(['nome_fantasia' => $searchTerm]);
            if (isset($response['clientes_cadastro']) && count($response['clientes_cadastro']) > 0) {
                return $response['clientes_cadastro'];
            }
        } catch (Exception $e) {
            Log::info('Nome fantasia search failed', ['term' => $searchTerm]);
        }

        return [];
    }

    /**
     * Executa a requisição HTTP para a API da Omie
     *
     * @param array $payload
     * @return array
     * @throws Exception
     */
    private function makeRequest(array $payload): array
    {
        try {
            Log::info('Omie API Request', ['call' => $payload['call']]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl, $payload);

            if (!$response->successful()) {
                throw new Exception('HTTP Error: ' . $response->status() . ' - ' . $response->body());
            }

            $data = $response->json();

            // Verifica se há erro na resposta da API
            if (isset($data['faultstring'])) {
                throw new Exception('Omie API Error: ' . $data['faultstring']);
            }

            Log::info('Omie API Response Success', ['call' => $payload['call']]);
            
            return $data;

        } catch (Exception $e) {
            Log::error('Omie API Request Failed', [
                'call' => $payload['call'],
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);
            
            throw $e;
        }
    }

    /**
     * Remove formatação de documentos (CNPJ/CPF)
     *
     * @param string $document
     * @return string
     */
    private function cleanDocument(string $document): string
    {
        return preg_replace('/[^0-9]/', '', $document);
    }

    /**
     * Formata documento (adiciona pontuação)
     *
     * @param string $document
     * @return string
     */
    public static function formatDocument(string $document): string
    {
        $document = preg_replace('/[^0-9]/', '', $document);
        
        if (strlen($document) === 11) {
            // CPF
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $document);
        } elseif (strlen($document) === 14) {
            // CNPJ
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $document);
        }
        
        return $document;
    }

    /**
     * Valida se o documento é válido (CPF ou CNPJ)
     *
     * @param string $document
     * @return bool
     */
    public static function isValidDocument(string $document): bool
    {
        $document = preg_replace('/[^0-9]/', '', $document);
        
        return strlen($document) === 11 || strlen($document) === 14;
    }
}