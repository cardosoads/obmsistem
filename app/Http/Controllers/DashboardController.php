<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Models\RecursoHumano;
use App\Models\Frota;
use App\Models\TipoVeiculo;
use App\Models\Combustivel;
use App\Models\Base;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard principal
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Dados básicos para o dashboard
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::whereNotNull('email_verified_at')->count(),
            'recent_logins' => User::whereDate('updated_at', today())->count(),
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
            'custo_total_funcionarios' => RecursoHumano::where('active', true)->sum('custo_total_mao_obra'),

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
            'preco_medio_combustivel' => Combustivel::where('active', true)->avg('preco_litro'),
            'combustiveis_por_base' => Base::withCount(['combustiveis' => function($query) {
                $query->where('active', true);
            }])->get()->pluck('combustiveis_count', 'nome')->toArray(),

            // Bases
            'total_bases' => Base::count(),
            'bases_ativas' => Base::where('active', true)->count()
        ];
        
        return view('admin.dashboard', compact('user', 'stats', 'modulesStats'));
    }

    /**
     * Exibe a página de gerenciamento de usuários
     */
    public function users(): View
    {
        $users = User::latest()->paginate(15);
        
        return view('admin.users', compact('users'));
    }

    /**
     * Exibe a página de configurações
     */
    public function settings(): View
    {
        $user = Auth::user();
        
        return view('admin.settings', compact('user'));
    }

    /**
     * Exibe a página de relatórios
     */
    public function reports(): View
    {
        // Dados simulados para relatórios
        $reports = [
            'daily_access' => [
                'today' => User::whereDate('updated_at', today())->count(),
                'yesterday' => User::whereDate('updated_at', today()->subDay())->count(),
                'week' => User::whereDate('updated_at', '>=', today()->subWeek())->count(),
            ],
            'user_growth' => [
                'this_month' => User::whereMonth('created_at', now()->month)->count(),
                'last_month' => User::whereMonth('created_at', now()->subMonth()->month)->count(),
            ]
        ];
        
        return view('admin.reports', compact('reports'));
    }
}