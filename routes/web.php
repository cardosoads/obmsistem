<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\OmieController;
use App\Http\Controllers\Admin\OmiePessoaController;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Admin\MarcaController;
use App\Http\Controllers\Admin\CentroCustoController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\OrcamentoController;
use App\Http\Controllers\Admin\ImpostoController;
use App\Http\Controllers\Admin\GrupoImpostoController;
use App\Http\Controllers\RecursoHumanoController;
use App\Http\Controllers\TipoVeiculoController;
use App\Http\Controllers\FrotaController;
use App\Http\Controllers\CombustivelController;

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
    
    // Página de teste com select
    Route::get('/admin/teste-select', function () {
        return view('admin.teste-select');
    })->name('admin.teste-select');
    

    

    
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
        ],
        'parameters' => [
            'bases' => 'base'
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
        ],
        'parameters' => [
            'centros-custo' => 'centroCusto'
        ]
    ]);
    Route::patch('/admin/centros-custo/{centroCusto}/toggle-status', [CentroCustoController::class, 'toggleStatus'])->name('admin.centros-custo.toggle-status');
    Route::get('/admin/centros-custo/base/{base}/data', [CentroCustoController::class, 'getBaseData'])->name('admin.centros-custo.base-data');
    Route::post('/admin/centros-custo/sincronizar', [CentroCustoController::class, 'sincronizar'])->name('admin.centros-custo.sincronizar');
    
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
    Route::post('/admin/impostos/calcular', [ImpostoController::class, 'calcular'])->name('admin.impostos.calcular');
    Route::get('/admin/impostos/search', [ImpostoController::class, 'search'])->name('admin.impostos.search');
    
    // Grupos de Impostos
    Route::resource('admin/grupos-impostos', GrupoImpostoController::class, [
        'names' => [
            'index' => 'admin.grupos-impostos.index',
            'create' => 'admin.grupos-impostos.create',
            'store' => 'admin.grupos-impostos.store',
            'show' => 'admin.grupos-impostos.show',
            'edit' => 'admin.grupos-impostos.edit',
            'update' => 'admin.grupos-impostos.update',
            'destroy' => 'admin.grupos-impostos.destroy'
        ]
    ]);
    Route::patch('/admin/grupos-impostos/{gruposImposto}/status', [GrupoImpostoController::class, 'toggleStatus'])->name('admin.grupos-impostos.toggle-status');
    Route::post('/admin/grupos-impostos/calcular', [GrupoImpostoController::class, 'calcular'])->name('admin.grupos-impostos.calcular');
    Route::get('/admin/grupos-impostos/{gruposImposto}/breakdown', [GrupoImpostoController::class, 'breakdown'])->name('admin.grupos-impostos.breakdown');
    Route::get('/admin/grupos-impostos/search', [GrupoImpostoController::class, 'search'])->name('admin.grupos-impostos.search');
    Route::get('/admin/grupos-impostos/impostos-disponiveis', [GrupoImpostoController::class, 'impostosDisponiveis'])->name('admin.grupos-impostos.impostos-disponiveis');

    // Recursos Humanos
    Route::resource('admin/recursos-humanos', RecursoHumanoController::class, [
        'names' => [
            'index' => 'admin.recursos-humanos.index',
            'create' => 'admin.recursos-humanos.create',
            'store' => 'admin.recursos-humanos.store',
            'show' => 'admin.recursos-humanos.show',
            'edit' => 'admin.recursos-humanos.edit',
            'update' => 'admin.recursos-humanos.update',
            'destroy' => 'admin.recursos-humanos.destroy'
        ],
        'parameters' => [
            'recursos-humanos' => 'recursoHumano'
        ]
    ]);
    Route::patch('/admin/recursos-humanos/{recursoHumano}/toggle-status', [RecursoHumanoController::class, 'toggleStatus'])->name('admin.recursos-humanos.toggle-status');
    Route::post('/admin/recursos-humanos/{recursoHumano}/recalcular', [RecursoHumanoController::class, 'recalcular'])->name('admin.recursos-humanos.recalcular');
    Route::get('/admin/recursos-humanos/relatorio', [RecursoHumanoController::class, 'relatorio'])->name('admin.recursos-humanos.relatorio');

    // Módulo de Frotas e Veículos
    
    // Tipos de Veículos
    Route::resource('admin/tipos-veiculos', TipoVeiculoController::class, [
        'names' => [
            'index' => 'admin.tipos-veiculos.index',
            'create' => 'admin.tipos-veiculos.create',
            'store' => 'admin.tipos-veiculos.store',
            'show' => 'admin.tipos-veiculos.show',
            'edit' => 'admin.tipos-veiculos.edit',
            'update' => 'admin.tipos-veiculos.update',
            'destroy' => 'admin.tipos-veiculos.destroy'
        ],
        'parameters' => [
            'tipos-veiculos' => 'tipoVeiculo'
        ]
    ]);
    Route::patch('/admin/tipos-veiculos/{tipoVeiculo}/toggle-status', [TipoVeiculoController::class, 'toggleStatus'])->name('admin.tipos-veiculos.toggle-status');
    
    // Frotas
    Route::resource('admin/frotas', FrotaController::class, [
        'names' => [
            'index' => 'admin.frotas.index',
            'create' => 'admin.frotas.create',
            'store' => 'admin.frotas.store',
            'show' => 'admin.frotas.show',
            'edit' => 'admin.frotas.edit',
            'update' => 'admin.frotas.update',
            'destroy' => 'admin.frotas.destroy'
        ]
    ]);
    Route::patch('/admin/frotas/{frota}/toggle-status', [FrotaController::class, 'toggleStatus'])->name('admin.frotas.toggle-status');
    Route::post('/admin/frotas/{frota}/recalcular-custo', [FrotaController::class, 'recalcularCusto'])->name('admin.frotas.recalcular-custo');
    
    // Combustíveis
    Route::resource('admin/combustiveis', CombustivelController::class, [
        'names' => [
            'index' => 'admin.combustiveis.index',
            'create' => 'admin.combustiveis.create',
            'store' => 'admin.combustiveis.store',
            'show' => 'admin.combustiveis.show',
            'edit' => 'admin.combustiveis.edit',
            'update' => 'admin.combustiveis.update',
            'destroy' => 'admin.combustiveis.destroy'
        ],
        'parameters' => [
            'combustiveis' => 'combustivel'
        ]
    ]);
    Route::patch('/admin/combustiveis/{combustivel}/toggle-status', [CombustivelController::class, 'toggleStatus'])->name('admin.combustiveis.toggle-status');
    Route::get('/admin/combustiveis/base/{base}', [CombustivelController::class, 'getByBase'])->name('admin.combustiveis.by-base');

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
    ]);    Route::patch('/admin/orcamentos/{orcamento}/status', [OrcamentoController::class, 'updateStatus'])->name('admin.orcamentos.update-status');    Route::get('/admin/orcamentos/{orcamento}/pdf', [OrcamentoController::class, 'gerarPdf'])->name('admin.orcamentos.pdf');    Route::post('/admin/orcamentos/buscar-percentual-grupo-imposto', [OrcamentoController::class, 'buscarPercentualGrupoImposto'])->name('admin.orcamentos.buscar-percentual-grupo-imposto');
    
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

