<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


// Rota para a página inicial
Route::get('/', function () {
    return view('welcome');
});

// A ROTA PRINCIPAL DO DASHBOARD QUE ESTAVA FALTANDO
// Esta rota vai checar o tipo de usuário e redirecionar para o painel correto.
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
// Protegido para que apenas usuários com a role 'super-admin' possam acessar.
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Rota para o Dashboard do Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Aqui dentro virão as rotas para gerenciar empresas, usuários, etc.

});


// Este arquivo é importado para manter as rotas de autenticação (login, logout, etc.)
require __DIR__.'/auth.php';
