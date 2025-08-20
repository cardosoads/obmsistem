<?php

namespace App\Http\Controllers;

use App\Models\CentroCusto;
use App\Models\Base;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CentroCustoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centrosCusto = CentroCusto::with(['base', 'marca'])
            ->orderBy('codigo')
            ->paginate(15);

        return view('admin.centros-custo.index', compact('centrosCusto'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bases = Base::active()->orderBy('name')->get();
        $marcas = Marca::active()->orderBy('name')->get();

        return view('admin.centros-custo.create', compact('bases', 'marcas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'codigo' => 'required|string|max:20|unique:centros_custo,codigo',
            'description' => 'nullable|string',
            'cliente_omie_id' => 'nullable|string|max:50',
            'cliente_nome' => 'nullable|string|max:255',
            'base_id' => 'required|exists:bases,id',
            'marca_id' => 'required|exists:marcas,id',
            'mercado' => 'nullable|string|max:100',
            'active' => 'boolean'
        ]);

        DB::transaction(function () use ($validated) {
            $centroCusto = CentroCusto::create($validated);
            
            // Atualiza os campos da base automaticamente
            $centroCusto->updateBaseFields();
            $centroCusto->save();
        });

        return redirect()->route('admin.centros-custo.index')
            ->with('success', 'Centro de custo criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CentroCusto $centroCusto)
    {
        $centroCusto->load(['base', 'marca']);
        return view('admin.centros-custo.show', compact('centroCusto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CentroCusto $centroCusto)
    {
        $bases = Base::active()->orderBy('name')->get();
        $marcas = Marca::active()->orderBy('name')->get();

        return view('admin.centros-custo.edit', compact('centroCusto', 'bases', 'marcas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CentroCusto $centroCusto)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'codigo' => 'required|string|max:20|unique:centros_custo,codigo,' . $centroCusto->id,
            'description' => 'nullable|string',
            'cliente_omie_id' => 'nullable|string|max:50',
            'cliente_nome' => 'nullable|string|max:255',
            'base_id' => 'required|exists:bases,id',
            'marca_id' => 'required|exists:marcas,id',
            'mercado' => 'nullable|string|max:100',
            'active' => 'boolean'
        ]);

        DB::transaction(function () use ($centroCusto, $validated) {
            $centroCusto->update($validated);
            
            // Atualiza os campos da base se a base foi alterada
            if ($centroCusto->wasChanged('base_id')) {
                $centroCusto->updateBaseFields();
                $centroCusto->save();
            }
        });

        return redirect()->route('admin.centros-custo.index')
            ->with('success', 'Centro de custo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CentroCusto $centroCusto)
    {
        try {
            $centroCusto->delete();
            return redirect()->route('admin.centros-custo.index')
                ->with('success', 'Centro de custo excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('admin.centros-custo.index')
                ->with('error', 'Erro ao excluir centro de custo: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint para buscar dados da base via AJAX
     */
    public function getBaseData(Base $base)
    {
        return response()->json([
            'regional' => $base->regional,
            'sigla' => $base->sigla,
            'uf' => $base->uf,
            'supervisor' => $base->supervisor
        ]);
    }
}
