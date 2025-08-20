<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Models\Fornecedor; // Removido: dados vêm da API OMIE
use App\Models\Orcamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard administrativo
     */
    public function index()
    {
        // Estatísticas gerais
        $totalOrcamentos = Orcamento::count();
        $orcamentosAprovados = Orcamento::aprovado()->count();
        $orcamentosEnviados = Orcamento::enviado()->count();
        $orcamentosRascunho = Orcamento::rascunho()->count();
        
        // $totalClientes = Cliente::count(); // Removido: agora utilizamos API OMIE
        $totalClientes = 0; // Placeholder - pode ser implementado via API OMIE se necessário
        // $totalFornecedores = Fornecedor::count(); // Removido: dados vêm da API OMIE
        $totalFornecedores = 0; // Placeholder - pode ser implementado via API OMIE se necessário
        $totalUsuarios = User::count();
        
        // Valor total dos orçamentos aprovados
        $valorTotalAprovados = Orcamento::aprovado()->sum('valor_final');
        
        // Orçamentos por mês (últimos 6 meses)
        $orcamentosPorMes = Orcamento::select(
            DB::raw('YEAR(created_at) as ano'),
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(valor_final) as valor_total')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('ano', 'mes')
        ->orderBy('ano', 'desc')
        ->orderBy('mes', 'desc')
        ->get();
        
        // Orçamentos por status
        $orcamentosPorStatus = Orcamento::select(
            'status',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('status')
        ->get()
        ->pluck('total', 'status');
        
        // Últimos orçamentos criados
        $ultimosOrcamentos = Orcamento::with(['user'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Orçamentos próximos do vencimento (próximos 7 dias)
        $orcamentosVencimento = Orcamento::where('data_validade', '>=', now())
            ->where('data_validade', '<=', now()->addDays(7))
            ->where('status', '!=', 'aprovado')
            ->orderBy('data_validade')
            ->get();
        
        return view('admin.dashboard', compact(
            'totalOrcamentos',
            'orcamentosAprovados',
            'orcamentosEnviados',
            'orcamentosRascunho',
            'totalClientes',
            'totalFornecedores',
            'totalUsuarios',
            'valorTotalAprovados',
            'orcamentosPorMes',
            'orcamentosPorStatus',
            'ultimosOrcamentos',
            'orcamentosVencimento'
        ));
    }
    
    /**
     * Retorna dados para gráficos via AJAX
     */
    public function chartData(Request $request)
    {
        $type = $request->get('type');
        
        switch ($type) {
            case 'orcamentos_mes':
                return $this->getOrcamentosPorMes();
            case 'orcamentos_status':
                return $this->getOrcamentosPorStatus();
            case 'valor_mes':
                return $this->getValorPorMes();
            default:
                return response()->json(['error' => 'Tipo de gráfico não encontrado'], 404);
        }
    }
    
    /**
     * Dados de orçamentos por mês
     */
    private function getOrcamentosPorMes()
    {
        $dados = Orcamento::select(
            DB::raw('YEAR(created_at) as ano'),
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('ano', 'mes')
        ->orderBy('ano')
        ->orderBy('mes')
        ->get();
        
        return response()->json($dados);
    }
    
    /**
     * Dados de orçamentos por status
     */
    private function getOrcamentosPorStatus()
    {
        $dados = Orcamento::select(
            'status',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('status')
        ->get();
        
        return response()->json($dados);
    }
    
    /**
     * Dados de valor por mês
     */
    private function getValorPorMes()
    {
        $dados = Orcamento::select(
            DB::raw('YEAR(created_at) as ano'),
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('SUM(valor_final) as valor_total')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->where('status', 'aprovado')
        ->groupBy('ano', 'mes')
        ->orderBy('ano')
        ->orderBy('mes')
        ->get();
        
        return response()->json($dados);
    }
}
