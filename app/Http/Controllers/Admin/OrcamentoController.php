<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orcamento;
use App\Models\OrcamentoPrestador;
use App\Models\OrcamentoAumentoKm;
use App\Models\OrcamentoProprioNovaRota;
use App\Models\CentroCusto;
use App\Models\GrupoImposto;
use App\Models\RecursoHumano;
use App\Models\Frota;
use App\Models\Base;
use App\Services\OmieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrcamentoController extends Controller
{
    private OmieService $omieService;

    public function __construct(OmieService $omieService)
    {
        $this->omieService = $omieService;
    }

    /**
     * Buscar cargos disponíveis por base específica
     */
    public function buscarCargosPorBase(Request $request)
    {
        $request->validate([
            'base_id' => 'required|exists:bases,id'
        ]);

        $cargos = RecursoHumano::where('base_id', $request->base_id)
                              ->where('active', true)
                              ->select('cargo')
                              ->distinct()
                              ->orderBy('cargo')
                              ->pluck('cargo');

        return response()->json([
            'success' => true,
            'cargos' => $cargos
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Orcamento::with(['centroCusto', 'user']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('cliente_nome')) {
            $query->where('cliente_nome', 'like', '%' . $request->cliente_nome . '%');
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('data_orcamento', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_orcamento', '<=', $request->data_fim);
        }

        // Nova estrutura de busca com select de tipo usando OmieService
        if ($request->filled('search_value') && $request->filled('search_type')) {
            $searchValue = $request->search_value;
            $searchType = $request->search_type;

            switch ($searchType) {
                case 'geral':
                    $query->where(function($q) use ($searchValue) {
                        $q->where('numero_orcamento', 'like', '%' . $searchValue . '%')
                          ->orWhere('nome_rota', 'like', '%' . $searchValue . '%')
                          ->orWhere('cliente_nome', 'like', '%' . $searchValue . '%')
                          ->orWhere('id_protocolo', 'like', '%' . $searchValue . '%')
                          ->orWhere('cliente_omie_id', 'like', '%' . $searchValue . '%')
                          ->orWhere('id_logcare', 'like', '%' . $searchValue . '%')
                          ->orWhereHas('centroCusto', function($q) use ($searchValue) {
                              $q->where('codigo', 'like', '%' . $searchValue . '%')
                                ->orWhere('name', 'like', '%' . $searchValue . '%');
                          });
                    });
                    break;
                case 'id_protocolo':
                    $query->where('id_protocolo', 'like', '%' . $searchValue . '%');
                    break;
                case 'cliente_omie_id':
                    // Busca melhorada usando OmieService
                    $this->applyClienteOmieIdSearch($query, $searchValue);
                    break;
                case 'cliente_nome':
                    // Busca melhorada usando OmieService
                    $this->applyClienteNomeSearch($query, $searchValue);
                    break;
                case 'cliente_documento':
                    // Busca por documento usando OmieService
                    $this->applyClienteDocumentoSearch($query, $searchValue);
                    break;
                case 'id_logcare':
                    $query->where('id_logcare', 'like', '%' . $searchValue . '%');
                    break;
                case 'centro_custo_codigo':
                    $query->whereHas('centroCusto', function($q) use ($searchValue) {
                        $q->where('codigo', 'like', '%' . $searchValue . '%')
                          ->orWhere('name', 'like', '%' . $searchValue . '%');
                    });
                    break;
            }
        }

        $orcamentos = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.orcamentos.index', compact('orcamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $centrosCusto = CentroCusto::where('active', true)->get();
        $gruposImpostos = GrupoImposto::where('ativo', true)->with('impostos')->get();
        
        return view('admin.orcamentos.create', compact('centrosCusto', 'gruposImpostos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log temporário para debug - dados do request
        \Log::info('Dados do request no store:', [
            'valor_lucro' => $request->input('valor_lucro'),
            'valor_impostos' => $request->input('valor_impostos'),
            'percentual_lucro' => $request->input('percentual_lucro'),
            'percentual_impostos' => $request->input('percentual_impostos'),
            'all_request' => $request->all()
        ]);
        
        $validated = $request->validate([
            'data_solicitacao' => 'required|date',
            'centro_custo_id' => 'required|exists:centros_custo,id',
            'id_protocolo' => 'required|string|max:255',
            'nome_rota' => 'required|string|max:255',
            'id_logcare' => 'nullable|string|max:255',
            'cliente_omie_id' => 'nullable|string|max:255',
            'cliente_nome' => 'nullable|string|max:255',
            'horario' => 'nullable|date_format:H:i',
            'frequencia_atendimento' => 'nullable|array',
            'frequencia_atendimento.*' => 'string|in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
            'tipo_orcamento' => 'required|in:prestador,aumento_km,proprio_nova_rota',
            'status' => 'nullable|in:em_andamento,enviado,aprovado,rejeitado,cancelado',
            'observacoes' => 'nullable|string',
            // Campos específicos do prestador
            'fornecedor_omie_id' => 'required_if:tipo_orcamento,prestador|nullable|integer',
            'fornecedor_nome' => 'required_if:tipo_orcamento,prestador|nullable|string|max:255',
            'valor_referencia' => 'required_if:tipo_orcamento,prestador|nullable|numeric|min:0',
            'qtd_dias' => 'required_if:tipo_orcamento,prestador|nullable|integer|min:1',
            'custo_fornecedor' => 'nullable|numeric|min:0',
            'percentual_lucro' => 'nullable|numeric|min:0|max:100',
            'valor_lucro' => 'nullable|numeric|min:0',
            'percentual_impostos' => 'nullable|numeric|min:0|max:100',
            'valor_impostos' => 'nullable|numeric|min:0',
            'valor_total' => 'nullable|numeric|min:0',
            'grupo_imposto_id' => 'nullable|exists:grupos_impostos,id',
            // Campos específicos do Aumento KM
            'km_dia' => 'required_if:tipo_orcamento,aumento_km|nullable|numeric|min:0',
            'qtd_dias_aumento' => 'required_if:tipo_orcamento,aumento_km|nullable|integer|min:1',
            'combustivel_km_litro' => 'required_if:tipo_orcamento,aumento_km|nullable|numeric|min:0',
            'valor_combustivel' => 'required_if:tipo_orcamento,aumento_km|nullable|numeric|min:0',
            'hora_extra' => 'nullable|numeric|min:0',
            'pedagio' => 'nullable|numeric|min:0',
            'grupo_imposto_id_aumento' => 'nullable|exists:grupos_impostos,id',
            'lucro_percentual_aumento' => 'nullable|numeric|min:0|max:100',
            'impostos_percentual_aumento' => 'nullable|numeric|min:0|max:100',
            'valor_lucro_aumento' => 'nullable|numeric|min:0',
            'valor_impostos_aumento' => 'nullable|numeric|min:0',
            // Campos específicos do Próprio Nova Rota
            'motivo_alteracao' => 'nullable|string|max:500',
            // Campos funcionário
            'tem_funcionario' => 'nullable|boolean',
            'recurso_humano_id' => 'required_if:tem_funcionario,1|nullable|exists:recursos_humanos,id',
            'cargo_funcionario' => 'nullable|string|max:255',
            'base_funcionario_id' => 'nullable|exists:bases,id',
            'valor_funcionario' => 'nullable|numeric|min:0',
            // Campos veículo próprio
            'tem_veiculo_proprio' => 'nullable|boolean',
            'frota_id' => 'required_if:tem_veiculo_proprio,1|nullable|exists:frotas,id',
            'valor_aluguel_veiculo' => 'nullable|numeric|min:0',
            // Campos prestador
            'tem_prestador' => 'nullable|boolean',
            'fornecedor_omie_id_prestador' => 'required_if:tem_prestador,1|nullable|string',
            'fornecedor_nome_prestador' => 'required_if:tem_prestador,1|nullable|string|max:255',
            'valor_referencia_prestador' => 'required_if:tem_prestador,1|nullable|numeric|min:0',
            'qtd_dias_prestador' => 'required_if:tem_prestador,1|nullable|integer|min:1',
            'lucro_percentual_prestador' => 'nullable|numeric|min:0|max:100',
            'impostos_percentual_prestador' => 'nullable|numeric|min:0|max:100',
            'grupo_imposto_prestador_id' => 'nullable|exists:grupos_impostos,id',
            // Campos específicos do Próprio Nova Rota
            'nova_origem' => 'required_if:tipo_orcamento,proprio_nova_rota|nullable|string|max:255',
            'novo_destino' => 'required_if:tipo_orcamento,proprio_nova_rota|nullable|string|max:255',
            'km_nova_rota' => 'required_if:tipo_orcamento,proprio_nova_rota|nullable|numeric|min:0',
            'valor_km_nova_rota' => 'required_if:tipo_orcamento,proprio_nova_rota|nullable|numeric|min:0',
            'motivo_alteracao' => 'nullable|string|max:500',
            // Campos funcionário
            'tem_funcionario' => 'nullable|boolean',
            'cargo_funcionario' => 'required_if:tem_funcionario,1|nullable|string|max:255',
            'base_funcionario_id' => 'required_if:tem_funcionario,1|nullable|exists:bases,id',
            'valor_funcionario' => 'nullable|numeric|min:0',
            // Campos veículo próprio
            'tem_veiculo_proprio' => 'nullable|boolean',
            'frota_id' => 'required_if:tem_veiculo_proprio,1|nullable|exists:frotas,id',
            'valor_aluguel_veiculo' => 'nullable|numeric|min:0',
            // Campos prestador
            'tem_prestador' => 'nullable|boolean',
            'fornecedor_omie_id_prestador' => 'required_if:tem_prestador,1|nullable|string',
            'fornecedor_nome_prestador' => 'required_if:tem_prestador,1|nullable|string|max:255',
            'valor_referencia_prestador' => 'required_if:tem_prestador,1|nullable|numeric|min:0',
            'qtd_dias_prestador' => 'required_if:tem_prestador,1|nullable|integer|min:1',
            'lucro_percentual_prestador' => 'nullable|numeric|min:0|max:100',
            'impostos_percentual_prestador' => 'nullable|numeric|min:0|max:100',
            'grupo_imposto_prestador_id' => 'nullable|exists:grupos_impostos,id'
        ]);

        try {
            DB::beginTransaction();

            $validated['user_id'] = Auth::id();
            $validated['data_orcamento'] = now()->format('Y-m-d');
            
            // Filtrar campos específicos baseado no tipo de orçamento
            $orcamentoData = $this->filtrarCamposOrcamento($validated);
            
            $orcamento = Orcamento::create($orcamentoData);

            // Criar registro específico baseado no tipo de orçamento
            $this->criarRegistroEspecifico($orcamento, $validated);

            DB::commit();

            return redirect()->route('admin.orcamentos.show', $orcamento)
                           ->with('success', 'Orçamento criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->with('error', 'Erro ao criar orçamento: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Orcamento $orcamento)
    {
        $orcamento->load([
            'centroCusto', 
            'user',
            'orcamentoPrestador.grupoImposto',
            'orcamentoAumentoKm.grupoImposto',
            'orcamentoProprioNovaRota'
        ]);
        
        return view('admin.orcamentos.show', compact('orcamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orcamento $orcamento)
    {
        $centrosCusto = CentroCusto::where('active', true)->get();
        $gruposImpostos = GrupoImposto::where('ativo', true)->with('impostos')->get();
        
        // Carregar relacionamentos específicos baseados no tipo
        $orcamento->load([
            'centroCusto',
            'orcamentoPrestador.grupoImposto',
            'orcamentoAumentoKm',
            'orcamentoProprioNovaRota'
        ]);
        
        return view('admin.orcamentos.edit', compact('orcamento', 'centrosCusto', 'gruposImpostos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orcamento $orcamento)
    {
        // DEBUG: Log dos dados recebidos
        \Log::info('Dados recebidos no update:', $request->all());
        
        $validated = $request->validate([
            'data_solicitacao' => 'required|date',
            'centro_custo_id' => 'required|exists:centros_custo,id',
            'id_protocolo' => 'nullable|string|max:255',
            'nome_rota' => 'required|string|max:255',
            'id_logcare' => 'nullable|string|max:255',
            'cliente_omie_id' => 'nullable|string|max:255',
            'cliente_nome' => 'nullable|string|max:255',
            'horario' => 'nullable|date_format:H:i',
            'frequencia_atendimento' => 'nullable|array',
            'frequencia_atendimento.*' => 'string|in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
            'tipo_orcamento' => 'required|in:prestador,aumento_km,proprio_nova_rota',
            'status' => 'required|in:em_andamento,enviado,aprovado,rejeitado,cancelado',
            'observacoes' => 'nullable|string',
            // Campos específicos do prestador
            'fornecedor_omie_id' => 'required_if:tipo_orcamento,prestador|nullable|integer',
            'fornecedor_nome' => 'required_if:tipo_orcamento,prestador|nullable|string|max:255',
            'valor_referencia' => 'required_if:tipo_orcamento,prestador|nullable|numeric|min:0',
            'qtd_dias' => 'required_if:tipo_orcamento,prestador|nullable|integer|min:1',
            'custo_fornecedor' => 'nullable|numeric|min:0',
            'percentual_lucro' => 'nullable|numeric|min:0|max:100',
            'valor_lucro' => 'nullable|numeric|min:0',
            'percentual_impostos' => 'nullable|numeric|min:0|max:100',
            'valor_impostos' => 'nullable|numeric|min:0',
            'valor_total' => 'nullable|numeric|min:0',
            'grupo_imposto_id' => 'nullable|exists:grupos_impostos,id',
            // Campos específicos do Aumento KM
            'km_dia' => 'required_if:tipo_orcamento,aumento_km|nullable|numeric|min:0',
            'qtd_dias_aumento' => 'required_if:tipo_orcamento,aumento_km|nullable|integer|min:1',
            'combustivel_km_litro' => 'required_if:tipo_orcamento,aumento_km|nullable|numeric|min:0',
            'valor_combustivel' => 'required_if:tipo_orcamento,aumento_km|nullable|numeric|min:0',
            'hora_extra' => 'nullable|numeric|min:0',
            'pedagio' => 'nullable|numeric|min:0',
            'grupo_imposto_id_aumento' => 'nullable|exists:grupos_impostos,id',
            'lucro_percentual_aumento' => 'nullable|numeric|min:0|max:100',
            'impostos_percentual_aumento' => 'nullable|numeric|min:0|max:100',
            'valor_lucro_aumento' => 'nullable|numeric|min:0',
            'valor_impostos_aumento' => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            // Filtrar campos específicos baseado no tipo de orçamento
            $orcamentoData = $this->filtrarCamposOrcamento($validated);
            
            $orcamento->update($orcamentoData);

            // Atualizar ou criar registro específico baseado no tipo de orçamento
            $this->atualizarRegistroEspecifico($orcamento, $validated);
            
            DB::commit();

            // Recarregar o orçamento do banco de dados para garantir que temos os dados persistidos
            $orcamento->refresh();
            $orcamento->load([
                'centroCusto',
                'orcamentoPrestador.grupoImposto',
                'orcamentoAumentoKm',
                'orcamentoProprioNovaRota'
            ]);

            return redirect()->route('admin.orcamentos.show', $orcamento)
                           ->with('success', 'Orçamento atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                        ->with('error', 'Erro ao atualizar orçamento: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orcamento $orcamento)
    {
        try {
            $orcamento->delete();
            
            return redirect()->route('admin.orcamentos.index')
                           ->with('success', 'Orçamento excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir orçamento: ' . $e->getMessage());
        }
    }

    /**
     * Atualizar status do orçamento
     */
    public function updateStatus(Request $request, Orcamento $orcamento)
    {
        $validated = $request->validate([
            'status' => 'required|in:em_andamento,enviado,aprovado,rejeitado,cancelado'
        ]);

        try {
            $orcamento->update(['status' => $validated['status']]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aplicar busca por cliente Omie ID usando OmieService
     */
    private function applyClienteOmieIdSearch($query, $searchValue)
    {
        if (is_numeric($searchValue)) {
            try {
                $cliente = $this->omieService->getClientById((int)$searchValue);
                if ($cliente) {
                    $query->where('cliente_omie_id', $searchValue);
                } else {
                    // Se cliente não existe na API, não retorna resultados
                    $query->whereRaw('1 = 0');
                }
            } catch (\Exception $e) {
                \Log::warning('Erro ao buscar cliente por ID Omie', [
                    'omie_id' => $searchValue,
                    'error' => $e->getMessage()
                ]);
                // Em caso de erro na API, faz busca local
                $query->where('cliente_omie_id', 'like', '%' . $searchValue . '%');
            }
        } else {
            $query->where('cliente_omie_id', 'like', '%' . $searchValue . '%');
        }
    }

    /**
     * Aplicar busca por nome do cliente usando OmieService
     */
    private function applyClienteNomeSearch($query, $searchValue)
    {
        try {
            $clientesEncontrados = $this->omieService->searchClientsByTerm($searchValue);
            if (!empty($clientesEncontrados)) {
                $omieIds = array_column($clientesEncontrados, 'codigo_cliente_omie');
                $query->where(function($q) use ($searchValue, $omieIds) {
                    $q->where('cliente_nome', 'like', '%' . $searchValue . '%')
                      ->orWhereIn('cliente_omie_id', $omieIds);
                });
            } else {
                $query->where('cliente_nome', 'like', '%' . $searchValue . '%');
            }
        } catch (\Exception $e) {
            \Log::warning('Erro ao buscar clientes por termo', [
                'term' => $searchValue,
                'error' => $e->getMessage()
            ]);
            // Em caso de erro na API, faz busca local apenas
            $query->where('cliente_nome', 'like', '%' . $searchValue . '%');
        }
    }

    /**
      * Aplicar busca por documento do cliente usando OmieService
      */
    private function applyClienteDocumentoSearch($query, $searchValue)
    {
        try {
            $cliente = $this->omieService->getClientByDocument($searchValue);
            if ($cliente) {
                $query->where('cliente_omie_id', $cliente['codigo_cliente_omie']);
            } else {
                // Se cliente não existe, não retorna resultados
                $query->whereRaw('1 = 0');
            }
        } catch (\Exception $e) {
            \Log::warning('Erro ao buscar cliente por documento', [
                'document' => $searchValue,
                'error' => $e->getMessage()
            ]);
            // Em caso de erro na API, não retorna resultados
            $query->whereRaw('1 = 0');
        }
    }

    /**
     * Aplicar busca por ID do fornecedor usando OmieService
     */
    private function applyFornecedorOmieIdSearch($query, $searchValue)
    {
        try {
            $fornecedor = $this->omieService->consultarFornecedor($searchValue);
            if ($fornecedor && $fornecedor['success']) {
                $query->whereHas('orcamentoPrestador', function($q) use ($searchValue) {
                    $q->where('fornecedor_omie_id', $searchValue);
                });
            } else {
                // Se fornecedor não existe, não retorna resultados
                $query->whereRaw('1 = 0');
            }
        } catch (\Exception $e) {
            \Log::warning('Erro ao buscar fornecedor por ID Omie', [
                'fornecedor_id' => $searchValue,
                'error' => $e->getMessage()
            ]);
            // Em caso de erro na API, tenta busca local
            $query->whereHas('orcamentoPrestador', function($q) use ($searchValue) {
                $q->where('fornecedor_omie_id', $searchValue);
            });
        }
    }

    /**
     * Aplicar busca por nome do fornecedor usando OmieService
     */
    private function applyFornecedorNomeSearch($query, $searchValue)
    {
        try {
            $fornecedores = $this->omieService->searchSuppliersByTerm($searchValue);
            if ($fornecedores && !empty($fornecedores)) {
                $fornecedorIds = collect($fornecedores)->pluck('codigo_cliente_omie')->filter()->toArray();
                
                $query->where(function($q) use ($searchValue, $fornecedorIds) {
                    // Busca por nome local
                    $q->whereHas('orcamentoPrestador', function($subQ) use ($searchValue) {
                        $subQ->where('fornecedor_nome', 'like', '%' . $searchValue . '%');
                    });
                    
                    // Busca por IDs encontrados na API Omie
                    if (!empty($fornecedorIds)) {
                        $q->orWhereHas('orcamentoPrestador', function($subQ) use ($fornecedorIds) {
                            $subQ->whereIn('fornecedor_omie_id', $fornecedorIds);
                        });
                    }
                });
            } else {
                // Se não encontrou fornecedores na API, busca apenas local
                $query->whereHas('orcamentoPrestador', function($q) use ($searchValue) {
                    $q->where('fornecedor_nome', 'like', '%' . $searchValue . '%');
                });
            }
        } catch (\Exception $e) {
            \Log::warning('Erro ao buscar fornecedor por nome', [
                'search_term' => $searchValue,
                'error' => $e->getMessage()
            ]);
            // Em caso de erro na API, busca apenas local
            $query->whereHas('orcamentoPrestador', function($q) use ($searchValue) {
                $q->where('fornecedor_nome', 'like', '%' . $searchValue . '%');
            });
        }
    }

    /**
     * Filtrar campos do orçamento baseado no tipo
     */
    private function filtrarCamposOrcamento(array $validated)
    {
        // Campos que sempre devem ser incluídos
        $camposPermitidos = [
            'data_solicitacao',
            'id_protocolo',
            'centro_custo_id',
            'nome_rota',
            'id_logcare',
            'cliente_omie_id',
            'cliente_nome',
            'horario',
            'frequencia_atendimento',
            'tipo_orcamento',
            'observacoes',
            'user_id',
            'data_orcamento',
            'numero_orcamento',
            'status'
        ];
        
        // Adicionar campos específicos baseado no tipo
        if ($validated['tipo_orcamento'] === 'prestador') {
            $camposPermitidos = array_merge($camposPermitidos, [
                'percentual_lucro',
                'percentual_impostos',
                'valor_impostos',
                'valor_total'
            ]);
        } else {
            // Para outros tipos, apenas valor_impostos com valor 0
            $camposPermitidos[] = 'valor_impostos';
            $validated['valor_impostos'] = 0;
        }
        
        // Filtrar apenas os campos permitidos
        return array_intersect_key($validated, array_flip($camposPermitidos));
    }

    /**
     * Criar registro específico baseado no tipo de orçamento
     */
    private function criarRegistroEspecifico(Orcamento $orcamento, array $validated)
    {
        switch ($validated['tipo_orcamento']) {
            case 'prestador':
                $this->criarOrcamentoPrestador($orcamento, $validated);
                break;
            case 'aumento_km':
                $this->criarOrcamentoAumentoKm($orcamento, $validated);
                break;
            case 'proprio_nova_rota':
                $this->criarOrcamentoProprioNovaRota($orcamento, $validated);
                break;
        }
    }

    /**
     * Criar registro específico para orçamento de prestador
     */
    private function criarOrcamentoPrestador(Orcamento $orcamento, array $validated)
    {
        // Log temporário para debug
        \Log::info('Dados recebidos no criarOrcamentoPrestador:', [
            'valor_lucro' => $validated['valor_lucro'] ?? 'não definido',
            'valor_impostos' => $validated['valor_impostos'] ?? 'não definido',
            'percentual_lucro' => $validated['percentual_lucro'] ?? 'não definido',
            'percentual_impostos' => $validated['percentual_impostos'] ?? 'não definido',
            'all_validated' => $validated
        ]);
        
        $prestadorData = [
            'orcamento_id' => $orcamento->id,
            'fornecedor_omie_id' => $validated['fornecedor_omie_id'],
            'fornecedor_nome' => $validated['fornecedor_nome'],
            'valor_referencia' => $validated['valor_referencia'] ?? 0,
            'qtd_dias' => $validated['qtd_dias'] ?? 1,
            'custo_fornecedor' => $validated['custo_fornecedor'] ?? 0,
            'lucro_percentual' => $validated['percentual_lucro'] ?? 0,
            'valor_lucro' => $validated['valor_lucro'] ?? 0,
            'impostos_percentual' => $validated['percentual_impostos'] ?? 0,
            'valor_impostos' => $validated['valor_impostos'] ?? 0,
            'valor_total' => $validated['valor_total'] ?? 0,
            'grupo_imposto_id' => $validated['grupo_imposto_id'] ?? null,
            'observacoes' => $validated['observacoes'] ?? null
        ];

        $orcamentoPrestador = OrcamentoPrestador::create($prestadorData);
        
        // Definir dias iniciais baseados na frequência se não foi informado
        if (!$validated['qtd_dias']) {
            $orcamentoPrestador->definirDiasIniciais();
        }
        
        // Atualizar cálculos automáticos preservando os percentuais enviados
        $orcamentoPrestador->atualizarCalculosPreservandoPercentuais();
        
        // Atualizar valor total do orçamento principal
        $orcamento->update(['valor_total' => $orcamentoPrestador->valor_total]);
    }

    /**
     * Buscar percentual de impostos do grupo selecionado
     */
    public function buscarPercentualGrupoImposto(Request $request)
    {
        $grupoImpostoId = $request->input('grupo_imposto_id');
        
        if (!$grupoImpostoId) {
            return response()->json(['percentual' => 0]);
        }
        
        $grupoImposto = GrupoImposto::with('impostos')->find($grupoImpostoId);
        
        if (!$grupoImposto) {
            return response()->json(['percentual' => 0]);
        }
        
        // Calcular percentual total dos impostos ativos do grupo
        $percentualTotal = $grupoImposto->impostosAtivos->sum('percentual');
        
        return response()->json([
            'percentual' => $percentualTotal,
            'nome' => $grupoImposto->nome,
            'impostos' => $grupoImposto->impostosAtivos->map(function($imposto) {
                return [
                    'nome' => $imposto->nome,
                    'percentual' => $imposto->percentual
                ];
            })
        ]);
    }

    /**
     * Criar registro específico para orçamento de aumento KM
     */
    private function criarOrcamentoAumentoKm(Orcamento $orcamento, array $validated)
    {
        $aumentoKmData = [
            'orcamento_id' => $orcamento->id,
            'km_dia' => $validated['km_dia'] ?? 0,
            'qtd_dias' => $validated['qtd_dias_aumento'] ?? 1,
            'combustivel_km_litro' => $validated['combustivel_km_litro'] ?? 0,
            'valor_combustivel' => $validated['valor_combustivel'] ?? 0,
            'hora_extra' => $validated['hora_extra'] ?? 0,
            'pedagio' => $validated['pedagio'] ?? 0,
            'lucro_percentual' => $validated['lucro_percentual_aumento'] ?? 0,
            'impostos_percentual' => $validated['impostos_percentual_aumento'] ?? 0,
            'valor_lucro' => $validated['valor_lucro_aumento'] ?? 0,
            'valor_impostos' => $validated['valor_impostos_aumento'] ?? 0,
            'grupo_imposto_id' => $validated['grupo_imposto_id_aumento'] ?? null,
            'observacoes' => $validated['observacoes'] ?? null
        ];

        $orcamentoAumentoKm = OrcamentoAumentoKm::create($aumentoKmData);
        
        // Atualizar cálculos automáticos
        $orcamentoAumentoKm->calcularValores();
        $orcamentoAumentoKm->save();
        
        // Atualizar valor total do orçamento principal
        $orcamento->update(['valor_total' => $orcamentoAumentoKm->valor_total]);
    }

    /**
     * Criar registro específico para orçamento de próprio nova rota
     */
    private function criarOrcamentoProprioNovaRota(Orcamento $orcamento, array $validated)
    {
        $novaRotaData = [
            'orcamento_id' => $orcamento->id,
            'motivo_alteracao' => $validated['motivo_alteracao'] ?? null,
            'observacoes' => $validated['observacoes'] ?? null,
            // Campos funcionário
            'tem_funcionario' => $validated['tem_funcionario'] ?? false,
            'recurso_humano_id' => $validated['recurso_humano_id'] ?? null,
            'cargo_funcionario' => $validated['cargo_funcionario'] ?? null,
            'base_funcionario_id' => $validated['base_funcionario_id'] ?? null,
            'valor_funcionario' => $validated['valor_funcionario'] ?? 0,
            // Campos veículo próprio
            'tem_veiculo_proprio' => $validated['tem_veiculo_proprio'] ?? false,
            'frota_id' => $validated['frota_id'] ?? null,
            'valor_aluguel_veiculo' => $validated['valor_aluguel_veiculo'] ?? 0,
            // Campos prestador
            'tem_prestador' => $validated['tem_prestador'] ?? false,
            'fornecedor_omie_id' => $validated['fornecedor_omie_id_prestador'] ?? null,
            'fornecedor_nome' => $validated['fornecedor_nome_prestador'] ?? null,
            'valor_referencia_prestador' => $validated['valor_referencia_prestador'] ?? 0,
            'qtd_dias_prestador' => $validated['qtd_dias_prestador'] ?? 0,
            'lucro_percentual_prestador' => $validated['lucro_percentual_prestador'] ?? 0,
            'impostos_percentual_prestador' => $validated['impostos_percentual_prestador'] ?? 0,
            'grupo_imposto_prestador_id' => $validated['grupo_imposto_prestador_id'] ?? null
        ];

        $orcamentoNovaRota = OrcamentoProprioNovaRota::create($novaRotaData);
        
        // Buscar valores automáticos se necessário
        $this->buscarValoresAutomaticos($orcamentoNovaRota, $validated);
        
        // Atualizar cálculos automáticos
        $orcamentoNovaRota->atualizarCalculos();
        $orcamentoNovaRota->save();
        
        // Atualizar valor total do orçamento principal
         $orcamento->update(['valor_total' => $orcamentoNovaRota->valor_total_geral]);
     }

     /**
      * Busca valores automáticos para funcionário e veículo
      */
     private function buscarValoresAutomaticos(OrcamentoProprioNovaRota $orcamentoNovaRota, array $validated)
     {
         // Buscar valor do funcionário se habilitado
         if ($orcamentoNovaRota->tem_funcionario && $orcamentoNovaRota->recurso_humano_id) {
             $recursoHumano = RecursoHumano::where('id', $orcamentoNovaRota->recurso_humano_id)
                                         ->where('active', true)
                                         ->first();
             
             if ($recursoHumano) {
                 $orcamentoNovaRota->valor_funcionario = $recursoHumano->custo_total_mao_obra ?? 0;
                 $orcamentoNovaRota->cargo_funcionario = $recursoHumano->cargo;
                 $orcamentoNovaRota->base_funcionario_id = $recursoHumano->base_id;
             }
         }
         
         // Buscar valor do veículo se habilitado
         if ($orcamentoNovaRota->tem_veiculo_proprio && $orcamentoNovaRota->frota_id) {
             $frota = Frota::where('id', $orcamentoNovaRota->frota_id)
                          ->where('active', true)
                          ->first();
             
             if ($frota) {
                 $orcamentoNovaRota->valor_aluguel_veiculo = $frota->aluguel_carro ?? 0;
             }
         }
     }

     /**
      * Atualizar registro específico baseado no tipo de orçamento
      */
     private function atualizarRegistroEspecifico(Orcamento $orcamento, array $validated)
     {
         switch ($validated['tipo_orcamento']) {
             case 'prestador':
                 $this->atualizarOrcamentoPrestador($orcamento, $validated);
                 break;
             case 'aumento_km':
                 $this->atualizarOrcamentoAumentoKm($orcamento, $validated);
                 break;
             case 'proprio_nova_rota':
                 $this->atualizarOrcamentoProprioNovaRota($orcamento, $validated);
                 break;
         }
     }

     /**
      * Atualizar registro específico para orçamento de prestador
      */
     private function atualizarOrcamentoPrestador(Orcamento $orcamento, array $validated)
    {
        $prestadorData = [
            'fornecedor_omie_id' => $validated['fornecedor_omie_id'],
            'fornecedor_nome' => $validated['fornecedor_nome'],
            'valor_referencia' => $validated['valor_referencia'] ?? 0,
            'qtd_dias' => $validated['qtd_dias'] ?? 1,
            'custo_fornecedor' => $validated['custo_fornecedor'] ?? 0,
            'lucro_percentual' => $validated['percentual_lucro'] ?? 0,
            'valor_lucro' => $validated['valor_lucro'] ?? 0,
            'impostos_percentual' => $validated['percentual_impostos'] ?? 0,
            'valor_impostos' => $validated['valor_impostos'] ?? 0,
            'valor_total' => $validated['valor_total'] ?? 0,
            'grupo_imposto_id' => $validated['grupo_imposto_id'] ?? null,
            'observacoes' => $validated['observacoes'] ?? null
        ];

        $orcamentoPrestador = $orcamento->orcamentoPrestador;
        
        if ($orcamentoPrestador) {
            // Atualizar registro existente
            $orcamentoPrestador->update($prestadorData);
        } else {
            // Criar novo registro
            $prestadorData['orcamento_id'] = $orcamento->id;
            $orcamentoPrestador = OrcamentoPrestador::create($prestadorData);
        }
        
        // Atualizar cálculos automáticos preservando os percentuais enviados
        $orcamentoPrestador->atualizarCalculosPreservandoPercentuais();
         
         // Atualizar valor total do orçamento principal
         $orcamento->update(['valor_total' => $orcamentoPrestador->valor_total]);
     }

     /**
      * Atualizar registro específico para orçamento de aumento KM
      */
     private function atualizarOrcamentoAumentoKm(Orcamento $orcamento, array $validated)
     {
         // DEBUG: Log dos dados validados para aumento KM
         \Log::info('Dados validados para aumento KM:', $validated);
         
         $aumentoKmData = [
             'km_dia' => $validated['km_dia'] ?? 0,
             'qtd_dias' => $validated['qtd_dias_aumento'] ?? 1,
             'combustivel_km_litro' => $validated['combustivel_km_litro'] ?? 0,
             'valor_combustivel' => $validated['valor_combustivel'] ?? 0,
             'hora_extra' => $validated['hora_extra'] ?? 0,
             'pedagio' => $validated['pedagio'] ?? 0,
             'lucro_percentual' => $validated['lucro_percentual_aumento'] ?? 0,
             'impostos_percentual' => $validated['impostos_percentual_aumento'] ?? 0,
             'valor_lucro' => $validated['valor_lucro_aumento'] ?? 0,
             'valor_impostos' => $validated['valor_impostos_aumento'] ?? 0,
             'grupo_imposto_id' => $validated['grupo_imposto_id_aumento'] ?? null,
             'observacoes' => $validated['observacoes'] ?? null
         ];
         
         // DEBUG: Log dos dados que serão salvos
         \Log::info('Dados que serão salvos no aumento KM:', $aumentoKmData);

         $orcamentoAumentoKm = $orcamento->orcamentoAumentoKm;
         
         if ($orcamentoAumentoKm) {
             // Atualizar registro existente
             $orcamentoAumentoKm->update($aumentoKmData);
         } else {
             // Criar novo registro
             $aumentoKmData['orcamento_id'] = $orcamento->id;
             $orcamentoAumentoKm = OrcamentoAumentoKm::create($aumentoKmData);
         }
         
         // Atualizar cálculos automáticos
         $orcamentoAumentoKm->calcularValores();
         $orcamentoAumentoKm->save();
         
         // Atualizar valor total do orçamento principal
         $orcamento->update(['valor_total' => $orcamentoAumentoKm->valor_total]);
     }

     /**
      * Atualizar registro específico para orçamento de próprio nova rota
      */
     private function atualizarOrcamentoProprioNovaRota(Orcamento $orcamento, array $validated)
     {
         $novaRotaData = [
             'motivo_alteracao' => $validated['motivo_alteracao'] ?? null,
             'observacoes' => $validated['observacoes'] ?? null,
             // Campos funcionário
             'tem_funcionario' => $validated['tem_funcionario'] ?? false,
             'recurso_humano_id' => $validated['recurso_humano_id'] ?? null,
             'cargo_funcionario' => $validated['cargo_funcionario'] ?? null,
             'base_funcionario_id' => $validated['base_funcionario_id'] ?? null,
             'valor_funcionario' => $validated['valor_funcionario'] ?? 0,
             // Campos veículo próprio
             'tem_veiculo_proprio' => $validated['tem_veiculo_proprio'] ?? false,
             'frota_id' => $validated['frota_id'] ?? null,
             'valor_aluguel_veiculo' => $validated['valor_aluguel_veiculo'] ?? 0,
             // Campos prestador
             'tem_prestador' => $validated['tem_prestador'] ?? false,
             'fornecedor_omie_id' => $validated['fornecedor_omie_id_prestador'] ?? null,
             'fornecedor_nome' => $validated['fornecedor_nome_prestador'] ?? null,
             'valor_referencia_prestador' => $validated['valor_referencia_prestador'] ?? 0,
             'qtd_dias_prestador' => $validated['qtd_dias_prestador'] ?? 0,
             'lucro_percentual_prestador' => $validated['lucro_percentual_prestador'] ?? 0,
             'impostos_percentual_prestador' => $validated['impostos_percentual_prestador'] ?? 0,
             'grupo_imposto_prestador_id' => $validated['grupo_imposto_prestador_id'] ?? null
         ];

         $orcamentoNovaRota = $orcamento->orcamentoProprioNovaRota;
         
         if ($orcamentoNovaRota) {
             // Atualizar registro existente
             $orcamentoNovaRota->update($novaRotaData);
         } else {
             // Criar novo registro
             $novaRotaData['orcamento_id'] = $orcamento->id;
             $orcamentoNovaRota = OrcamentoProprioNovaRota::create($novaRotaData);
         }
         
         // Buscar valores automáticos se necessário
         $this->buscarValoresAutomaticos($orcamentoNovaRota, $validated);
         
         // Atualizar cálculos automáticos
         $orcamentoNovaRota->atualizarCalculos();
         $orcamentoNovaRota->save();
         
         // Atualizar valor total do orçamento principal
         $orcamento->update(['valor_total' => $orcamentoNovaRota->valor_total_nova_rota]);
     }

    /**
     * Gerar PDF do orçamento
     */
    public function gerarPdf(Orcamento $orcamento)
    {
        // Carregar relacionamentos necessários
        $orcamento->load([
            'centroCusto',
            'user',
            'orcamentoPrestador',
            'orcamentoAumentoKm',
            'orcamentoProprioNovaRota'
        ]);

        // Configurar opções do PDF
        $pdf = Pdf::loadView('admin.orcamentos.pdf', compact('orcamento'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => false
            ]);

        // Nome do arquivo
        $filename = 'orcamento_' . $orcamento->numero_orcamento . '_' . now()->format('Y-m-d') . '.pdf';

        // Retornar o PDF para download
        return $pdf->download($filename);
    }
 }
