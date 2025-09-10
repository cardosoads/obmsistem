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
        $validated = $request->validate([
            'tipo_veiculo_id' => 'required|exists:tipos_veiculos,id',
            'fipe' => 'required|numeric|min:0|max:9999999999.99',
            'percentual_fipe' => 'nullable|numeric|min:0|max:999.99',
            'aluguel_carro' => 'required|numeric|min:0|max:9999999999.99',
            'rastreador' => 'nullable|numeric|min:0|max:9999999999.99',
            'provisoes_avarias' => 'nullable|numeric|min:0|max:9999999999.99',
            'provisao_desmobilizacao' => 'nullable|numeric|min:0|max:9999999999.99',
            'provisao_diaria_rac' => 'nullable|numeric|min:0|max:9999999999.99',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active');
        $validated['percentual_fipe'] = $validated['percentual_fipe'] ?? 0;
        $validated['rastreador'] = $validated['rastreador'] ?? 0;
        $validated['provisoes_avarias'] = $validated['provisoes_avarias'] ?? 0;
        $validated['provisao_desmobilizacao'] = $validated['provisao_desmobilizacao'] ?? 0;
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
        $validated = $request->validate([
            'tipo_veiculo_id' => 'required|exists:tipos_veiculos,id',
            'fipe' => 'required|numeric|min:0|max:9999999999.99',
            'percentual_fipe' => 'nullable|numeric|min:0|max:999.99',
            'aluguel_carro' => 'required|numeric|min:0|max:9999999999.99',
            'rastreador' => 'nullable|numeric|min:0|max:9999999999.99',
            'provisoes_avarias' => 'nullable|numeric|min:0|max:9999999999.99',
            'provisao_desmobilizacao' => 'nullable|numeric|min:0|max:9999999999.99',
            'provisao_diaria_rac' => 'nullable|numeric|min:0|max:9999999999.99',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active');
        $validated['percentual_fipe'] = $validated['percentual_fipe'] ?? 0;
        $validated['rastreador'] = $validated['rastreador'] ?? 0;
        $validated['provisoes_avarias'] = $validated['provisoes_avarias'] ?? 0;
        $validated['provisao_desmobilizacao'] = $validated['provisao_desmobilizacao'] ?? 0;
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
}
