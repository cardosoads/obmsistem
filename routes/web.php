<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\OrcamentoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\OmieController;
use App\Http\Controllers\Admin\OmiePessoaController;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Admin\MarcaController;
use App\Http\Controllers\Admin\CentroCustoController;
use App\Http\Controllers\Admin\ImpostoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;

// Rota de login (página inicial)
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.form');

// Rotas de autenticação
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/check-auth', [AuthController::class, 'checkAuth'])->name('auth.check');

// Rotas protegidas do dashboard administrativo
Route::middleware(['admin.auth'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [DashboardController::class, 'users'])->name('admin.users');
    Route::get('/admin/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::get('/admin/reports', [DashboardController::class, 'reports'])->name('admin.reports');
    
    // Orçamentos
    Route::resource('admin/orcamentos', OrcamentoController::class, [
        'names' => [
            'index' => 'admin.orcamentos.index',
            'create' => 'admin.orcamentos.create',
            'store' => 'admin.orcamentos.store',
            'show' => 'admin.orcamentos.show',
            'edit' => 'admin.orcamentos.edit',
            'update' => 'admin.orcamentos.update',
            'destroy' => 'admin.orcamentos.destroy'
        ]
    ]);
    Route::post('/admin/orcamentos/{orcamento}/duplicate', [OrcamentoController::class, 'duplicate'])->name('admin.orcamentos.duplicate');
    Route::patch('/admin/orcamentos/{orcamento}/status', [OrcamentoController::class, 'changeStatus'])->name('admin.orcamentos.change-status');
    
    // Usuários
    Route::resource('admin/users', UserController::class, [
        'names' => [
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'show' => 'admin.users.show',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy'
        ]
    ]);
    Route::patch('/admin/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    Route::patch('/admin/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');
    
    // Clientes - Removido: agora utilizamos integração com API OMIE
    // Route::resource('admin/clientes', ClienteController::class, [
    //     'names' => [
    //         'index' => 'admin.clientes.index',
    //         'create' => 'admin.clientes.create',
    //         'store' => 'admin.clientes.store',
    //         'show' => 'admin.clientes.show',
    //         'edit' => 'admin.clientes.edit',
    //         'update' => 'admin.clientes.update',
    //         'destroy' => 'admin.clientes.destroy'
    //     ]
    // ]);
    // Route::patch('/admin/clientes/{cliente}/status', [ClienteController::class, 'toggleStatus'])->name('admin.clientes.toggle-status');
    
    // Fornecedores - Removido: agora utilizamos integração com API OMIE
    // Route::resource('admin/fornecedores', FornecedorController::class, [
    //     'names' => [
    //         'index' => 'admin.fornecedores.index',
    //         'create' => 'admin.fornecedores.create',
    //         'store' => 'admin.fornecedores.store',
    //         'show' => 'admin.fornecedores.show',
    //         'edit' => 'admin.fornecedores.edit',
    //         'update' => 'admin.fornecedores.update',
    //         'destroy' => 'admin.fornecedores.destroy'
    //     ]
    // ]);
    // Route::patch('/admin/fornecedores/{fornecedor}/status', [FornecedorController::class, 'toggleStatus'])->name('admin.fornecedores.toggle-status');
    
    // Bases
    Route::resource('admin/bases', BaseController::class, [
        'names' => [
            'index' => 'admin.bases.index',
            'create' => 'admin.bases.create',
            'store' => 'admin.bases.store',
            'show' => 'admin.bases.show',
            'edit' => 'admin.bases.edit',
            'update' => 'admin.bases.update',
            'destroy' => 'admin.bases.destroy'
        ]
    ]);
    Route::patch('/admin/bases/{base}/status', [BaseController::class, 'toggleStatus'])->name('admin.bases.toggle-status');
    
    // Marcas
    Route::resource('admin/marcas', MarcaController::class, [
        'names' => [
            'index' => 'admin.marcas.index',
            'create' => 'admin.marcas.create',
            'store' => 'admin.marcas.store',
            'show' => 'admin.marcas.show',
            'edit' => 'admin.marcas.edit',
            'update' => 'admin.marcas.update',
            'destroy' => 'admin.marcas.destroy'
        ]
    ]);
    Route::patch('/admin/marcas/{marca}/status', [MarcaController::class, 'toggleStatus'])->name('admin.marcas.toggle-status');
    
    // Centros de Custo
    Route::resource('admin/centros-custo', CentroCustoController::class, [
        'names' => [
            'index' => 'admin.centros-custo.index',
            'create' => 'admin.centros-custo.create',
            'store' => 'admin.centros-custo.store',
            'show' => 'admin.centros-custo.show',
            'edit' => 'admin.centros-custo.edit',
            'update' => 'admin.centros-custo.update',
            'destroy' => 'admin.centros-custo.destroy'
        ]
    ]);
    Route::patch('/admin/centros-custo/{centroCusto}/status', [CentroCustoController::class, 'toggleStatus'])->name('admin.centros-custo.toggle-status');
    Route::get('/admin/centros-custo/base/{base}/data', [CentroCustoController::class, 'getBaseData'])->name('admin.centros-custo.base-data');
    
    // Impostos
    Route::resource('admin/impostos', ImpostoController::class, [
        'names' => [
            'index' => 'admin.impostos.index',
            'create' => 'admin.impostos.create',
            'store' => 'admin.impostos.store',
            'show' => 'admin.impostos.show',
            'edit' => 'admin.impostos.edit',
            'update' => 'admin.impostos.update',
            'destroy' => 'admin.impostos.destroy'
        ]
    ]);
    Route::patch('/admin/impostos/{imposto}/status', [ImpostoController::class, 'toggleStatus'])->name('admin.impostos.toggle-status');
    
    // Configurações
    Route::put('/admin/settings/omie', [SettingsController::class, 'updateOmie'])->name('admin.settings.omie.update');
    Route::post('/admin/settings/omie/test', [SettingsController::class, 'testOmieConnection'])->name('admin.settings.omie.test');
    Route::post('/admin/settings/system', [SettingsController::class, 'updateSystem'])->name('admin.settings.system.update');
    
    // Omie - Clientes e Fornecedores
    Route::get('/admin/omie/clientes', [OmieController::class, 'clientes'])->name('admin.omie.clientes');
    Route::get('/admin/omie/fornecedores', [OmieController::class, 'fornecedores'])->name('admin.omie.fornecedores');
    Route::get('/admin/omie/clientes/{omieId}', [OmieController::class, 'consultarCliente'])->name('admin.omie.clientes.show');
    Route::get('/admin/omie/fornecedores/{omieId}', [OmieController::class, 'consultarFornecedor'])->name('admin.omie.fornecedores.show');
    Route::get('/admin/omie/pessoas', [OmiePessoaController::class, 'index'])->name('admin.omie.pessoas');
    Route::get('/admin/omie/pessoas/{omieId}', [OmiePessoaController::class, 'show'])->name('admin.omie.pessoas.show');
    

});

// API OMIE - Clientes e Fornecedores (sem autenticação para uso via AJAX)
Route::prefix('api/omie')->name('api.omie.')->group(function () {
    Route::get('/clientes/search', [\App\Http\Controllers\Api\OmieClienteController::class, 'search'])->name('clientes.search');
    Route::get('/clientes/{omieId}', [\App\Http\Controllers\Api\OmieClienteController::class, 'show'])->name('clientes.show');
    Route::post('/clientes/clear-cache', [\App\Http\Controllers\Api\OmieClienteController::class, 'clearCache'])->name('clientes.clear-cache');
    
    Route::get('/fornecedores/{omieId}', [\App\Http\Controllers\Api\OmieFornecedorController::class, 'show'])->name('fornecedores.show');
});

// Rota de fallback para usuários autenticados que acessam a raiz
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['admin.auth'])->name('dashboard');
