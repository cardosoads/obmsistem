<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RecursoHumano;
use App\Models\Frota;
use App\Models\TipoVeiculo;
use App\Models\Combustivel;
use App\Models\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard administrativo
     */
    public function index()
    {
        // Estatísticas dos usuários
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('active', true)->count(),
            'recent_logins' => User::whereDate('last_login_at', today())->count(),
            'system_status' => 'Operacional'
        ];

        // Estatísticas dos novos módulos
        $modulesStats = [
            // Recursos Humanos
            'total_funcionarios' => RecursoHumano::count(),
            'funcionarios_ativos' => RecursoHumano::where('active', true)->count(),
            'funcionarios_por_cargo' => RecursoHumano::select('cargo', DB::raw('count(*) as total'))
                ->where('active', true)
                ->groupBy('cargo')
                ->pluck('total', 'cargo')
                ->toArray(),
            'custo_total_funcionarios' => RecursoHumano::where('active', true)->sum('custo_total'),

            // Frotas
            'total_veiculos' => Frota::count(),
            'veiculos_ativos' => Frota::where('active', true)->count(),
            'custo_total_frotas' => Frota::where('active', true)->sum('custo_total'),
            'valor_medio_veiculo' => Frota::where('active', true)->avg('custo_total'),

            // Tipos de Veículos
            'total_tipos_veiculos' => TipoVeiculo::count(),
            'tipos_veiculos_ativos' => TipoVeiculo::where('active', true)->count(),
            'consumo_medio' => TipoVeiculo::where('active', true)->avg('consumo_km_litro'),

            // Combustíveis
            'total_combustiveis' => Combustivel::count(),
            'combustiveis_ativos' => Combustivel::where('active', true)->count(),
            'preco_medio_combustivel' => Combustivel::where('active', true)->avg('preco'),
            'combustiveis_por_base' => Base::withCount(['combustiveis' => function($query) {
                $query->where('active', true);
            }])->get()->pluck('combustiveis_count', 'nome')->toArray(),

            // Bases
            'total_bases' => Base::count(),
            'bases_ativas' => Base::where('active', true)->count()
        ];
        
        // Usuário atual
        $user = auth()->user();
        
        return view('admin.dashboard', compact('stats', 'modulesStats', 'user'));
    }
    
    /**
     * Retorna dados para gráficos via AJAX
     */
    public function chartData(Request $request)
    {
        $type = $request->get('type');
        
        switch ($type) {
            default:
                return response()->json(['error' => 'Tipo de gráfico não encontrado'], 404);
        }
    }
}
