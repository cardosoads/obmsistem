<?php

namespace App\Http\Controllers;

use App\Models\RecursoHumano;
use App\Models\Base;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RecursoHumanoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = RecursoHumano::with('base');

        // Filtros
        if ($request->filled('cargo')) {
            $query->byCargo($request->cargo);
        }

        if ($request->filled('tipo_contratacao')) {
            $query->byTipoContratacao($request->tipo_contratacao);
        }

        if ($request->filled('base_id')) {
            $query->where('base_id', $request->base_id);
        }

        if ($request->filled('active')) {
            $query->where('active', $request->boolean('active'));
        }

        // Busca por texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('cargo', 'like', "%{$search}%")
                  ->orWhere('tipo_contratacao', 'like', "%{$search}%")
                  ->orWhereHas('base', function ($baseQuery) use ($search) {
                      $baseQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $recursosHumanos = $query->orderBy('cargo')
                                ->orderBy('tipo_contratacao')
                                ->paginate(15);

        if ($request->expectsJson()) {
            return response()->json($recursosHumanos);
        }

        return view('admin.recursos-humanos.index', compact('recursosHumanos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bases = Base::active()->orderBy('name')->get();
        $tiposContratacao = RecursoHumano::getTiposContratacao();
        $cargos = RecursoHumano::getCargos();

        return view('admin.recursos-humanos.create', compact('bases', 'tiposContratacao', 'cargos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'tipo_contratacao' => 'required|string|max:100',
            'cargo' => 'required|string|max:100',
            'base_id' => 'nullable|exists:bases,id',
            'base_salarial' => 'required|numeric|min:0',
            'salario_base' => 'required|numeric|min:0',
            'insalubridade' => 'nullable|numeric|min:0',
            'periculosidade' => 'nullable|numeric|min:0',
            'horas_extras' => 'nullable|numeric|min:0',
            'adicional_noturno' => 'nullable|numeric|min:0',
            'extras' => 'nullable|numeric|min:0',
            'vale_transporte' => 'nullable|numeric|min:0',
            'beneficios' => 'nullable|numeric|min:0',
            'percentual_encargos' => 'nullable|numeric|min:0|max:100',
            'percentual_beneficios' => 'nullable|numeric|min:0|max:100',
            'active' => 'boolean',
        ]);

        $recursoHumano = RecursoHumano::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Recurso humano criado com sucesso!',
                'data' => $recursoHumano->load('base')
            ], 201);
        }

        return redirect()->route('admin.recursos-humanos.index')
                        ->with('success', 'Recurso humano criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(RecursoHumano $recursoHumano): View|JsonResponse
    {
        $recursoHumano->load('base');

        if (request()->expectsJson()) {
            return response()->json($recursoHumano);
        }

        return view('admin.recursos-humanos.show', compact('recursoHumano'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RecursoHumano $recursoHumano): View
    {
        $bases = Base::active()->orderBy('name')->get();
        $tiposContratacao = RecursoHumano::getTiposContratacao();
        $cargos = RecursoHumano::getCargos();

        return view('admin.recursos-humanos.edit', compact('recursoHumano', 'bases', 'tiposContratacao', 'cargos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RecursoHumano $recursoHumano): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'tipo_contratacao' => 'required|string|max:100',
            'cargo' => 'required|string|max:100',
            'base_id' => 'nullable|exists:bases,id',
            'base_salarial' => 'required|numeric|min:0',
            'salario_base' => 'required|numeric|min:0',
            'insalubridade' => 'nullable|numeric|min:0',
            'periculosidade' => 'nullable|numeric|min:0',
            'horas_extras' => 'nullable|numeric|min:0',
            'adicional_noturno' => 'nullable|numeric|min:0',
            'extras' => 'nullable|numeric|min:0',
            'vale_transporte' => 'nullable|numeric|min:0',
            'beneficios' => 'nullable|numeric|min:0',
            'percentual_encargos' => 'nullable|numeric|min:0|max:100',
            'percentual_beneficios' => 'nullable|numeric|min:0|max:100',
            'active' => 'boolean',
        ]);

        $recursoHumano->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Recurso humano atualizado com sucesso!',
                'data' => $recursoHumano->load('base')
            ]);
        }

        return redirect()->route('admin.recursos-humanos.index')
                        ->with('success', 'Recurso humano atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecursoHumano $recursoHumano): RedirectResponse|JsonResponse
    {
        $recursoHumano->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Recurso humano excluído com sucesso!'
            ]);
        }

        return redirect()->route('admin.recursos-humanos.index')
                        ->with('success', 'Recurso humano excluído com sucesso!');
    }

    /**
     * Toggle status ativo/inativo
     */
    public function toggleStatus(RecursoHumano $recursoHumano): JsonResponse
    {
        $recursoHumano->update(['active' => !$recursoHumano->active]);

        return response()->json([
            'message' => 'Status atualizado com sucesso!',
            'active' => $recursoHumano->active
        ]);
    }

    /**
     * Recalcular custos de um recurso humano
     */
    public function recalcular(RecursoHumano $recursoHumano): JsonResponse
    {
        $recursoHumano->atualizarCalculos();
        $recursoHumano->save();

        return response()->json([
            'message' => 'Cálculos atualizados com sucesso!',
            'data' => $recursoHumano->fresh()
        ]);
    }

    /**
     * Obter dados para relatórios
     */
    public function relatorio(Request $request): JsonResponse
    {
        $query = RecursoHumano::with('base');

        if ($request->filled('base_id')) {
            $query->where('base_id', $request->base_id);
        }

        if ($request->filled('cargo')) {
            $query->where('cargo', $request->cargo);
        }

        $recursos = $query->active()->get();

        $relatorio = [
            'total_recursos' => $recursos->count(),
            'custo_total_folha' => $recursos->sum('custo_total_mao_obra'),
            'media_salarial' => $recursos->avg('salario_base'),
            'por_cargo' => $recursos->groupBy('cargo')->map(function ($grupo) {
                return [
                    'quantidade' => $grupo->count(),
                    'custo_total' => $grupo->sum('custo_total_mao_obra'),
                    'media_salarial' => $grupo->avg('salario_base'),
                ];
            }),
            'por_base' => $recursos->groupBy('base.name')->map(function ($grupo) {
                return [
                    'quantidade' => $grupo->count(),
                    'custo_total' => $grupo->sum('custo_total_mao_obra'),
                ];
            })
        ];

        return response()->json($relatorio);
    }
}
