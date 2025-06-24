<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
// Importações dos Controllers - A linha do VeiculoController provavelmente estava faltando
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\ManutencaoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota para a página inicial
Route::get('/', function () {
    return view('welcome');
});

// ROTA DE REDIRECIONAMENTO APÓS LOGIN
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'super-admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// ROTAS DO USUÁRIO AUTENTICADO (CLIENTE DA EMPRESA)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD de Veículos para o usuário da empresa
    Route::resource('veiculos', VeiculoController::class);

    // CRUD de Manutenções
    Route::resource('manutencoes', ManutencaoController::class);
});


// GRUPO DE ROTAS DO SUPER ADMINISTRADOR
// Usamos o Route::controller para agrupar as rotas de EmpresaController
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // CRUD de Empresas
    Route::resource('empresas', EmpresaController::class);
});


// Arquivo com as rotas de autenticação (login, logout, etc.)
require __DIR__.'/auth.php';

