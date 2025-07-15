<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\ManutencaoController;
use App\Http\Controllers\AbastecimentoController;
use App\Http\Controllers\DashboardController;


// Rota para a página inicial
Route::get('/', function () {
    return view('welcome');
});

// ROTA DE REDIRECIONAMENTO APÓS LOGIN
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');


// ROTAS DO USUÁRIO AUTENTICADO (CLIENTE DA EMPRESA)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUDs da Aplicação
    Route::resource('veiculos', VeiculoController::class);
    Route::resource('manutencoes', ManutencaoController::class);
    Route::resource('abastecimentos', AbastecimentoController::class);
});


// GRUPO DE ROTAS DO SUPER ADMINISTRADOR
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // CRUD de Empresas
    Route::resource('empresas', EmpresaController::class);
});


require __DIR__.'/auth.php';
