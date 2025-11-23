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
use App\Http\Controllers\ReservaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

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
    
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Logs e Parâmetros
    Route::get('/logs', [App\Http\Controllers\LogController::class, 'index'])->name('logs.index');
    Route::get('parametros', [ConfiguracaoEmpresaController::class, 'index'])->name('parametros.index');
    Route::post('parametros', [ConfiguracaoEmpresaController::class, 'update'])->name('parametros.update');

    // CRUDs Principais
    Route::resource('veiculos', VeiculoController::class);
    Route::resource('manutencoes', ManutencaoController::class)->parameters(['manutencoes' => 'manutencao']);
    Route::resource('abastecimentos', AbastecimentoController::class);
    Route::resource('servicos', ServicoController::class);
    Route::resource('fornecedores', FornecedorController::class);
    Route::resource('perfis', PerfilController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('motoristas', MotoristaController::class);

    // --- MÓDULO DE RESERVAS (Versão Definitiva) ---
    Route::controller(ReservaController::class)->prefix('reservas')->name('reservas.')->group(function () {
        
        // Workflow de Estados (Verbos PATCH para alteração de estado)
        Route::patch('/{reserva}/aprovar', 'aprovar')->name('aprovar');
        Route::patch('/{reserva}/rejeitar', 'rejeitar')->name('rejeitar');
        Route::patch('/{reserva}/cancelar', 'cancelar')->name('cancelar');
        Route::patch('/{reserva}/iniciar', 'iniciar')->name('iniciar');
        Route::patch('/{reserva}/finalizar', 'finalizar')->name('finalizar');
        
        // Revisão (POST pois envia formulário complexo)
        Route::post('/{reserva}/revisar', 'revisar')->name('revisar');

        // Vínculos (Abastecimentos, Pedágios, Passageiros, Manutenções)
        Route::post('/{reserva}/abastecimentos', 'attachAbastecimento')->name('abastecimentos.attach');
        Route::delete('/{reserva}/abastecimentos/{abastecimento}', 'detachAbastecimento')->name('abastecimentos.detach');
        
        Route::post('/{reserva}/pedagios', 'attachPedagio')->name('pedagios.attach');
        Route::delete('/{reserva}/pedagio/{pedagio}', 'detachPedagio')->name('pedagios.detach');

        Route::post('/{reserva}/passageiros', 'attachPassageiro')->name('passageiros.attach');
        Route::delete('/{reserva}/passageiro/{passageiro}', 'detachPassageiro')->name('passageiros.detach');

        Route::post('/{reserva}/manutencoes', 'attachManutencao')->name('manutencoes.attach');
        Route::delete('/{reserva}/manutencao/{manutencao}', 'detachManutencao')->name('manutencoes.detach');
    });

    // Resource Padrão de Reservas (Index, Create, Store, Show, Edit, Update, Destroy)
    // Deve vir DEPOIS das rotas personalizadas acima para evitar conflito de URL
    Route::resource('reservas', ReservaController::class);


    // Rotas AJAX Dashboard
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/veiculos/{id}/historico', [DashboardController::class, 'getVeiculoHistorico'])->name('veiculos.historico');
    Route::get('/abastecimentos/veiculo/{id}', [AbastecimentoController::class, 'getVeiculoData'])->name('abastecimentos.veiculo.data');
});


// GRUPO DE ROTAS DO SUPER ADMINISTRADOR
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
    Route::resource('empresas', EmpresaController::class);
    Route::resource('licencas', LicenseController::class);
    Route::resource('permissoes', PermissaoController::class);
    Route::resource('configuracoes-padrao', ConfiguracaoPadraoController::class)->parameters(['configuracoes-padrao' => 'configuracoes_padrao']);
});

require __DIR__.'/auth.php';