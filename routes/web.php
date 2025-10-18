<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\ManutencaoController;
use App\Http\Controllers\AbastecimentoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\Admin\PermissaoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\Admin\ConfiguracaoPadraoController;
use App\Http\Controllers\ConfiguracaoEmpresaController;

// Rota para a página inicial
Route::get('/', function () {
    return view('welcome');
});

// --- ROTAS PÚBLICAS ---
Route::get('/register-company', [RegisterController::class, 'create'])->middleware('guest')->name('company.register');
Route::post('/register-company', [RegisterController::class, 'store'])->middleware('guest')->name('company.store');
Route::view('/licenca-expirada', 'licenca-expirada')->middleware('auth')->name('licenca.expirada');


// ROTA DE REDIRECIONAMENTO APÓS LOGIN
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'check.license'])->name('dashboard');


// ROTAS DO USUÁRIO AUTENTICADO (CLIENTE DA EMPRESA)
Route::middleware(['auth', 'check.license'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rota de geração de Logs
    Route::get('/logs', [App\Http\Controllers\LogController::class, 'index'])->name('logs.index');

    // CRUDs da Aplicação
    Route::resource('veiculos', VeiculoController::class);
    Route::resource('manutencoes', ManutencaoController::class)->parameters(['manutencoes' => 'manutencao']);
    Route::resource('abastecimentos', AbastecimentoController::class);
    Route::resource('servicos', ServicoController::class);
    Route::resource('fornecedores', FornecedorController::class);
    Route::resource('perfis', PerfilController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('motoristas', MotoristaController::class);

    // --- CORREÇÃO: Sintaxe da Rota e Adição do POST ---
    Route::get('parametros', [ConfiguracaoEmpresaController::class, 'index'])->name('parametros.index');
    Route::post('parametros', [ConfiguracaoEmpresaController::class, 'update'])->name('parametros.update');


    // Rotas AJAX para o Dashboard
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/veiculos/{id}/historico', [DashboardController::class, 'getVeiculoHistorico'])->name('veiculos.historico');
    Route::get('/abastecimentos/veiculo/{id}', [AbastecimentoController::class, 'getVeiculoData'])->name('abastecimentos.veiculo.data');

});


// GRUPO DE ROTAS DO SUPER ADMINISTRADOR
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // CRUD de Empresas
    Route::resource('empresas', EmpresaController::class);

    // CRUD de Licenças
    Route::resource('licencas', LicenseController::class);

    // CRUD de Permissões
    Route::resource('permissoes', PermissaoController::class);

     // CRUD de Configurações Padrão
    Route::resource('configuracoes-padrao', ConfiguracaoPadraoController::class)->parameters([
        'configuracoes-padrao' => 'configuracoes_padrao'
    ]);
});


require __DIR__.'/auth.php';

