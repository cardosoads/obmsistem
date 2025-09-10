<?php

namespace App\Http\Controllers;

use App\Models\Combustivel;
use App\Models\Base;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CombustivelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Combustivel::with('base');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('convenio', 'like', "%{$search}%")
                  ->orWhereHas('base', function ($baseQuery) use ($search) {
                      $baseQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('base_id')) {
            $query->where('base_id', $request->get('base_id'));
        }

        if ($request->filled('status')) {
            $active = $request->get('status') === 'ativo';
            $query->where('active', $active);
        }

        $combustiveis = $query->orderBy('preco_litro', 'asc')->paginate(15);
        $bases = Base::active()->orderBy('name')->get();

        // Estatísticas para o dashboard
        $totalCombustiveis = Combustivel::count();
        $combustiveisAtivos = Combustivel::where('active', true)->count();
        $precoMedio = Combustivel::where('active', true)->avg('preco_litro') ?? 0;
        $basesAtendidas = Combustivel::distinct('base_id')->count('base_id');

        return view('admin.combustiveis.index', compact(
            'combustiveis', 
            'bases', 
            'totalCombustiveis', 
            'combustiveisAtivos', 
            'precoMedio', 
            'basesAtendidas'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bases = Base::active()->orderBy('name')->get();
        return view('admin.combustiveis.create', compact('bases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'base_id' => 'required|exists:bases,id',
            'convenio' => 'required|string|max:255',
            'preco_litro' => 'required|numeric|min:0|max:99999.999',
            'active' => 'boolean',
        ], [
            'base_id.required' => 'A base é obrigatória.',
            'base_id.exists' => 'A base selecionada não existe.',
            'convenio.required' => 'O convênio é obrigatório.',
            'preco_litro.required' => 'O preço por litro é obrigatório.',
            'preco_litro.numeric' => 'O preço por litro deve ser um número.',
            'preco_litro.min' => 'O preço por litro deve ser maior que zero.',
        ]);

        // Verificar se já existe um convênio para esta base
        $existente = Combustivel::where('base_id', $validated['base_id'])
                                ->where('convenio', $validated['convenio'])
                                ->first();

        if ($existente) {
            return back()->withErrors([
                'convenio' => 'Já existe um convênio com este nome para a base selecionada.'
            ])->withInput();
        }

        $validated['active'] = $request->has('active');

        Combustivel::create($validated);

        return redirect()->route('admin.combustiveis.index')
            ->with('success', 'Combustível criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Combustivel $combustivel): View
    {
        $combustivel->load('base');
        
        // Calcular estatísticas de preços
        $precoMedio = Combustivel::where('active', true)->avg('preco_litro') ?? 0;
        $menorPreco = Combustivel::where('active', true)->min('preco_litro') ?? 0;
        $maiorPreco = Combustivel::where('active', true)->max('preco_litro') ?? 0;
        
        // Buscar outros combustíveis na mesma base (exceto o atual)
        $outrosCombustiveis = Combustivel::where('base_id', $combustivel->base_id)
                                        ->where('id', '!=', $combustivel->id)
                                        ->with('base')
                                        ->orderBy('convenio')
                                        ->get();
        
        return view('admin.combustiveis.show', compact(
            'combustivel', 
            'precoMedio', 
            'menorPreco', 
            'maiorPreco', 
            'outrosCombustiveis'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Combustivel $combustivel): View
    {
        $bases = Base::active()->orderBy('name')->get();
        
        // Calcular estatísticas de preços
        $precoMedio = Combustivel::where('active', true)->avg('preco_litro') ?? 0;
        $menorPreco = Combustivel::where('active', true)->min('preco_litro') ?? 0;
        $maiorPreco = Combustivel::where('active', true)->max('preco_litro') ?? 0;
        
        // Buscar outros combustíveis na mesma base (exceto o atual)
        $outrosCombustiveis = Combustivel::where('base_id', $combustivel->base_id)
                                        ->where('id', '!=', $combustivel->id)
                                        ->with('base')
                                        ->orderBy('convenio')
                                        ->get();
        
        return view('admin.combustiveis.edit', compact(
            'combustivel', 
            'bases', 
            'precoMedio', 
            'menorPreco', 
            'maiorPreco',
            'outrosCombustiveis'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Combustivel $combustivel): RedirectResponse
    {
        $validated = $request->validate([
            'base_id' => 'required|exists:bases,id',
            'convenio' => 'required|string|max:255',
            'preco_litro' => 'required|numeric|min:0|max:99999.999',
            'active' => 'boolean',
        ], [
            'base_id.required' => 'A base é obrigatória.',
            'base_id.exists' => 'A base selecionada não existe.',
            'convenio.required' => 'O convênio é obrigatório.',
            'preco_litro.required' => 'O preço por litro é obrigatório.',
            'preco_litro.numeric' => 'O preço por litro deve ser um número.',
            'preco_litro.min' => 'O preço por litro deve ser maior que zero.',
        ]);

        // Verificar se já existe um convênio para esta base (exceto o atual)
        $existente = Combustivel::where('base_id', $validated['base_id'])
                                ->where('convenio', $validated['convenio'])
                                ->where('id', '!=', $combustivel->id)
                                ->first();

        if ($existente) {
            return back()->withErrors([
                'convenio' => 'Já existe um convênio com este nome para a base selecionada.'
            ])->withInput();
        }

        $validated['active'] = $request->has('active');

        $combustivel->update($validated);

        return redirect()->route('admin.combustiveis.index')
            ->with('success', 'Combustível atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Combustivel $combustivel): JsonResponse
    {
        try {
            $combustivel->delete();

            return response()->json([
                'success' => true,
                'message' => 'Combustível excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir combustível: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle status do combustível
     */
    public function toggleStatus(Combustivel $combustivel): JsonResponse
    {
        try {
            $combustivel->update(['active' => !$combustivel->active]);

            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso!',
                'active' => $combustivel->active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obter combustíveis por base (AJAX)
     */
    public function getByBase(Base $base): JsonResponse
    {
        try {
            $combustiveis = $base->combustiveis()->active()->orderBy('convenio')->get();

            return response()->json([
                'success' => true,
                'combustiveis' => $combustiveis
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar combustíveis: ' . $e->getMessage()
            ], 500);
        }
    }
}
