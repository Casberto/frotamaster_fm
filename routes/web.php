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
    ->middleware(['auth', 'verified', 'check.license'])->name('dashboard'); // Adicionado 'check.license' aqui também


// ROTAS DO USUÁRIO AUTENTICADO (CLIENTE DA EMPRESA)
// A CORREÇÃO PRINCIPAL ESTÁ AQUI: ADICIONADO O 'check.license'
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
});


require __DIR__.'/auth.php';
