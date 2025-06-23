<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rota para a página inicial
Route::get('/', function () {
    return view('welcome');
});

// A ROTA PRINCIPAL DO DASHBOARD
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'super-admin') {
        return redirect()->route('admin.dashboard');
    }

    // Futuramente, aqui será o dashboard do usuário comum da empresa
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// GRUPO DE ROTAS DO SUPER ADMINISTRADOR
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Rota para o Dashboard do Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // --- CRUD COMPLETO DE EMPRESAS (MANUAL) ---
    // Listagem (Index)
    Route::get('empresas', [EmpresaController::class, 'index'])->name('empresas.index');
    
    // Formulário de Criação (Create)
    Route::get('empresas/create', [EmpresaController::class, 'create'])->name('empresas.create');
    
    // Salvar Nova Empresa (Store)
    Route::post('empresas', [EmpresaController::class, 'store'])->name('empresas.store');
    
    // Formulário de Edição (Edit)
    Route::get('empresas/{empresa}/edit', [EmpresaController::class, 'edit'])->name('empresas.edit');
    
    // Atualizar Empresa (Update)
    Route::put('empresas/{empresa}', [EmpresaController::class, 'update'])->name('empresas.update');
    
    // Deletar Empresa (Destroy)
    Route::delete('empresas/{empresa}', [EmpresaController::class, 'destroy'])->name('empresas.destroy');

});


// Este arquivo é importado para manter as rotas de autenticação (login, logout, etc.)
require __DIR__.'/auth.php';
