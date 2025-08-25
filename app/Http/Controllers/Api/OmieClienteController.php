<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OmieApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OmieClienteController extends Controller
{
    private OmieApiService $omieService;

    public function __construct(OmieApiService $omieService)
    {
        $this->omieService = $omieService;
    }

    /**
     * Buscar clientes diretamente da API OMIE
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);

        try {
            // Buscar diretamente na API OMIE
            $clientesOmie = $this->omieService->getClientes([
                'pagina' => $page,
                'registros_por_pagina' => $perPage
            ]);
            
            // Aplicar filtro se necessário
            if ($search) {
                $clientesOmie = array_filter($clientesOmie, function ($cliente) use ($search) {
                    $searchLower = strtolower($search);
                    
                    // Buscar por ID numérico (código_cliente_omie)
                    if (is_numeric($search)) {
                        return ($cliente['codigo_cliente_omie'] ?? '') == $search;
                    }
                    
                    // Buscar por nome, nome fantasia ou CNPJ/CPF
                    return str_contains(strtolower($cliente['razao_social'] ?? ''), $searchLower) ||
                           str_contains(strtolower($cliente['nome_fantasia'] ?? ''), $searchLower) ||
                           str_contains($cliente['cnpj_cpf'] ?? '', $search);
                });
                // Reindexar o array para garantir que seja um array sequencial
                $clientesOmie = array_values($clientesOmie);
            }

            // Formatar dados da API OMIE
            $clientesFormatados = array_map(function ($cliente) {
                return [
                    'id' => $cliente['codigo_cliente_omie'] ?? null,
                    'omie_id' => $cliente['codigo_cliente_omie'] ?? null,
                    'nome' => $cliente['razao_social'] ?? $cliente['nome_fantasia'] ?? 'N/A',
                    'nome_fantasia' => $cliente['nome_fantasia'] ?? '',
                    'razao_social' => $cliente['razao_social'] ?? '',
                    'documento' => $cliente['cnpj_cpf'] ?? '',
                    'email' => $cliente['email'] ?? '',
                    'telefone' => ($cliente['telefone1_ddd'] ?? '') . ($cliente['telefone1_numero'] ?? ''),
                    'cidade' => $cliente['cidade'] ?? '',
                    'estado' => $cliente['estado'] ?? '',
                    'ativo' => ($cliente['inativo'] ?? 'N') === 'N'
                ];
            }, $clientesOmie);

            return response()->json([
                'success' => true,
                'data' => array_values($clientesFormatados),
                'total' => count($clientesFormatados)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar clientes: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Obter cliente específico
     */
    public function show(int $omieId): JsonResponse
    {
        try {
            $cliente = $this->omieService->getCliente($omieId);

            if (!$cliente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente não encontrado'
                ], 404);
            }

            $clienteFormatado = [
                'id' => $cliente['codigo_cliente_omie'] ?? null,
                'omie_id' => $cliente['codigo_cliente_omie'] ?? null,
                'nome' => $cliente['razao_social'] ?? $cliente['nome_fantasia'] ?? 'N/A',
                'nome_fantasia' => $cliente['nome_fantasia'] ?? '',
                'razao_social' => $cliente['razao_social'] ?? '',
                'documento' => $cliente['cnpj_cpf'] ?? '',
                'email' => $cliente['email'] ?? '',
                'telefone' => $cliente['telefone1_ddd'] . $cliente['telefone1_numero'] ?? '',
                'endereco' => $cliente['endereco'] ?? '',
                'cidade' => $cliente['cidade'] ?? '',
                'estado' => $cliente['estado'] ?? '',
                'cep' => $cliente['cep'] ?? '',
                'ativo' => ($cliente['inativo'] ?? 'N') === 'N'
            ];

            return response()->json([
                'success' => true,
                'data' => $clienteFormatado
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpar cache de clientes
     */
    public function clearCache(): JsonResponse
    {
        try {
            $this->omieService->clearClientesCache();

            return response()->json([
                'success' => true,
                'message' => 'Cache de clientes limpo com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar cache: ' . $e->getMessage()
            ], 500);
        }
    }
}