// API de Localidades IBGE (sem autenticação para uso via AJAX)
Route::prefix('admin/localidades')->name('admin.localidades.')->group(function () {
    Route::get('/estados', [\App\Http\Controllers\Admin\LocalidadeController::class, 'estados'])->name('estados');
    Route::get('/municipios/{uf}', [\App\Http\Controllers\Admin\LocalidadeController::class, 'municipiosPorUf'])->name('municipios.uf');
    Route::get('/municipio/{id}', [\App\Http\Controllers\Admin\LocalidadeController::class, 'municipio'])->name('municipio');
    Route::get('/regioes', [\App\Http\Controllers\Admin\LocalidadeController::class, 'regioes'])->name('regioes');
    Route::post('/limpar-cache', [\App\Http\Controllers\Admin\LocalidadeController::class, 'limparCache'])->name('limpar-cache');
});

// API OMIE - Clientes e Fornecedores (sem autenticação para uso via AJAX)
Route::prefix('api/omie')->name('api.omie.')->group(function () {
    Route::get('/clientes/search', [\App\Http\Controllers\Api\OmieClienteController::class, 'search'])->name('clientes.search');
    Route::get('/clientes/{omieId}', [\App\Http\Controllers\Api\OmieClienteController::class, 'show'])->name('clientes.show');
    Route::post('/clientes/clear-cache', [\App\Http\Controllers\Api\OmieClienteController::class, 'clearCache'])->name('clientes.clear-cache');
    
    Route::get('/fornecedores/search', [\App\Http\Controllers\Api\OmieClienteController::class, 'searchSuppliers'])->name('fornecedores.search');
    Route::get('/fornecedores/{omieId}', [\App\Http\Controllers\Api\OmieClienteController::class, 'show'])->name('fornecedores.show');



});

// Rota de fallback para usuários autenticados que acessam a raiz
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['admin.auth'])->name('dashboard');
