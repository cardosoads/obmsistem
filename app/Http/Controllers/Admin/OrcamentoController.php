<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orcamento;
use App\Models\OrcamentoPrestador;
use App\Models\OrcamentoAumentoKm;
use App\Models\OrcamentoProprioNovaRota;
use App\Models\CentroCusto;
use App\Models\GrupoImposto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrcamentoController extends Controller
{
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_orcamento', 'like', '%' . $search . '%')
                  ->orWhere('nome_rota', 'like', '%' . $search . '%')
                  ->orWhere('cliente_nome', 'like', '%' . $search . '%');
            });
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
            'nome_rota' => 'required|string|max:255',
            'id_logcare' => 'nullable|string|max:255',
            'cliente_omie_id' => 'nullable|string|max:255',
            'cliente_nome' => 'nullable|string|max:255',
            'horario' => 'nullable|date_format:H:i',
            'frequencia_atendimento' => 'nullable|array',
            'frequencia_atendimento.*' => 'string|in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
            'tipo_orcamento' => 'required|in:prestador,aumento_km,proprio_nova_rota',
            'status' => 'nullable|in:rascunho,enviado,aprovado,rejeitado,cancelado',
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
            // Campos específicos do Próprio Nova Rota
            'nova_origem' => 'required_if:tipo_orcamento,proprio_nova_rota|nullable|string|max:255',
            'novo_destino' => 'required_if:tipo_orcamento,proprio_nova_rota|nullable|string|max:255',
            'km_nova_rota' => 'required_if:tipo_orcamento,proprio_nova_rota|nullable|numeric|min:0',
            'valor_km_nova_rota' => 'required_if:tipo_orcamento,proprio_nova_rota|nullable|numeric|min:0',
            'motivo_alteracao' => 'nullable|string|max:500',
            // Campos específicos do Aumento KM
            'km_dia' => 'required_if:tipo_orcamento,aumento_km|nullable|numeric|min:0',
            'qtd_dias_aumento' => 'required_if:tipo_orcamento,aumento_km|nullable|integer|min:1',
            'combustivel_km_litro' => 'required_if:tipo_orcamento,aumento_km|nullable|numeric|min:0',
            'valor_combustivel' => 'required_if:tipo_orcamento,aumento_km|nullable|numeric|min:0',
            'hora_extra' => 'nullable|numeric|min:0',
            // Campos específicos do Próprio Nova Rota
            'nova_origem' => 'required_if:tipo_orcamento,proprio_nova_rota|nullable|string|max:255',
            'novo_destino' => 'required_if:tipo_orcamento,proprio_nova_rota|nullable|string|max:255',
            'km_nova_rota' => 'required_if:tipo_orcamento,proprio_nova_rota|nullable|numeric|min:0',
            'valor_km_nova_rota' => 'required_if:tipo_orcamento,proprio_nova_rota|nullable|numeric|min:0',
            'motivo_alteracao' => 'nullable|string|max:500'
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
        $orcamento->load(['centroCusto', 'user']);
        
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
        $validated = $request->validate([
            'data_solicitacao' => 'required|date',
            'centro_custo_id' => 'required|exists:centros_custo,id',
            'nome_rota' => 'required|string|max:255',
            'id_logcare' => 'nullable|string|max:255',
            'cliente_omie_id' => 'nullable|string|max:255',
            'cliente_nome' => 'nullable|string|max:255',
            'horario' => 'nullable|date_format:H:i',
            'frequencia_atendimento' => 'nullable|array',
            'frequencia_atendimento.*' => 'string|in:segunda,terca,quarta,quinta,sexta,sabado,domingo',
            'tipo_orcamento' => 'required|in:prestador,aumento_km,proprio_nova_rota',
            'status' => 'required|in:rascunho,enviado,aprovado,rejeitado,cancelado',
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
            'grupo_imposto_id' => 'nullable|exists:grupos_impostos,id'
        ]);

        try {
            DB::beginTransaction();
            
            // Filtrar campos específicos baseado no tipo de orçamento
            $orcamentoData = $this->filtrarCamposOrcamento($validated);
            
            $orcamento->update($orcamentoData);

            // Atualizar ou criar registro específico baseado no tipo de orçamento
            $this->atualizarRegistroEspecifico($orcamento, $validated);
            
            DB::commit();

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
            'status' => 'required|in:rascunho,enviado,aprovado,rejeitado,cancelado'
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
     * Filtrar campos do orçamento baseado no tipo
     */
    private function filtrarCamposOrcamento(array $validated)
    {
        // Campos que sempre devem ser incluídos
        $camposPermitidos = [
            'data_solicitacao',
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
        
        // Calcular percentual total dos impostos do grupo
        $percentualTotal = $grupoImposto->impostos->sum('percentual');
        
        return response()->json([
            'percentual' => $percentualTotal,
            'nome' => $grupoImposto->nome,
            'impostos' => $grupoImposto->impostos->map(function($imposto) {
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
            'lucro_percentual' => $validated['percentual_lucro'] ?? 0,
            'impostos_percentual' => $validated['percentual_impostos'] ?? 0,
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
            'nova_origem' => $validated['nova_origem'],
            'novo_destino' => $validated['novo_destino'],
            'km_nova_rota' => $validated['km_nova_rota'] ?? 0,
            'valor_km_nova_rota' => $validated['valor_km_nova_rota'] ?? 0,
            'motivo_alteracao' => $validated['motivo_alteracao'] ?? null,
            'observacoes' => $validated['observacoes'] ?? null
        ];

        $orcamentoNovaRota = OrcamentoProprioNovaRota::create($novaRotaData);
        
        // Atualizar cálculos automáticos
        $orcamentoNovaRota->calcularValorTotal();
        $orcamentoNovaRota->save();
        
        // Atualizar valor total do orçamento principal
         $orcamento->update(['valor_total' => $orcamentoNovaRota->valor_total_nova_rota]);
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
         $aumentoKmData = [
             'km_dia' => $validated['km_dia'] ?? 0,
             'qtd_dias' => $validated['qtd_dias_aumento'] ?? 1,
             'combustivel_km_litro' => $validated['combustivel_km_litro'] ?? 0,
             'valor_combustivel' => $validated['valor_combustivel'] ?? 0,
             'hora_extra' => $validated['hora_extra'] ?? 0,
             'lucro_percentual' => $validated['percentual_lucro'] ?? 0,
             'impostos_percentual' => $validated['percentual_impostos'] ?? 0,
             'observacoes' => $validated['observacoes'] ?? null
         ];

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
             'nova_origem' => $validated['nova_origem'],
             'novo_destino' => $validated['novo_destino'],
             'km_nova_rota' => $validated['km_nova_rota'] ?? 0,
             'valor_km_nova_rota' => $validated['valor_km_nova_rota'] ?? 0,
             'motivo_alteracao' => $validated['motivo_alteracao'] ?? null,
             'observacoes' => $validated['observacoes'] ?? null
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
         
         // Atualizar cálculos automáticos
         $orcamentoNovaRota->calcularValorTotal();
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
