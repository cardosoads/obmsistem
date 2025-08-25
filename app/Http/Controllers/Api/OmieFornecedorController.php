<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OmieService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OmieFornecedorController extends Controller
{
    private OmieService $omieService;

    public function __construct(OmieService $omieService)
    {
        $this->omieService = $omieService;
    }

    /**
     * Buscar fornecedores por nome
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);

        try {
            // Buscar fornecedores na API OMIE
            $response = $this->omieService->listarFornecedores([
                'pagina' => $page,
                'registros_por_pagina' => $perPage
            ]);
            
            if (!$response['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $response['message'] ?? 'Erro ao buscar fornecedores'
                ], 400);
            }
            
            $fornecedores = $response['data']['fornecedor_cadastro'] ?? [];
            
            // Aplicar filtro se necessário
            if ($search) {
                $fornecedores = array_filter($fornecedores, function ($fornecedor) use ($search) {
                    $searchLower = strtolower($search);
                    
                    return str_contains(strtolower($fornecedor['razao_social'] ?? ''), $searchLower) ||
                           str_contains(strtolower($fornecedor['nome_fantasia'] ?? ''), $searchLower) ||
                           str_contains($fornecedor['cnpj_cpf'] ?? '', $search);
                });
                // Reindexar o array para garantir que seja um array sequencial
                $fornecedores = array_values($fornecedores);
            }

            // Formatar dados da API OMIE
            $fornecedoresFormatados = array_map(function ($fornecedor) {
                return [
                    'id' => $fornecedor['codigo_fornecedor_omie'] ?? null,
                    'omie_id' => $fornecedor['codigo_fornecedor_omie'] ?? null,
                    'nome' => $fornecedor['razao_social'] ?? $fornecedor['nome_fantasia'] ?? 'N/A',
                    'razao_social' => $fornecedor['razao_social'] ?? 'N/A',
                    'nome_fantasia' => $fornecedor['nome_fantasia'] ?? '',
                    'cnpj_cpf' => $fornecedor['cnpj_cpf'] ?? '',
                    'email' => $fornecedor['email'] ?? '',
                    'telefone1_ddd' => $fornecedor['telefone1_ddd'] ?? '',
                    'telefone1_numero' => $fornecedor['telefone1_numero'] ?? ''
                ];
            }, $fornecedores);

            return response()->json([
                'success' => true,
                'data' => $fornecedoresFormatados,
                'total' => count($fornecedoresFormatados)
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar fornecedores OMIE', [
                'search' => $search,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Consultar fornecedor por ID OMIE
     */
    public function show(string $omieId): JsonResponse
    {
        try {
            Log::info('Consultando fornecedor OMIE', ['omie_id' => $omieId]);
            
            $response = $this->omieService->consultarFornecedor($omieId);
            
            if ($response['success'] && $response['data']) {
                $fornecedor = $response['data'];
                Log::info('Fornecedor encontrado', ['fornecedor' => $fornecedor]);
                
                // Formatar dados do fornecedor
                $fornecedorFormatado = [
                    'id' => $fornecedor['codigo_fornecedor_omie'] ?? null,
                    'omie_id' => $fornecedor['codigo_fornecedor_omie'] ?? null,
                    'nome' => $fornecedor['razao_social'] ?? $fornecedor['nome_fantasia'] ?? 'N/A',
                    'razao_social' => $fornecedor['razao_social'] ?? 'N/A',
                    'nome_fantasia' => $fornecedor['nome_fantasia'] ?? '',
                    'cnpj_cpf' => $fornecedor['cnpj_cpf'] ?? '',
                    'email' => $fornecedor['email'] ?? '',
                    'telefone1_ddd' => $fornecedor['telefone1_ddd'] ?? '',
                    'telefone1_numero' => $fornecedor['telefone1_numero'] ?? ''
                ];
                
                return response()->json([
                'success' => true,
                'data' => $fornecedorFormatado
            ]);
            }
            
            Log::warning('Fornecedor não encontrado', ['omie_id' => $omieId]);
            return response()->json([
                'success' => false,
                'message' => 'Fornecedor não encontrado'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Erro ao consultar fornecedor OMIE', [
                'omie_id' => $omieId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }
}