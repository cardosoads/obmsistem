<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

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
        
        return view('admin.dashboard', compact('user', 'stats'));
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