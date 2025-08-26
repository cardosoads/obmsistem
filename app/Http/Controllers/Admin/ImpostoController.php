<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Imposto;
use App\Models\GrupoImposto;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ImpostoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Imposto::query();

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

        $impostos = $query->orderBy('nome')->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'impostos' => $impostos->items(),
                'pagination' => [
                    'current_page' => $impostos->currentPage(),
                    'last_page' => $impostos->lastPage(),
                    'per_page' => $impostos->perPage(),
                    'total' => $impostos->total()
                ]
            ]);
        }

        return view('admin.impostos.index', compact('impostos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $gruposImpostos = GrupoImposto::ativo()->orderBy('nome')->get();
        return view('admin.impostos.create', compact('gruposImpostos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:impostos,nome',
            'descricao' => 'nullable|string|max:1000',
            'percentual' => 'required|numeric|min:0|max:100',
            'ativo' => 'boolean',
            'grupos_impostos' => 'nullable|array',
            'grupos_impostos.*' => 'exists:grupos_impostos,id'
        ], [
            'nome.required' => 'O nome do imposto é obrigatório.',
            'nome.unique' => 'Já existe um imposto com este nome.',
            'percentual.required' => 'O percentual é obrigatório.',
            'percentual.numeric' => 'O percentual deve ser um número.',
            'percentual.min' => 'O percentual deve ser maior ou igual a 0.',
            'percentual.max' => 'O percentual deve ser menor ou igual a 100.',
            'grupos_impostos.*.exists' => 'Um ou mais grupos selecionados não existem.'
        ]);

        $validated['ativo'] = $request->has('ativo');

        $imposto = Imposto::create($validated);

        // Sincronizar grupos de impostos
        if ($request->filled('grupos_impostos')) {
            $imposto->gruposImpostos()->sync($request->grupos_impostos);
        }

        return redirect()->route('admin.impostos.index')
            ->with('success', 'Imposto criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Imposto $imposto): View
    {
        $imposto->load('gruposImpostos');
        return view('admin.impostos.show', compact('imposto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Imposto $imposto): View
    {
        $gruposImpostos = GrupoImposto::ativo()->orderBy('nome')->get();
        $imposto->load('gruposImpostos');
        return view('admin.impostos.edit', compact('imposto', 'gruposImpostos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Imposto $imposto): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:impostos,nome,' . $imposto->id,
            'descricao' => 'nullable|string|max:1000',
            'percentual' => 'required|numeric|min:0|max:100',
            'ativo' => 'boolean',
            'grupos_impostos' => 'nullable|array',
            'grupos_impostos.*' => 'exists:grupos_impostos,id'
        ], [
            'nome.required' => 'O nome do imposto é obrigatório.',
            'nome.unique' => 'Já existe um imposto com este nome.',
            'percentual.required' => 'O percentual é obrigatório.',
            'percentual.numeric' => 'O percentual deve ser um número.',
            'percentual.min' => 'O percentual deve ser maior ou igual a 0.',
            'percentual.max' => 'O percentual deve ser menor ou igual a 100.',
            'grupos_impostos.*.exists' => 'Um ou mais grupos selecionados não existem.'
        ]);

        $validated['ativo'] = $request->has('ativo');

        $imposto->update($validated);

        // Sincronizar grupos de impostos
        if ($request->filled('grupos_impostos')) {
            $imposto->gruposImpostos()->sync($request->grupos_impostos);
        } else {
            $imposto->gruposImpostos()->detach();
        }

        return redirect()->route('admin.impostos.index')
            ->with('success', 'Imposto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Imposto $imposto): RedirectResponse
    {
        try {
            // Verificar se o imposto está sendo usado em algum grupo
            if ($imposto->gruposImpostos()->count() > 0) {
                return redirect()->route('admin.impostos.index')
                    ->with('error', 'Não é possível excluir este imposto pois ele está vinculado a um ou mais grupos.');
            }

            $imposto->delete();

            return redirect()->route('admin.impostos.index')
                ->with('success', 'Imposto excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('admin.impostos.index')
                ->with('error', 'Erro ao excluir o imposto: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleStatus(Imposto $imposto): JsonResponse
    {
        try {
            $imposto->update(['ativo' => !$imposto->ativo]);

            return response()->json([
                'success' => true,
                'message' => 'Status do imposto alterado com sucesso!',
                'ativo' => $imposto->ativo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate tax value for a given base value.
     */
    public function calcular(Request $request): JsonResponse
    {
        $request->validate([
            'imposto_id' => 'required|exists:impostos,id',
            'valor_base' => 'required|numeric|min:0'
        ]);

        $imposto = Imposto::findOrFail($request->imposto_id);
        $valorBase = (float) $request->valor_base;
        $valorImposto = $imposto->calcularValor($valorBase);

        return response()->json([
            'imposto' => [
                'id' => $imposto->id,
                'nome' => $imposto->nome,
                'percentual' => $imposto->percentual,
                'percentual_formatado' => $imposto->percentual_formatado
            ],
            'calculo' => [
                'valor_base' => $valorBase,
                'valor_base_formatado' => 'R$ ' . number_format($valorBase, 2, ',', '.'),
                'valor_imposto' => $valorImposto,
                'valor_imposto_formatado' => 'R$ ' . number_format($valorImposto, 2, ',', '.'),
                'valor_total' => $valorBase + $valorImposto,
                'valor_total_formatado' => 'R$ ' . number_format($valorBase + $valorImposto, 2, ',', '.')
            ]
        ]);
    }

    /**
     * Get impostos for select2 ajax.
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->get('q', '');
        
        $impostos = Imposto::ativo()
            ->where('nome', 'like', "%{$search}%")
            ->orderBy('nome')
            ->limit(20)
            ->get(['id', 'nome', 'percentual']);

        $results = $impostos->map(function ($imposto) {
            return [
                'id' => $imposto->id,
                'text' => $imposto->nome . ' (' . $imposto->percentual_formatado . ')'
            ];
        });

        return response()->json(['results' => $results]);
    }
}