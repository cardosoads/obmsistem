<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Models\Fornecedor; // Removido: dados vêm da API OMIE
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
        // $totalClientes = Cliente::count(); // Removido: agora utilizamos API OMIE
        $totalClientes = 0; // Placeholder - pode ser implementado via API OMIE se necessário
        // $totalFornecedores = Fornecedor::count(); // Removido: dados vêm da API OMIE
        $totalFornecedores = 0; // Placeholder - pode ser implementado via API OMIE se necessário
        $totalUsuarios = User::count();
        
        return view('admin.dashboard', compact(
            'totalClientes',
            'totalFornecedores',
            'totalUsuarios'
        ));
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
