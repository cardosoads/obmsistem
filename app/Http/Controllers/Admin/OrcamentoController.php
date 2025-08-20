<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Base;
use App\Models\CentroCusto;
// use App\Models\Fornecedor; // Removido: dados vêm da API OMIE
use App\Models\Marca;
use App\Models\Orcamento;
use App\Models\OrcamentoHistorico;
use App\Models\OrcamentoPrestador;
use App\Models\OrcamentoAumentoKm;
use App\Models\OrcamentoProprioNovaRota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrcamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Orcamento::with(['baseOrigem', 'baseDestino', 'centroCusto', 'user']);
        
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
                $q->where('numero_orcamento', 'like', "%{$search}%")
                  ->orWhere('cliente_nome', 'like', "%{$search}%")
                  ->orWhere('nome_rota', 'like', "%{$search}%");
            });
        }
        
        $orcamentos = $query->latest()->paginate(15);
        $statusOptions = [
            'rascunho' => 'Rascunho',
            'enviado' => 'Enviado',
            'aprovado' => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            'cancelado' => 'Cancelado',
            'expirado' => 'Expirado'
        ];
        
        return view('admin.orcamentos.index', compact('orcamentos', 'statusOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bases = Base::active()->orderBy('name')->get();
        $centrosCusto = CentroCusto::active()->orderBy('name')->get();
        // $fornecedores = Fornecedor::active()->orderBy('name')->get(); // Removido: dados vêm da API OMIE
        $marcas = Marca::active()->orderBy('name')->get();
        
        return view('admin.orcamentos.create', compact(
            'bases', 'centrosCusto', 'marcas'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'data_solicitacao' => 'required|date',
            'centro_custo_id' => 'required|exists:centros_custo,id',
            'nome_rota' => 'required|string|max:255',
            'id_logcare' => 'nullable|string|max:100',
            'cliente_omie_id' => 'nullable|integer',
            'cliente_nome' => 'nullable|string|max:255',
            'horario' => 'nullable|string|max:50',
            'frequencia_atendimento' => 'nullable|string|max:100',
            'tipo_orcamento' => ['required', Rule::in(['prestador', 'aumento_km', 'proprio_nova_rota'])]
        ]);
        
        DB::beginTransaction();
        
        try {
            // Gerar número do orçamento
            $numeroOrcamento = $this->gerarNumeroOrcamento();
            
            $validated['numero_orcamento'] = $numeroOrcamento;
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'rascunho';
            $validated['data_orcamento'] = now()->format('Y-m-d');
            $validated['valor_total'] = 0;
            $validated['valor_impostos'] = 0;
            $validated['valor_final'] = 0;
            
            $orcamento = Orcamento::create($validated);
            
            // Criar registro específico baseado no tipo de orçamento
            $this->criarRegistroEspecifico($orcamento, $request, $validated['tipo_orcamento']);
            
            // Registrar no histórico
            OrcamentoHistorico::create([
                'orcamento_id' => $orcamento->id,
                'user_id' => Auth::id(),
                'acao' => 'criacao',
                'status_novo' => $orcamento->status,
                'data_acao' => now(),
                'observacoes' => 'Orçamento criado'
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.orcamentos.show', $orcamento)
                           ->with('success', 'Orçamento criado com sucesso!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Erro ao criar orçamento: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Orcamento $orcamento)
    {
        $orcamento->load([
            'baseOrigem', 'baseDestino', 'centroCusto', 'user',
            'orcamentosPrestador.marca',
            'aumentosKm', 'novasRotasProprias', 'historico.user'
        ]);
        
        return view('admin.orcamentos.show', compact('orcamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orcamento $orcamento)
    {
        // Só permite editar se estiver em rascunho
        if ($orcamento->status !== 'rascunho') {
            return redirect()->route('admin.orcamentos.show', $orcamento)
                           ->with('error', 'Apenas orçamentos em rascunho podem ser editados.');
        }
        
        // $clientes = Cliente::active()->orderBy('name')->get(); // Removido: agora utilizamos API OMIE
        $bases = Base::active()->orderBy('name')->get();
        $centrosCusto = CentroCusto::active()->orderBy('name')->get();
        // $fornecedores = Fornecedor::active()->orderBy('name')->get(); // Removido: dados vêm da API OMIE
        $marcas = Marca::active()->orderBy('name')->get();
        
        return view('admin.orcamentos.edit', compact(
            'orcamento', 'bases', 'centrosCusto', 'marcas'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orcamento $orcamento)
    {
        // Só permite editar se estiver em rascunho
        if ($orcamento->status !== 'rascunho') {
            return redirect()->route('admin.orcamentos.show', $orcamento)
                           ->with('error', 'Apenas orçamentos em rascunho podem ser editados.');
        }
        
        $validated = $request->validate([
            'data_solicitacao' => 'required|date',
            'base_origem_id' => 'required|exists:bases,id',
            'base_destino_id' => 'required|exists:bases,id',
            'centro_custo_id' => 'required|exists:centros_custo,id',
            'data_orcamento' => 'required|date',
            'data_validade' => 'required|date|after:data_orcamento',
            'evento' => 'required|string|max:255',
            'nome_rota' => 'required|string|max:255',
            'id_logcare' => 'nullable|string|max:100',
            'cliente_dasa' => 'nullable|string|max:255',
            'horario' => 'nullable|string|max:50',
            'frequencia_atendimento' => 'nullable|string|max:100',
            'tipo_orcamento' => ['required', Rule::in(['prestador', 'aumento_km', 'proprio_nova_rota'])],
            'valor_total' => 'required|numeric|min:0',
            'valor_impostos' => 'nullable|numeric|min:0',
            'observacoes' => 'nullable|string|max:1000'
        ]);
        
        DB::beginTransaction();
        
        try {
            $dadosAnteriores = $orcamento->toArray();
            
            $validated['valor_final'] = $validated['valor_total'] + ($validated['valor_impostos'] ?? 0);
            
            $orcamento->update($validated);
            
            // Registrar no histórico
            OrcamentoHistorico::create([
                'orcamento_id' => $orcamento->id,
                'user_id' => Auth::id(),
                'acao' => 'edicao',
                'dados_alterados' => array_diff_assoc($validated, $dadosAnteriores),
                'data_acao' => now(),
                'observacoes' => 'Orçamento atualizado'
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.orcamentos.show', $orcamento)
                           ->with('success', 'Orçamento atualizado com sucesso!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar orçamento: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orcamento $orcamento)
    {
        // Só permite excluir se estiver em rascunho
        if ($orcamento->status !== 'rascunho') {
            return response()->json([
                'success' => false,
                'message' => 'Apenas orçamentos em rascunho podem ser excluídos.'
            ], 422);
        }
        
        try {
            $orcamento->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Orçamento excluído com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir orçamento: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Altera o status do orçamento
     */
    public function changeStatus(Request $request, Orcamento $orcamento)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['rascunho', 'enviado', 'aprovado', 'rejeitado', 'cancelado', 'expirado'])],
            'observacoes' => 'nullable|string|max:500'
        ]);
        
        DB::beginTransaction();
        
        try {
            $statusAnterior = $orcamento->status;
            
            $orcamento->update(['status' => $validated['status']]);
            
            // Registrar no histórico
            OrcamentoHistorico::create([
                'orcamento_id' => $orcamento->id,
                'user_id' => Auth::id(),
                'acao' => match($validated['status']) {
                    'enviado' => 'envio',
                    'aprovado' => 'aprovacao',
                    'rejeitado' => 'rejeicao',
                    'cancelado' => 'cancelamento',
                    'expirado' => 'expiracao',
                    default => 'edicao'
                },
                'status_anterior' => $statusAnterior,
                'status_novo' => $validated['status'],
                'data_acao' => now(),
                'observacoes' => $validated['observacoes'] ?? "Status alterado para {$validated['status']}"
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Status alterado com sucesso!',
                'new_status' => $validated['status']
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Cria registro específico baseado no tipo de orçamento
     */
    private function criarRegistroEspecifico(Orcamento $orcamento, Request $request, string $tipo)
    {
        switch ($tipo) {
            case 'prestador':
                $this->criarOrcamentoPrestador($orcamento, $request);
                break;
            case 'aumento_km':
                $this->criarOrcamentoAumentoKm($orcamento, $request);
                break;
            case 'proprio_nova_rota':
                $this->criarOrcamentoProprioNovaRota($orcamento, $request);
                break;
        }
    }
    
    /**
     * Cria registro de orçamento prestador
     */
    private function criarOrcamentoPrestador(Orcamento $orcamento, Request $request)
    {
        $dados = $request->validate([
            'fornecedor_omie_id' => 'required|integer',
            'fornecedor_nome' => 'required|string|max:255',
            'marca_id' => 'required|exists:marcas,id',
            'valor_referencia' => 'required|numeric|min:0',
            'qtd_dias' => 'required|integer|min:1',
            'custo_fornecedor' => 'required|numeric|min:0',
            'lucro_percentual' => 'required|numeric|min:0|max:100',
            'valor_lucro' => 'required|numeric|min:0',
            'impostos_percentual' => 'required|numeric|min:0|max:100',
            'valor_impostos' => 'required|numeric|min:0',
            'observacoes' => 'nullable|string|max:1000'
        ]);
        
        $dados['orcamento_id'] = $orcamento->id;
        
        OrcamentoPrestador::create($dados);
    }
    
    /**
     * Cria registro de orçamento aumento km
     */
    private function criarOrcamentoAumentoKm(Orcamento $orcamento, Request $request)
    {
        $dados = $request->validate([
            'km_dia' => 'required|numeric|min:0',
            'qtd_dias' => 'required|integer|min:1',
            'km_total_mes' => 'required|numeric|min:0',
            'combustivel_km_litro' => 'required|numeric|min:0',
            'total_combustivel' => 'required|numeric|min:0',
            'valor_combustivel' => 'required|numeric|min:0',
            'hora_extra' => 'required|numeric|min:0',
            'custo_total_combustivel_he' => 'required|numeric|min:0',
            'lucro_percentual' => 'required|numeric|min:0|max:100',
            'valor_lucro' => 'required|numeric|min:0',
            'impostos_percentual' => 'required|numeric|min:0|max:100',
            'valor_impostos' => 'required|numeric|min:0',
            'valor_total' => 'required|numeric|min:0',
            'observacoes' => 'nullable|string|max:1000'
        ]);
        
        $dados['orcamento_id'] = $orcamento->id;
        
        OrcamentoAumentoKm::create($dados);
    }
    
    /**
     * Cria registro de orçamento próprio nova rota
     */
    private function criarOrcamentoProprioNovaRota(Orcamento $orcamento, Request $request)
    {
        $dados = $request->validate([
            'observacoes' => 'nullable|string|max:1000'
        ]);
        
        $dados['orcamento_id'] = $orcamento->id;
        
        OrcamentoProprioNovaRota::create($dados);
    }
    
    /**
     * Gera um número único para o orçamento
     */
    private function gerarNumeroOrcamento(): string
    {
        $ano = date('Y');
        $mes = date('m');
        
        // Busca o último número do mês
        $ultimoOrcamento = Orcamento::where('numero_orcamento', 'like', "ORC-{$ano}{$mes}-%")
                                  ->orderBy('numero_orcamento', 'desc')
                                  ->first();
        
        if ($ultimoOrcamento) {
            $ultimoNumero = (int) substr($ultimoOrcamento->numero_orcamento, -4);
            $novoNumero = $ultimoNumero + 1;
        } else {
            $novoNumero = 1;
        }
        
        return "ORC-{$ano}{$mes}-" . str_pad($novoNumero, 4, '0', STR_PAD_LEFT);
    }
}
