<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OmieService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OmieClienteController extends Controller
{
    private OmieService $omieService;

    public function __construct(OmieService $omieService)
    {
        $this->omieService = $omieService;
    }

    /**
     * Buscar clientes por termo (documento, razão social ou nome fantasia)
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        $q = $request->get('q', ''); // Suporte para parâmetro 'q' também
        
        $searchTerm = $search ?: $q;

        if (empty($searchTerm)) {
            return response()->json([
                'success' => false,
                'message' => 'Termo de busca é obrigatório',
                'data' => []
            ], 400);
        }

        // Limitar o termo de busca para evitar consultas muito amplas
        if (strlen($searchTerm) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Termo de busca deve ter pelo menos 2 caracteres',
                'data' => []
            ], 400);
        }

        try {
            // Buscar clientes por termo
            $clientes = $this->omieService->searchClientsByTerm($searchTerm);

            // Limitar resultados para evitar sobrecarga
            $clientes = array_slice($clientes, 0, 20);

            // Formatar dados dos clientes
            $clientesFormatados = array_map(function ($cliente) {
                return [
                    'id' => $cliente['codigo_cliente_omie'] ?? null,
                    'omie_id' => $cliente['codigo_cliente_omie'] ?? null,
                    'nome' => $cliente['razao_social'] ?? $cliente['nome_fantasia'] ?? 'N/A',
                    'nome_fantasia' => $cliente['nome_fantasia'] ?? '',
                    'razao_social' => $cliente['razao_social'] ?? '',
                    'documento' => $this->omieService->formatDocument($cliente['cnpj_cpf'] ?? ''),
                    'documento_limpo' => $cliente['cnpj_cpf'] ?? '',
                    'email' => $cliente['email'] ?? '',
                    'telefone' => ($cliente['telefone1_ddd'] ?? '') . ($cliente['telefone1_numero'] ?? ''),
                    'cidade' => $cliente['cidade'] ?? '',
                    'estado' => $cliente['estado'] ?? '',
                    'ativo' => ($cliente['inativo'] ?? 'N') === 'N'
                ];
            }, $clientes);

            return response()->json([
                'success' => true,
                'data' => $clientesFormatados,
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
     * Buscar fornecedores por termo (documento, razão social ou nome fantasia)
     * Suporta busca específica por código ou nome
     */
    public function searchSuppliers(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        $q = $request->get('q', ''); // Suporte para parâmetro 'q' também
        $type = $request->get('type', ''); // Tipo de busca: 'codigo' ou 'nome'
        
        $searchTerm = $search ?: $q;

        if (empty($searchTerm)) {
            return response()->json([
                'success' => false,
                'message' => 'Termo de busca é obrigatório',
                'data' => []
            ], 400);
        }

        // Validação específica baseada no tipo de busca
        if ($type === 'codigo') {
            // Para código, aceitar a partir de 1 caractere
            if (strlen($searchTerm) < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código deve ter pelo menos 1 caractere',
                    'data' => []
                ], 400);
            }
        } else {
            // Para nome ou busca geral, manter mínimo de 2 caracteres
            if (strlen($searchTerm) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Termo de busca deve ter pelo menos 2 caracteres',
                    'data' => []
                ], 400);
            }
        }

        try {
            // Buscar fornecedores por termo com tipo específico
            $fornecedores = $this->omieService->searchSuppliersByTerm($searchTerm, $type);

            // Limitar resultados para evitar sobrecarga
            $fornecedores = array_slice($fornecedores, 0, 20);

            // Formatar dados dos fornecedores
            $fornecedoresFormatados = array_map(function ($fornecedor) {
                return [
                    'id' => $fornecedor['codigo_cliente_omie'] ?? null,
                    'omie_id' => $fornecedor['codigo_cliente_omie'] ?? null,
                    'nome' => $fornecedor['razao_social'] ?? $fornecedor['nome_fantasia'] ?? 'N/A',
                    'nome_fantasia' => $fornecedor['nome_fantasia'] ?? '',
                    'razao_social' => $fornecedor['razao_social'] ?? '',
                    'documento' => $this->omieService->formatDocument($fornecedor['cnpj_cpf'] ?? ''),
                    'documento_limpo' => $fornecedor['cnpj_cpf'] ?? '',
                    'email' => $fornecedor['email'] ?? '',
                    'telefone' => ($fornecedor['telefone1_ddd'] ?? '') . ($fornecedor['telefone1_numero'] ?? ''),
                    'cidade' => $fornecedor['cidade'] ?? '',
                    'estado' => $fornecedor['estado'] ?? '',
                    'ativo' => ($fornecedor['inativo'] ?? 'N') === 'N'
                ];
            }, $fornecedores);

            return response()->json([
                'success' => true,
                'data' => $fornecedoresFormatados,
                'total' => count($fornecedoresFormatados)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar fornecedores: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Consulta detalhes de um cliente ou fornecedor
     */
    public function show($omieId, Request $request): JsonResponse
    {
        try {
            $tipo = $request->get('tipo', 'cliente'); // cliente ou fornecedor
            
            if ($tipo === 'fornecedor') {
                $response = $this->omieService->consultarFornecedor($omieId);
            } else {
                $response = $this->omieService->consultarCliente($omieId);
            }
            
            if (!$response['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $response['message']
                ], 400);
            }
            
            return response()->json([
                'success' => true,
                'data' => $response['data']
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Limpar cache dos clientes
     */
    public function clearCache(): JsonResponse
    {
        try {
            $this->omieService->clearCache();
            
            return response()->json([
                'success' => true,
                'message' => 'Cache limpo com sucesso'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar cache: ' . $e->getMessage()
            ], 500);
        }
    }
}