<?php

namespace App\Http\Controllers;

use App\Models\Frota;
use App\Models\TipoVeiculo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FrotaController extends Controller
{
    /**
     * Processa campos monetários convertendo de formato brasileiro para numérico
     */
    private function processMoneyFields(array $data): array
    {
        $moneyFields = [
            'fipe',
            'aluguel_carro',
            'rastreador',
            'provisoes_avarias',
            'provisao_desmobilizacao',
            'provisao_diaria_rac'
        ];

        foreach ($moneyFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = $this->convertMoneyToNumeric($data[$field]);
            }
        }

        return $data;
    }

    /**
     * Converte valor monetário brasileiro para formato numérico
     */
    private function convertMoneyToNumeric($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }
        
        // Remove pontos (separadores de milhares) e substitui vírgula por ponto
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        
        return (float) $value;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Frota::with('tipoVeiculo');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('tipoVeiculo', function ($q) use ($search) {
                $q->where('codigo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipo_veiculo_id')) {
            $query->where('tipo_veiculo_id', $request->get('tipo_veiculo_id'));
        }

        if ($request->filled('status')) {
            $active = $request->get('status') === 'ativo';
            $query->where('active', $active);
        }

        $frotas = $query->orderBy('custo_total', 'desc')->paginate(15);
        $tiposVeiculos = TipoVeiculo::active()->orderBy('codigo')->get();

        // Estatísticas para os cards
        $totalFrotas = Frota::count();
        $frotasAtivas = Frota::where('active', true)->count();
        $custoMedio = Frota::avg('custo_total') ?? 0;
        $valorFipeTotal = Frota::sum('fipe') ?? 0;

        return view('admin.frotas.index', compact(
            'frotas', 
            'tiposVeiculos', 
            'totalFrotas', 
            'frotasAtivas', 
            'custoMedio', 
            'valorFipeTotal'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tiposVeiculos = TipoVeiculo::active()->orderBy('codigo')->get();
        return view('admin.frotas.create', compact('tiposVeiculos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Processar campos monetários antes da validação
        $requestData = $this->processMoneyFields($request->all());
        $request->merge($requestData);
        
        $validated = $request->validate([
            'tipo_veiculo_id' => 'required|exists:tipos_veiculos,id',
            'fipe' => 'required|numeric|min:0|max:9999999999.99',
            'percentual_fipe' => 'nullable|numeric|min:0|max:999.99',
            'aluguel_carro' => 'required|numeric|min:0|max:9999999999.99',
            'rastreador' => 'nullable|numeric|min:0|max:9999999999.99',
            'percentual_provisoes_avarias' => 'nullable|numeric|min:0|max:100',
            'provisoes_avarias' => 'nullable|numeric|min:0|max:9999999999.99',
            'percentual_provisao_desmobilizacao' => 'nullable|numeric|min:0|max:100',
            'provisao_desmobilizacao' => 'nullable|numeric|min:0|max:9999999999.99',
            'percentual_provisao_rac' => 'nullable|numeric|min:0|max:100',
            'provisao_diaria_rac' => 'nullable|numeric|min:0|max:9999999999.99',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active');
        $validated['percentual_fipe'] = $validated['percentual_fipe'] ?? 0;
        $validated['rastreador'] = $validated['rastreador'] ?? 0;
        $validated['percentual_provisoes_avarias'] = $validated['percentual_provisoes_avarias'] ?? 0;
        $validated['provisoes_avarias'] = $validated['provisoes_avarias'] ?? 0;
        $validated['percentual_provisao_desmobilizacao'] = $validated['percentual_provisao_desmobilizacao'] ?? 0;
        $validated['provisao_desmobilizacao'] = $validated['provisao_desmobilizacao'] ?? 0;
        $validated['percentual_provisao_rac'] = $validated['percentual_provisao_rac'] ?? 0;
        $validated['provisao_diaria_rac'] = $validated['provisao_diaria_rac'] ?? 0;

        Frota::create($validated);

        return redirect()->route('admin.frotas.index')
            ->with('success', 'Frota criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Frota $frota): View
    {
        $frota->load('tipoVeiculo');
        return view('admin.frotas.show', compact('frota'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Frota $frota): View
    {
        $tiposVeiculos = TipoVeiculo::active()->orderBy('codigo')->get();
        return view('admin.frotas.edit', compact('frota', 'tiposVeiculos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Frota $frota): RedirectResponse
    {
        // Processar campos monetários antes da validação
        $requestData = $this->processMoneyFields($request->all());
        $request->merge($requestData);
        
        $validated = $request->validate([
            'tipo_veiculo_id' => 'required|exists:tipos_veiculos,id',
            'fipe' => 'required|numeric|min:0|max:9999999999.99',
            'percentual_fipe' => 'nullable|numeric|min:0|max:999.99',
            'aluguel_carro' => 'required|numeric|min:0|max:9999999999.99',
            'rastreador' => 'nullable|numeric|min:0|max:9999999999.99',
            'percentual_provisoes_avarias' => 'nullable|numeric|min:0|max:100',
            'provisoes_avarias' => 'nullable|numeric|min:0|max:9999999999.99',
            'percentual_provisao_desmobilizacao' => 'nullable|numeric|min:0|max:100',
            'provisao_desmobilizacao' => 'nullable|numeric|min:0|max:9999999999.99',
            'percentual_provisao_rac' => 'nullable|numeric|min:0|max:100',
            'provisao_diaria_rac' => 'nullable|numeric|min:0|max:9999999999.99',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active');
        $validated['percentual_fipe'] = $validated['percentual_fipe'] ?? 0;
        $validated['rastreador'] = $validated['rastreador'] ?? 0;
        $validated['percentual_provisoes_avarias'] = $validated['percentual_provisoes_avarias'] ?? 0;
        $validated['provisoes_avarias'] = $validated['provisoes_avarias'] ?? 0;
        $validated['percentual_provisao_desmobilizacao'] = $validated['percentual_provisao_desmobilizacao'] ?? 0;
        $validated['provisao_desmobilizacao'] = $validated['provisao_desmobilizacao'] ?? 0;
        $validated['percentual_provisao_rac'] = $validated['percentual_provisao_rac'] ?? 0;
        $validated['provisao_diaria_rac'] = $validated['provisao_diaria_rac'] ?? 0;

        $frota->update($validated);

        return redirect()->route('admin.frotas.index')
            ->with('success', 'Frota atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Frota $frota): JsonResponse
    {
        try {
            $frota->delete();

            return response()->json([
                'success' => true,
                'message' => 'Frota excluída com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir frota: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle status da frota
     */
    public function toggleStatus(Frota $frota): JsonResponse
    {
        try {
            $frota->update(['active' => !$frota->active]);

            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso!',
                'active' => $frota->active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recalcular custo total
     */
    public function recalcularCusto(Frota $frota): JsonResponse
    {
        try {
            $custoAnterior = $frota->custo_total;
            $frota->calcularCustoTotal();
            $frota->save();

            return response()->json([
                'success' => true,
                'message' => 'Custo recalculado com sucesso!',
                'custo_anterior' => $custoAnterior,
                'custo_novo' => $frota->custo_total,
                'custo_formatado' => $frota->custo_total_formatado
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao recalcular custo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar valor da frota para orçamento próprio nova rota
     */
    public function buscarValor(Request $request): JsonResponse
    {
        $request->validate([
            'codigo_veiculo' => 'required|string'
        ]);

        $frota = Frota::whereHas('tipoVeiculo', function ($query) use ($request) {
                    $query->where('codigo', $request->codigo_veiculo);
                })
                ->where('active', true)
                ->first();

        if (!$frota) {
            return response()->json([
                'success' => false,
                'message' => 'Veículo não encontrado ou inativo.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $frota->id,
                'codigo_veiculo' => $frota->tipoVeiculo->codigo,
                'descricao_veiculo' => $frota->tipoVeiculo->descricao,
                'valor_aluguel' => $frota->aluguel_carro,
                'custo_total' => $frota->custo_total,
                'custo_total_formatado' => 'R$ ' . number_format($frota->custo_total, 2, ',', '.')
            ]
        ]);
    }

    /**
     * API para listar frotas ativas
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $query = Frota::with('tipoVeiculo')->where('active', true);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('tipoVeiculo', function ($q) use ($search) {
                $q->where('codigo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        $frotas = $query->orderBy('custo_total', 'asc')
                       ->limit(50)
                       ->get()
                       ->map(function ($frota) {
                           return [
                               'id' => $frota->id,
                               'codigo' => $frota->tipoVeiculo->codigo,
                               'descricao' => $frota->tipoVeiculo->descricao,
                               'valor_aluguel' => $frota->aluguel_carro,
                               'custo_total' => $frota->custo_total,
                               'custo_formatado' => 'R$ ' . number_format($frota->custo_total, 2, ',', '.')
                           ];
                       });

        return response()->json([
            'success' => true,
            'data' => $frotas
        ]);
    }
}
