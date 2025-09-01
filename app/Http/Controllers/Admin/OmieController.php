<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OmieService;
use App\Models\Cliente;
use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class OmieController extends Controller
{
    private $omieService;

    public function __construct(OmieService $omieService)
    {
        $this->omieService = $omieService;
    }

    /**
     * Lista clientes do Omie
     */
    public function clientes(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $search = $request->get('search', '');
            $searchType = $request->get('search_type', 'nome');
            
            $filtros = [
                'pagina' => $page,
                'registros_por_pagina' => 20
            ];
            
            if ($search) {
                // Busca específica por código Omie
                if ($searchType === 'codigo') {
                    // Para busca por código, usar método específico
                    $cliente = $this->omieService->getClientById((int)$search);
                    if ($cliente) {
                        return view('admin.omie.clientes', [
                            'clientes' => [$cliente],
                            'pagination' => [
                                'current_page' => 1,
                                'total_pages' => 1,
                                'total_records' => 1,
                                'per_page' => 20
                            ]
                        ]);
                    } else {
                        return view('admin.omie.clientes', [
                            'clientes' => [],
                            'error' => 'Cliente com código ' . $search . ' não encontrado',
                            'pagination' => null
                        ]);
                    }
                }
                // Busca por documento
                elseif ($searchType === 'documento') {
                    $cliente = $this->omieService->getClientByDocument($search);
                    if ($cliente) {
                        return view('admin.omie.clientes', [
                            'clientes' => [$cliente],
                            'pagination' => [
                                'current_page' => 1,
                                'total_pages' => 1,
                                'total_records' => 1,
                                'per_page' => 20
                            ]
                        ]);
                    } else {
                        return view('admin.omie.clientes', [
                            'clientes' => [],
                            'error' => 'Cliente com documento ' . $search . ' não encontrado',
                            'pagination' => null
                        ]);
                    }
                }
                // Busca por nome/razão social (padrão)
                else {
                    $filtros['clientesFiltro'] = [
                        'nome_fantasia' => $search
                    ];
                }
            }
            
            $response = $this->omieService->listarClientesPaginado($filtros);
            
            if (!$response['success']) {
                return view('admin.omie.clientes', [
                    'clientes' => [],
                    'error' => $response['message'],
                    'pagination' => null
                ]);
            }
            
            $clientes = $response['data']['clientes_cadastro'] ?? [];
            $pagination = [
                'current_page' => $page,
                'total_pages' => $response['data']['total_de_paginas'] ?? 1,
                'total_records' => $response['data']['total_de_registros'] ?? 0,
                'per_page' => 20
            ];
            
            return view('admin.omie.clientes', compact('clientes', 'pagination'));
            
        } catch (Exception $e) {
            Log::error('Erro ao listar clientes Omie: ' . $e->getMessage());
            
            return view('admin.omie.clientes', [
                'clientes' => [],
                'error' => 'Erro interno do servidor',
                'pagination' => null
            ]);
        }
    }

    /**
     * Lista fornecedores do Omie
     */
    public function fornecedores(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $search = $request->get('search', '');
            
            $filtros = [
                'pagina' => $page,
                'registros_por_pagina' => 20
            ];
            
            if ($search) {
                $filtros['fornecedorFiltro'] = [
                    'razao_social' => $search
                ];
            }
            
            $response = $this->omieService->listarFornecedores($filtros);
            
            if (!$response['success']) {
                return view('admin.omie.fornecedores', [
                    'fornecedores' => [],
                    'error' => $response['message'],
                    'pagination' => null
                ]);
            }
            
            $fornecedores = $response['data']['clientes_cadastro'] ?? [];
            $pagination = [
                'current_page' => $page,
                'total_pages' => $response['data']['total_de_paginas'] ?? 1,
                'total_records' => $response['data']['total_de_registros'] ?? 0,
                'per_page' => 20
            ];
            
            return view('admin.omie.fornecedores', compact('fornecedores', 'pagination'));
            
        } catch (Exception $e) {
            Log::error('Erro ao listar fornecedores Omie: ' . $e->getMessage());
            
            return view('admin.omie.fornecedores', [
                'fornecedores' => [],
                'error' => 'Erro interno do servidor',
                'pagination' => null
            ]);
        }
    }

    /**
     * Consulta detalhes de um cliente
     */
    public function consultarCliente($omieId)
    {
        try {
            $data = $this->omieService->consultarCliente($omieId);
            
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente não encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (Exception $e) {
            Log::error('Erro ao consultar cliente Omie: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Consulta detalhes de um fornecedor
     */
    public function consultarFornecedor($omieId)
    {
        try {
            $response = $this->omieService->consultarFornecedor($omieId);
            
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
            
        } catch (Exception $e) {
            Log::error('Erro ao consultar fornecedor Omie: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }
}
