<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CentroCusto;
use App\Models\Base;
use App\Models\Marca;
use App\Services\OmieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;

class CentroCustoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CentroCusto::with(['base', 'marca']);
        
        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('cliente_nome', 'like', "%{$search}%")
                  ->orWhere('omie_codigo', 'like', "%{$search}%")
                  ->orWhere('omie_estrutura', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('active', false);
            }
        }
        
        // Filtro de sincronização
        if ($request->filled('sync_status')) {
            if ($request->sync_status === 'sincronizado') {
                $query->sincronizados();
            } elseif ($request->sync_status === 'nao_sincronizado') {
                $query->naoSincronizados();
            } elseif ($request->sync_status === 'precisa_preenchimento') {
                $query->sincronizados()->where(function($q) {
                    $q->whereNull('name')
                      ->orWhereNull('description')
                      ->orWhereNull('base_id')
                      ->orWhereNull('marca_id');
                });
            }
        }
        
        // Filtro de status Omie
        if ($request->filled('omie_status')) {
            if ($request->omie_status === 'ativo_omie') {
                $query->ativosOmie();
            } elseif ($request->omie_status === 'inativo_omie') {
                $query->inativosOmie();
            }
        }
        
        $centrosCusto = $query->latest('sincronizado_em')->paginate(15);
        
        return view('admin.centros-custo.index', compact('centrosCusto'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bases = Base::where('active', true)->orderBy('name')->get();
        $marcas = Marca::where('active', true)->orderBy('name')->get();
        
        return view('admin.centros-custo.create', compact('bases', 'marcas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:centros_custo,codigo',
            'description' => 'nullable|string|max:500',
            'cliente_omie_id' => 'nullable|string|max:50',
            'cliente_nome' => 'nullable|string|max:255',
            'base_id' => 'nullable|exists:bases,id',
            'regional' => 'nullable|string|max:255',
            'sigla' => 'nullable|string|max:10',
            'uf' => 'nullable|string|max:2',
            'supervisor' => 'nullable|string|max:255',
            'marca_id' => 'nullable|exists:marcas,id',
            'mercado' => 'nullable|string|max:255',
            'active' => 'boolean',
            // Campos da API Omie (somente leitura via interface)
            'omie_codigo' => 'nullable|string|max:50',
            'omie_estrutura' => 'nullable|string|max:100',
            'omie_inativo' => 'boolean'
        ]);
        
        try {
            $centroCusto = CentroCusto::create($validated);
            
            // Atualizar campos da base se uma base foi selecionada
            if ($validated['base_id']) {
                $centroCusto->updateBaseFields();
            }
            
            return redirect()->route('admin.centros-custo.index')
                           ->with('success', 'Centro de custo criado com sucesso!');
                           
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao criar centro de custo: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CentroCusto $centroCusto)
    {
        $centroCusto->load(['base', 'marca']); // Removido: 'orcamentos' - orçamentos foram removidos do sistema
        
        return view('admin.centros-custo.show', compact('centroCusto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CentroCusto $centroCusto)
    {
        $bases = Base::where('active', true)->orderBy('name')->get();
        $marcas = Marca::where('active', true)->orderBy('name')->get();
        
        return view('admin.centros-custo.edit', compact('centroCusto', 'bases', 'marcas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CentroCusto $centroCusto)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'codigo' => ['required', 'string', 'max:50', Rule::unique('centros_custo')->ignore($centroCusto->id)],
            'description' => 'nullable|string|max:500',
            'cliente_omie_id' => 'nullable|string|max:50',
            'cliente_nome' => 'nullable|string|max:255',
            'base_id' => 'nullable|exists:bases,id',
            'regional' => 'nullable|string|max:255',
            'sigla' => 'nullable|string|max:10',
            'uf' => 'nullable|string|max:2',
            'supervisor' => 'nullable|string|max:255',
            'marca_id' => 'nullable|exists:marcas,id',
            'mercado' => 'nullable|string|max:255',
            'active' => 'boolean'
        ]);
        
        // Remove campos da API Omie da validação para update
        // Estes campos só podem ser atualizados via sincronização
        unset($validated['omie_codigo'], $validated['omie_estrutura'], $validated['omie_inativo']);
        
        try {
            $centroCusto->update($validated);
            
            // Atualizar campos da base se uma base foi selecionada
            if ($validated['base_id']) {
                $centroCusto->updateBaseFields();
            }
            
            return redirect()->route('admin.centros-custo.show', $centroCusto)
                           ->with('success', 'Centro de custo atualizado com sucesso!');
                           
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar centro de custo: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CentroCusto $centroCusto)
    {
        // Removido: verificação de orçamentos - orçamentos foram removidos do sistema
        
        try {
            $centroCusto->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Centro de custo excluído com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir centro de custo: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Alterna o status ativo/inativo do centro de custo
     */
    public function toggleStatus(CentroCusto $centroCusto)
    {
        try {
            $centroCusto->update(['active' => !$centroCusto->active]);
            
            $status = $centroCusto->active ? 'ativado' : 'desativado';
            
            return response()->json([
                'success' => true,
                'message' => "Centro de custo {$status} com sucesso!",
                'new_status' => $centroCusto->active
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Busca dados da base para preenchimento automático via AJAX
     */
    public function getBaseData(Base $base)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'regional' => $base->regional,
                    'sigla' => $base->sigla,
                    'uf' => $base->uf,
                    'supervisor' => $base->supervisor
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar dados da base: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Executa a sincronização com a API Omie via interface web
     */
    public function sincronizar()
    {
        try {
            // Executa o comando de sincronização
            Artisan::call('omie:sync-centros-custo');
            $output = Artisan::output();
            
            // Extrai estatísticas do output
            preg_match('/Total processados: (\d+)/', $output, $totalMatches);
            preg_match('/Novos: (\d+)/', $output, $novosMatches);
            preg_match('/Atualizados: (\d+)/', $output, $atualizadosMatches);
            
            $total = $totalMatches[1] ?? 0;
            $novos = $novosMatches[1] ?? 0;
            $atualizados = $atualizadosMatches[1] ?? 0;
            
            return response()->json([
                'success' => true,
                'message' => 'Sincronização concluída com sucesso!',
                'data' => [
                    'total_processados' => $total,
                    'novos' => $novos,
                    'atualizados' => $atualizados
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro durante a sincronização: ' . $e->getMessage()
            ], 500);
        }
    }
}
