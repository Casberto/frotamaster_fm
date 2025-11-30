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

// Importação dos novos controladores de Reserva (Single Action & Sub-recursos)
use App\Http\Controllers\Reserva\AprovarReservaController;
use App\Http\Controllers\Reserva\RejeitarReservaController;
use App\Http\Controllers\Reserva\CancelarReservaController;
use App\Http\Controllers\Reserva\IniciarReservaController;
use App\Http\Controllers\Reserva\FinalizarReservaController;
use App\Http\Controllers\Reserva\RevisarReservaController;
use App\Http\Controllers\Reserva\ReservaAbastecimentoController;
use App\Http\Controllers\Reserva\ReservaPedagioController;
use App\Http\Controllers\Reserva\ReservaPassageiroController;
use App\Http\Controllers\Reserva\ReservaManutencaoController;

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
    Route::resource('fornecedores', FornecedorController::class)->parameters(['fornecedores' => 'fornecedor']);
    Route::resource('perfis', PerfilController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('motoristas', MotoristaController::class);

    // --- MÓDULO DE RESERVAS (Refatorado) ---
    
    // 1. Workflow de Estados (Usando PATCH conforme os modais)
    Route::patch('reservas/{reserva}/aprovar', AprovarReservaController::class)->name('reservas.aprovar');
    Route::patch('reservas/{reserva}/rejeitar', RejeitarReservaController::class)->name('reservas.rejeitar');
    Route::patch('reservas/{reserva}/cancelar', CancelarReservaController::class)->name('reservas.cancelar');
    Route::patch('reservas/{reserva}/iniciar', IniciarReservaController::class)->name('reservas.iniciar'); 
    Route::patch('reservas/{reserva}/finalizar', FinalizarReservaController::class)->name('reservas.finalizar');
    Route::patch('reservas/{reserva}/corrigir', \App\Http\Controllers\Reserva\CorrigirReservaController::class)->name('reservas.corrigir');
    
    // Revisão usa POST pois o formulário pode ser complexo
    Route::post('reservas/{reserva}/revisar', RevisarReservaController::class)->name('reservas.revisar');

     // SUB-RECURSOS: Abastecimentos
    // Abastecimentos
    Route::post('reservas/{reserva}/abastecimentos', [ReservaAbastecimentoController::class, 'store'])->name('reservas.abastecimentos.attach');
    Route::post('reservas/{reserva}/abastecimentos/novo', [ReservaAbastecimentoController::class, 'storeNew'])->name('reservas.abastecimentos.create'); // <--- NOVA ROTA
    Route::delete('reservas/{reserva}/abastecimentos/{abastecimento}', [ReservaAbastecimentoController::class, 'destroy'])->name('reservas.abastecimentos.detach');

    // 3. Sub-recursos: Pedágios em Reservas
    Route::post('reservas/{reserva}/pedagios', [ReservaPedagioController::class, 'store'])->name('reservas.pedagios.attach');
    Route::delete('reservas/{reserva}/pedagio/{pedagio}', [ReservaPedagioController::class, 'destroy'])->name('reservas.pedagios.detach');

    // 4. Sub-recursos: Passageiros em Reservas
    Route::post('reservas/{reserva}/passageiros', [ReservaPassageiroController::class, 'store'])->name('reservas.passageiros.attach');
    Route::put('reservas/{reserva}/passageiros/{passageiro}', [ReservaPassageiroController::class, 'update'])->name('reservas.passageiros.update');
    Route::delete('reservas/{reserva}/passageiro/{passageiro}', [ReservaPassageiroController::class, 'destroy'])->name('reservas.passageiros.detach');

    // 5. Sub-recursos: Manutenções em Reservas
    Route::post('reservas/{reserva}/manutencoes', [ReservaManutencaoController::class, 'store'])->name('reservas.manutencoes.attach');
    Route::delete('reservas/{reserva}/manutencao/{manutencao}', [ReservaManutencaoController::class, 'destroy'])->name('reservas.manutencoes.detach');

    // 6. CRUD Padrão de Reservas (Deve vir por último para evitar conflito de rotas como /{reserva}/algo)
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