<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Imposto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ImpostoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Imposto::query();
        
        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('active', false);
            }
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $impostos = $query->latest()->paginate(15);
        
        return view('admin.impostos.index', compact('impostos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.impostos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0|max:999999999.99',
            'active' => 'boolean'
        ]);
        
        // Validação adicional para percentual
        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return back()->withInput()->withErrors(['value' => 'O valor percentual não pode ser maior que 100%.']);
        }
        
        try {
            Imposto::create($validated);
            
            return redirect()->route('admin.impostos.index')
                           ->with('success', 'Imposto criado com sucesso!');
                           
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao criar imposto: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Imposto $imposto)
    {
        return view('admin.impostos.show', compact('imposto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Imposto $imposto)
    {
        return view('admin.impostos.edit', compact('imposto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Imposto $imposto)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0|max:999999999.99',
            'active' => 'boolean'
        ]);
        
        // Validação adicional para percentual
        if ($validated['type'] === 'percentage' && $validated['value'] > 100) {
            return back()->withInput()->withErrors(['value' => 'O valor percentual não pode ser maior que 100%.']);
        }
        
        try {
            $imposto->update($validated);
            
            return redirect()->route('admin.impostos.show', $imposto)
                           ->with('success', 'Imposto atualizado com sucesso!');
                           
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar imposto: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Imposto $imposto)
    {
        try {
            $imposto->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Imposto excluído com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir imposto: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Alterna o status ativo/inativo do imposto
     */
    public function toggleStatus(Imposto $imposto)
    {
        try {
            $imposto->update(['active' => !$imposto->active]);
            
            $status = $imposto->active ? 'ativado' : 'desativado';
            
            return response()->json([
                'success' => true,
                'message' => "Imposto {$status} com sucesso!",
                'new_status' => $imposto->active
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }
}
