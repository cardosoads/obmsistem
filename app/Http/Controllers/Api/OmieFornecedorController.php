<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OmieService;
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
     * Consultar fornecedor por ID OMIE
     */
    public function show(string $omieId): JsonResponse
    {
        try {
            Log::info('Consultando fornecedor OMIE', ['omie_id' => $omieId]);
            
            $fornecedor = $this->omieService->consultarFornecedor($omieId);
            
            if ($fornecedor) {
                Log::info('Fornecedor encontrado', ['fornecedor' => $fornecedor]);
                return response()->json([
                    'success' => true,
                    'data' => $fornecedor
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