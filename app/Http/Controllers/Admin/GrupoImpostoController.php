<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrupoImposto;
use App\Models\Imposto;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class GrupoImpostoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = GrupoImposto::with(['impostos' => function ($q) {
            $q->where('ativo', true);
        }]);

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('ativo', $request->status === 'ativo');
        }

        // Filtro por busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        $gruposImpostos = $query->orderBy('nome')->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'grupos_impostos' => $gruposImpostos->items(),
                'pagination' => [
                    'current_page' => $gruposImpostos->currentPage(),
                    'last_page' => $gruposImpostos->lastPage(),
                    'per_page' => $gruposImpostos->perPage(),
                    'total' => $gruposImpostos->total()
                ]
            ]);
        }

        return view('admin.grupos-impostos.index', compact('gruposImpostos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $impostos = Imposto::ativo()->orderBy('nome')->get();
        return view('admin.grupos-impostos.create', compact('impostos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:grupos_impostos,nome',
            'descricao' => 'nullable|string|max:1000',
            'ativo' => 'boolean',
            'impostos' => 'required|array|min:1',
            'impostos.*' => 'exists:impostos,id'
        ], [
            'nome.required' => 'O nome do grupo é obrigatório.',
            'nome.unique' => 'Já existe um grupo com este nome.',
            'impostos.required' => 'Selecione pelo menos um imposto.',
            'impostos.min' => 'Selecione pelo menos um imposto.',
            'impostos.*.exists' => 'Um ou mais impostos selecionados não existem.'
        ]);

        $validated['ativo'] = $request->has('ativo');

        $grupoImposto = GrupoImposto::create($validated);

        // Sincronizar impostos
        $grupoImposto->impostos()->sync($request->impostos);

        return redirect()->route('admin.grupos-impostos.index')
            ->with('success', 'Grupo de impostos criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(GrupoImposto $gruposImposto): View
    {
        $gruposImposto->load(['impostos' => function ($q) {
            $q->orderBy('nome');
        }]);
        return view('admin.grupos-impostos.show', compact('gruposImposto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GrupoImposto $gruposImposto): View
    {
        $impostos = Imposto::ativo()->orderBy('nome')->get();
        $gruposImposto->load('impostos');
        return view('admin.grupos-impostos.edit', compact('gruposImposto', 'impostos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GrupoImposto $gruposImposto): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:grupos_impostos,nome,' . $gruposImposto->id,
            'descricao' => 'nullable|string|max:1000',
            'ativo' => 'boolean',
            'impostos' => 'required|array|min:1',
            'impostos.*' => 'exists:impostos,id'
        ], [
            'nome.required' => 'O nome do grupo é obrigatório.',
            'nome.unique' => 'Já existe um grupo com este nome.',
            'impostos.required' => 'Selecione pelo menos um imposto.',
            'impostos.min' => 'Selecione pelo menos um imposto.',
            'impostos.*.exists' => 'Um ou mais impostos selecionados não existem.'
        ]);

        $validated['ativo'] = $request->has('ativo');

        $gruposImposto->update($validated);

        // Sincronizar impostos
        $gruposImposto->impostos()->sync($request->impostos);

        return redirect()->route('admin.grupos-impostos.index')
            ->with('success', 'Grupo de impostos atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GrupoImposto $gruposImposto): RedirectResponse
    {
        try {
            // Desassociar todos os impostos antes de excluir
            $gruposImposto->impostos()->detach();
            $gruposImposto->delete();

            return redirect()->route('admin.grupos-impostos.index')
                ->with('success', 'Grupo de impostos excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('admin.grupos-impostos.index')
                ->with('error', 'Erro ao excluir o grupo: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the active status of the resource.
     */
    public function toggleStatus(GrupoImposto $gruposImposto): JsonResponse
    {
        try {
            $gruposImposto->update(['ativo' => !$gruposImposto->ativo]);

            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso!',
                'ativo' => $gruposImposto->ativo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate total tax value for a given base value.
     */
    public function calcular(Request $request): JsonResponse
    {
        $request->validate([
            'grupo_imposto_id' => 'required|exists:grupos_impostos,id',
            'valor_base' => 'required|numeric|min:0'
        ]);

        $grupoImposto = GrupoImposto::with('impostosAtivos')->findOrFail($request->grupo_imposto_id);
        $valorBase = (float) $request->valor_base;
        $breakdown = $grupoImposto->calcularBreakdown($valorBase);

        return response()->json([
            'grupo_imposto' => [
                'id' => $grupoImposto->id,
                'nome' => $grupoImposto->nome,
                'percentual_total' => $grupoImposto->percentual_total,
                'percentual_total_formatado' => $grupoImposto->percentual_total_formatado,
                'quantidade_impostos' => $grupoImposto->quantidade_impostos
            ],
            'calculo' => [
                'valor_base' => $valorBase,
                'valor_base_formatado' => 'R$ ' . number_format($valorBase, 2, ',', '.'),
                'breakdown' => $breakdown
            ]
        ]);
    }

    /**
     * Get breakdown details for a specific group and base value.
     */
    public function breakdown(Request $request): JsonResponse
    {
        $request->validate([
            'grupo_imposto_id' => 'required|exists:grupos_impostos,id',
            'valor_base' => 'required|numeric|min:0'
        ]);

        $grupoImposto = GrupoImposto::with('impostosAtivos')->findOrFail($request->grupo_imposto_id);
        $valorBase = (float) $request->valor_base;
        $breakdown = $grupoImposto->calcularBreakdown($valorBase);

        return response()->json($breakdown);
    }

    /**
     * Get grupos impostos for select2 ajax.
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->get('q', '');
        
        $gruposImpostos = GrupoImposto::ativo()
            ->with('impostosAtivos')
            ->where('nome', 'like', "%{$search}%")
            ->orderBy('nome')
            ->limit(20)
            ->get();

        $results = $gruposImpostos->map(function ($grupo) {
            return [
                'id' => $grupo->id,
                'text' => $grupo->nome . ' (' . $grupo->percentual_total_formatado . ' - ' . $grupo->quantidade_impostos . ' impostos)'
            ];
        });

        return response()->json(['results' => $results]);
    }

    /**
     * Get impostos available for a group (not already assigned).
     */
    public function impostosDisponiveis(GrupoImposto $gruposImposto): JsonResponse
    {
        $impostosAssociados = $gruposImposto->impostos()->pluck('impostos.id')->toArray();
        
        $impostosDisponiveis = Imposto::ativo()
            ->whereNotIn('id', $impostosAssociados)
            ->orderBy('nome')
            ->get(['id', 'nome', 'percentual']);

        $results = $impostosDisponiveis->map(function ($imposto) {
            return [
                'id' => $imposto->id,
                'text' => $imposto->nome . ' (' . $imposto->percentual_formatado . ')'
            ];
        });

        return response()->json(['results' => $results]);
    }
}