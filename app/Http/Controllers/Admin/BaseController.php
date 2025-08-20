<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Base;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Base::query();
        
        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('city', 'like', "%{$search}%")
                  ->orWhere('uf', 'like', "%{$search}%")
                  ->orWhere('regional', 'like', "%{$search}%")
                  ->orWhere('sigla', 'like', "%{$search}%")
                  ->orWhere('supervisor', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('active', false);
            }
        }
        
        $bases = $query->latest()->paginate(15);
        
        return view('admin.bases.index', compact('bases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bases.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:100',
            'uf' => 'nullable|string|size:2',
            'regional' => 'nullable|string|max:50',
            'sigla' => 'nullable|string|max:3|regex:/^[A-Z0-9\s]+$/',
            'supervisor' => 'nullable|string|max:255',
            'active' => 'boolean'
        ]);
        
        try {
            Base::create($validated);
            
            return redirect()->route('admin.bases.index')
                           ->with('success', 'Base criada com sucesso!');
                           
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao criar base: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Base $base)
    {
        $base->load(['orcamentosOrigem', 'orcamentosDestino']);
        
        return view('admin.bases.show', compact('base'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Base $base)
    {
        return view('admin.bases.edit', compact('base'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Base $base)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:100',
            'uf' => 'nullable|string|size:2',
            'regional' => 'nullable|string|max:50',
            'sigla' => 'nullable|string|max:3|regex:/^[A-Z0-9\s]+$/',
            'supervisor' => 'nullable|string|max:255',
            'active' => 'boolean'
        ]);
        
        try {
            $base->update($validated);
            
            return redirect()->route('admin.bases.show', $base)
                           ->with('success', 'Base atualizada com sucesso!');
                           
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar base: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Base $base)
    {
        // Verificar se a base possui orçamentos como origem ou destino
        $orcamentosOrigem = $base->orcamentosOrigem()->count();
        $orcamentosDestino = $base->orcamentosDestino()->count();
        
        if ($orcamentosOrigem > 0 || $orcamentosDestino > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível excluir esta base pois ela possui orçamentos associados.'
            ], 422);
        }
        
        try {
            $base->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Base excluída com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir base: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Alterna o status ativo/inativo da base
     */
    public function toggleStatus(Base $base)
    {
        try {
            $base->update(['active' => !$base->active]);
            
            $status = $base->active ? 'ativada' : 'desativada';
            
            return response()->json([
                'success' => true,
                'message' => "Base {$status} com sucesso!",
                'new_status' => $base->active
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }
}
