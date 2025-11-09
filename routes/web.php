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

    // Rotas de reservas
    Route::resource('reservas', ReservaController::class);
    Route::post('reservas/{reserva}/aprovar', [ReservaController::class, 'aprovar'])->name('reservas.aprovar');
    Route::post('reservas/{reserva}/rejeitar', [ReservaController::class, 'rejeitar'])->name('reservas.rejeitar');
    Route::post('reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
    Route::post('reservas/{reserva}/iniciar', [ReservaController::class, 'iniciar'])->name('reservas.iniciar'); 
    Route::post('reservas/{reserva}/finalizar', [ReservaController::class, 'finalizar'])->name('reservas.finalizar');
    Route::post('reservas/{reserva}/revisar', [ReservaController::class, 'revisar'])->name('reservas.revisar');

    // Rotas de abastecimentos em reservas
    Route::post('reservas/{reserva}/abastecimentos', [ReservaController::class, 'attachAbastecimento'])->name('reservas.abastecimentos.attach');
    Route::delete('reservas/{reserva}/abastecimentos/{abastecimento}', [ReservaController::class, 'detachAbastecimento'])->name('reservas.abastecimentos.detach');
    
    // Rotas de pedagios em reservas
    Route::post('reservas/{reserva}/pedagios', [ReservaController::class, 'attachPedagio'])->name('reservas.pedagios.attach');
    Route::delete('reservas/{reserva}/pedagio/{pedagio}', [ReservaController::class, 'detachPedagio'])->name('reservas.pedagios.detach');

    // Rotas de passageiros em reservas
    Route::post('reservas/{reserva}/passageiros', [ReservaController::class, 'attachPassageiro'])->name('reservas.passageiros.attach');
    Route::delete('reservas/{reserva}/passageiro/{passageiro}', [ReservaController::class, 'detachPassageiro'])->name('reservas.passageiros.detach');

    // Rotas de manutencoes em reservas
    Route::post('reservas/{reserva}/manutencoes', [ReservaController::class, 'attachManutencao'])->name('reservas.manutencoes.attach');
    Route::delete('reservas/{reserva}/manutencao/{manutencao}', [ReservaController::class, 'detachManutencao'])->name('reservas.manutencoes.detach');

    // Rotas de parametros
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

