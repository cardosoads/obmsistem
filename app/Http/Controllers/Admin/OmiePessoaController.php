<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OmieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class OmiePessoaController extends Controller
{
    private $omieService;

    public function __construct(OmieService $omieService)
    {
        $this->omieService = $omieService;
    }

    /**
     * Lista pessoas (clientes e fornecedores) do Omie
     */
    public function index(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $search = $request->get('search', '');
            $tipo = $request->get('tipo', 'todos'); // todos, clientes, fornecedores
            
            $pessoas = [];
            $pagination = null;
            $error = null;
            
            // Buscar clientes se solicitado
            if ($tipo === 'todos' || $tipo === 'clientes') {
                $filtrosClientes = [
                    'pagina' => $page,
                    'registros_por_pagina' => 20
                ];
                
                if ($search) {
                    $filtrosClientes['clientesFiltro'] = [
                        'nome_fantasia' => $search
                    ];
                }
                
                $responseClientes = $this->omieService->listarClientesPaginado($filtrosClientes);
                
                if ($responseClientes['success']) {
                    $clientes = $responseClientes['data']['clientes_cadastro'] ?? [];
                    
                    // Adicionar tipo 'cliente' aos dados
                    foreach ($clientes as &$cliente) {
                        $cliente['tipo_pessoa'] = 'cliente';
                        $cliente['id_omie'] = $cliente['codigo_cliente_omie'] ?? null;
                        $cliente['nome_principal'] = $cliente['razao_social'] ?? $cliente['nome_fantasia'] ?? 'N/A';
                    }
                    
                    $pessoas = array_merge($pessoas, $clientes);
                    
                    if (!$pagination) {
                        $pagination = [
                            'current_page' => $page,
                            'total_pages' => $responseClientes['data']['total_de_paginas'] ?? 1,
                            'total_records' => $responseClientes['data']['total_de_registros'] ?? 0,
                            'per_page' => 20
                        ];
                    }
                }
            }
            
            // Buscar fornecedores se solicitado
            if ($tipo === 'todos' || $tipo === 'fornecedores') {
                $filtrosFornecedores = [
                    'pagina' => $page,
                    'registros_por_pagina' => 20
                ];
                
                if ($search) {
                    $filtrosFornecedores['fornecedorFiltro'] = [
                        'razao_social' => $search
                    ];
                }
                
                $responseFornecedores = $this->omieService->listarFornecedores($filtrosFornecedores);
                
                if ($responseFornecedores['success']) {
                    $fornecedores = $responseFornecedores['data']['fornecedor_cadastro'] ?? [];
                    
                    // Adicionar tipo 'fornecedor' aos dados
                    foreach ($fornecedores as &$fornecedor) {
                        $fornecedor['tipo_pessoa'] = 'fornecedor';
                        $fornecedor['id_omie'] = $fornecedor['codigo_fornecedor_omie'] ?? null;
                        $fornecedor['nome_principal'] = $fornecedor['razao_social'] ?? $fornecedor['nome_fantasia'] ?? 'N/A';
                        
                        // Padronizar campos para compatibilidade com clientes
                        $fornecedor['codigo_cliente_omie'] = $fornecedor['codigo_fornecedor_omie'] ?? null;
                    }
                    
                    $pessoas = array_merge($pessoas, $fornecedores);
                    
                    if (!$pagination && $tipo === 'fornecedores') {
                        $pagination = [
                            'current_page' => $page,
                            'total_pages' => $responseFornecedores['data']['total_de_paginas'] ?? 1,
                            'total_records' => $responseFornecedores['data']['total_de_registros'] ?? 0,
                            'per_page' => 20
                        ];
                    }
                }
            }
            
            // Ordenar por nome
            usort($pessoas, function($a, $b) {
                return strcmp($a['nome_principal'], $b['nome_principal']);
            });
            
            return view('admin.omie.pessoas', compact('pessoas', 'pagination', 'tipo', 'search'));
            
        } catch (Exception $e) {
            Log::error('Erro ao listar pessoas Omie: ' . $e->getMessage());
            
            return view('admin.omie.pessoas', [
                'pessoas' => [],
                'error' => 'Erro interno do servidor',
                'pagination' => null,
                'tipo' => $tipo ?? 'todos',
                'search' => $search ?? ''
            ]);
        }
    }
    
    /**
     * Consulta detalhes de uma pessoa (cliente ou fornecedor)
     */
    public function show($omieId, Request $request)
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
            
            // Adicionar tipo aos dados de resposta
            $data = $response['data'];
            $data['tipo_pessoa'] = $tipo;
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (Exception $e) {
            Log::error('Erro ao consultar pessoa Omie: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }
}