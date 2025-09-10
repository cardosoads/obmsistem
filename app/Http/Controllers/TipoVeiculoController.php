<?php

namespace App\Http\Controllers;

use App\Models\TipoVeiculo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TipoVeiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = TipoVeiculo::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('codigo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipo_combustivel')) {
            $query->where('tipo_combustivel', $request->get('tipo_combustivel'));
        }

        if ($request->filled('status')) {
            $active = $request->get('status') === 'ativo';
            $query->where('active', $active);
        }

        $tiposVeiculos = $query->orderBy('codigo')->paginate(15);

        return view('admin.tipos-veiculos.index', compact('tiposVeiculos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.tipos-veiculos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:tipos_veiculos,codigo',
            'consumo_km_litro' => 'required|numeric|min:0|max:999.99',
            'tipo_combustivel' => 'required|in:Gasolina,Etanol,Diesel',
            'descricao' => 'required|string|max:255',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active');

        TipoVeiculo::create($validated);

        return redirect()->route('admin.tipos-veiculos.index')
            ->with('success', 'Tipo de veículo criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoVeiculo $tipoVeiculo): View
    {
        $tipoVeiculo->load('frotas');
        return view('admin.tipos-veiculos.show', compact('tipoVeiculo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoVeiculo $tipoVeiculo): View
    {
        return view('admin.tipos-veiculos.edit', compact('tipoVeiculo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoVeiculo $tipoVeiculo): RedirectResponse
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:tipos_veiculos,codigo,' . $tipoVeiculo->id,
            'consumo_km_litro' => 'required|numeric|min:0|max:999.99',
            'tipo_combustivel' => 'required|in:Gasolina,Etanol,Diesel',
            'descricao' => 'required|string|max:255',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active');

        $tipoVeiculo->update($validated);

        return redirect()->route('admin.tipos-veiculos.index')
            ->with('success', 'Tipo de veículo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoVeiculo $tipoVeiculo): JsonResponse
    {
        try {
            // Verificar se há frotas associadas
            if ($tipoVeiculo->frotas()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível excluir este tipo de veículo pois há frotas associadas.'
                ], 422);
            }

            $tipoVeiculo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tipo de veículo excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir tipo de veículo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle status do tipo de veículo
     */
    public function toggleStatus(TipoVeiculo $tipoVeiculo): JsonResponse
    {
        try {
            $tipoVeiculo->update(['active' => !$tipoVeiculo->active]);

            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso!',
                'active' => $tipoVeiculo->active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage()
            ], 500);
        }
    }
}
