<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Marca::query();
        
        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mercado', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('active', false);
            }
        }
        
        $marcas = $query->latest()->paginate(15);
        
        return view('admin.marcas.index', compact('marcas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.marcas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:marcas,name',
                'regex:/^[A-Z0-9\s]+$/' // Apenas letras maiúsculas, números e espaços
            ],
            'mercado' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Z0-9\s]+$/' // Apenas letras maiúsculas, números e espaços
            ],
            'active' => 'boolean'
        ], [
            'name.regex' => 'O campo MARCA deve conter apenas letras maiúsculas, números e espaços, sem acentuação.',
            'mercado.regex' => 'O campo MERCADO deve conter apenas letras maiúsculas, números e espaços, sem acentuação.',
            'name.required' => 'O campo MARCA é obrigatório.',
            'mercado.required' => 'O campo MERCADO é obrigatório.'
        ]);
        
        try {
            Marca::create($validated);
            
            return redirect()->route('admin.marcas.index')
                           ->with('success', 'Marca criada com sucesso!');
                           
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao criar marca: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Marca $marca)
    {
        // Removido: $marca->load('orcamentosPrestador.orcamento'); - orçamentos foram removidos do sistema
        
        return view('admin.marcas.show', compact('marca'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        return view('admin.marcas.edit', compact('marca'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('marcas')->ignore($marca->id),
                'regex:/^[A-Z0-9\s]+$/' // Apenas letras maiúsculas, números e espaços
            ],
            'mercado' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Z0-9\s]+$/' // Apenas letras maiúsculas, números e espaços
            ],
            'active' => 'boolean'
        ], [
            'name.regex' => 'O campo MARCA deve conter apenas letras maiúsculas, números e espaços, sem acentuação.',
            'mercado.regex' => 'O campo MERCADO deve conter apenas letras maiúsculas, números e espaços, sem acentuação.',
            'name.required' => 'O campo MARCA é obrigatório.',
            'mercado.required' => 'O campo MERCADO é obrigatório.'
        ]);
        
        try {
            $marca->update($validated);
            
            return redirect()->route('admin.marcas.show', $marca)
                           ->with('success', 'Marca atualizada com sucesso!');
                           
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar marca: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        // Removido: verificação de orçamentos - orçamentos foram removidos do sistema
        
        try {
            $marca->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Marca excluída com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir marca: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Alterna o status ativo/inativo da marca
     */
    public function toggleStatus(Marca $marca)
    {
        try {
            $marca->update(['active' => !$marca->active]);
            
            $status = $marca->active ? 'ativada' : 'desativada';
            
            return response()->json([
                'success' => true,
                'message' => "Marca {$status} com sucesso!",
                'new_status' => $marca->active
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }
}
