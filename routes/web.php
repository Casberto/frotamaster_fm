<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\VeiculoFotoController;
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
use App\Http\Controllers\SegurosController;
use App\Http\Controllers\SeguroSinistroController;
use App\Http\Controllers\SeguroSinistroFotoController;
use App\Http\Controllers\SeguroCoberturaController;

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

// Rotas Públicas da Oficina (Aprovação de Orçamento)
Route::get('/oficina/public/os/{token}', [\App\Http\Controllers\Oficina\AprovacaoClienteController::class, 'show'])->name('oficina.os.public.show');
Route::post('/oficina/public/os/{token}/aceitar', [\App\Http\Controllers\Oficina\AprovacaoClienteController::class, 'aceitar'])->name('oficina.os.public.aceitar');


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
    Route::resource('veiculos', VeiculoController::class)->middleware('check.module:veiculos');
    
    // Rotas de Fotos de Veículos
    Route::get('veiculos/{id}/fotos', [VeiculoFotoController::class, 'index'])->name('veiculos.fotos.index')->middleware('check.module:veiculos');
    Route::post('veiculos/{id}/fotos', [VeiculoFotoController::class, 'store'])->name('veiculos.fotos.store')->middleware('check.module:veiculos');
    Route::delete('veiculos/fotos/{id}', [VeiculoFotoController::class, 'destroy'])->name('veiculos.fotos.destroy')->middleware('check.module:veiculos');
    Route::get('veiculos/fotos/{filename}', [VeiculoFotoController::class, 'show'])->name('veiculos.fotos.show')->middleware('check.module:veiculos');

    Route::resource('manutencoes', ManutencaoController::class)->parameters(['manutencoes' => 'manutencao'])->middleware('check.module:manutencoes');
    Route::resource('abastecimentos', AbastecimentoController::class)->middleware('check.module:abastecimentos');
    Route::resource('servicos', ServicoController::class)->middleware('check.module:cadastros');
    Route::resource('fornecedores', FornecedorController::class)->parameters(['fornecedores' => 'fornecedor'])->middleware('check.module:cadastros');
    Route::resource('perfis', PerfilController::class)->middleware('check.module:configuracoes'); 
    Route::resource('usuarios', UsuarioController::class)->middleware('check.module:usuarios');
    Route::resource('motoristas', MotoristaController::class)->middleware('check.module:motoristas');

    // --- MÓDULO DE SEGUROS ---
    Route::middleware('check.module:seguros')->group(function() {
        // Rota para Renovação
        Route::post('/seguros/{id}/renew', [SegurosController::class, 'renew'])->name('seguros.renew');

        // Rotas para Fotos de Sinistros
        Route::get('/seguros/sinistros/{id}/fotos', [SeguroSinistroFotoController::class, 'index']);
        Route::post('/seguros/sinistros/{id}/fotos', [SeguroSinistroFotoController::class, 'store']);
        Route::delete('/seguros/sinistros/fotos/{id}', [SeguroSinistroFotoController::class, 'destroy']);
        Route::get('/seguros/sinistros/fotos/{filename}', [SeguroSinistroFotoController::class, 'show'])->name('seguros.sinistros.fotos.show');
        Route::get('/seguros/{id}/download', [SegurosController::class, 'download'])->name('seguros.download');
        Route::resource('seguros', SegurosController::class);
        
        // Coberturas
        Route::post('coberturas', [SeguroCoberturaController::class, 'store'])->name('coberturas.store');
        Route::delete('coberturas/{id}', [SeguroCoberturaController::class, 'destroy'])->name('coberturas.destroy');

        // Sinistros
        Route::post('sinistros', [SeguroSinistroController::class, 'store'])->name('sinistros.store');
        Route::put('sinistros/{id}', [SeguroSinistroController::class, 'update'])->name('sinistros.update');
        Route::delete('sinistros/{id}', [SeguroSinistroController::class, 'destroy'])->name('sinistros.destroy');
    });

    // --- MÓDULO DE RESERVAS (Refatorado) ---
    Route::middleware('check.module:reservas')->group(function() {
        
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
    });

    // --- MÓDULO OFICINA ---
    Route::prefix('oficina')->name('oficina.')->group(function () {
        Route::get('/painel', [\App\Http\Controllers\Oficina\PainelController::class, 'index'])->name('painel.index');
        Route::get('/historico', [\App\Http\Controllers\Oficina\PainelController::class, 'historico'])->name('historico');
        Route::post('/painel/update-status', [\App\Http\Controllers\Oficina\PainelController::class, 'updateStatus'])->name('painel.update-status');
        
        // Rotas de Veículos (AJAX)
        Route::get('/veiculos/buscar-placa', [\App\Http\Controllers\Oficina\VeiculoTerceiroController::class, 'buscarPlaca'])->name('veiculos.buscar-placa');

        // Rotas de OS
        Route::get('/os/create', [\App\Http\Controllers\Oficina\OrdemServicoController::class, 'create'])->name('os.create');
        Route::post('/os', [\App\Http\Controllers\Oficina\OrdemServicoController::class, 'store'])->name('os.store');
        Route::get('/os/{id}', [\App\Http\Controllers\Oficina\OrdemServicoController::class, 'show'])->name('os.show');
        Route::post('/os/{id}/whatsapp', [\App\Http\Controllers\Oficina\OrdemServicoController::class, 'gerarLinkWhatsapp'])->name('os.whatsapp');
        Route::post('/os/{id}/diagnostico', [\App\Http\Controllers\Oficina\OrdemServicoController::class, 'salvarDiagnostico'])->name('os.diagnostico');
        
        // Fluxo de Execução
        Route::post('/os/{id}/iniciar-execucao', [\App\Http\Controllers\Oficina\OrdemServicoController::class, 'iniciarExecucao'])->name('os.iniciar_execucao');
        Route::post('/os/{id}/solicitar-pecas', [\App\Http\Controllers\Oficina\OrdemServicoController::class, 'solicitarPecas'])->name('os.solicitar_pecas');
        Route::post('/os/{id}/finalizar', [\App\Http\Controllers\Oficina\OrdemServicoController::class, 'finalizarServico'])->name('os.finalizar');
        Route::post('/os/{id}/whatsapp-pronto', [\App\Http\Controllers\Oficina\OrdemServicoController::class, 'gerarLinkWhatsappPronto'])->name('os.whatsapp_pronto');
        Route::post('/os/{id}/rejeitar', [\App\Http\Controllers\Oficina\OrdemServicoController::class, 'rejeitarOrcamento'])->name('os.rejeitar');
        Route::post('/os/{id}/entregar', [\App\Http\Controllers\Oficina\OrdemServicoController::class, 'entregarVeiculo'])->name('os.entregar');
    Route::post('/os/{id}/garantia', [\App\Http\Controllers\Oficina\OrdemServicoController::class, 'acionarGarantia'])->name('os.garantia');

        // Itens da OS
        Route::post('/os/{id}/items', [\App\Http\Controllers\Oficina\OsItemController::class, 'store'])->name('os.items.store');
        Route::delete('/items/{id}', [\App\Http\Controllers\Oficina\OsItemController::class, 'destroy'])->name('os.items.destroy');

        // Financeiro e Compras
        Route::get('/financeiro', [\App\Http\Controllers\Oficina\FinanceiroController::class, 'index'])->name('financeiro');
        Route::get('/lista-compras', [\App\Http\Controllers\Oficina\FinanceiroController::class, 'listaComprasDia'])->name('compras.dia');
    });

    // Rotas AJAX Dashboard
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/veiculos/{id}/historico', [DashboardController::class, 'getVeiculoHistorico'])->name('veiculos.historico')->middleware('check.module:veiculos');
    Route::get('/manutencoes/{id}/detalhes', [DashboardController::class, 'getMaintenanceDetails'])->name('manutencoes.detalhes')->middleware('check.module:manutencoes');
    Route::get('/abastecimentos/{id}/detalhes', [DashboardController::class, 'getFuelingDetails'])->name('abastecimentos.detalhes')->middleware('check.module:abastecimentos');
    Route::get('/dashboard/reservations/{id}/details', [DashboardController::class, 'getReservationDetails'])->name('dashboard.reservations.details')->middleware('check.module:reservas');
    Route::get('/abastecimentos/veiculo/{id}', [AbastecimentoController::class, 'getVeiculoData'])->name('abastecimentos.veiculo.data')->middleware('check.module:abastecimentos');
});


// GRUPO DE ROTAS DO SUPER ADMINISTRADOR
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/monitor/stats', [App\Http\Controllers\Admin\AdminDashboardController::class, 'stats'])->name('monitor.stats');
    Route::resource('empresas', EmpresaController::class);
    Route::resource('licencas', LicenseController::class);
    Route::resource('permissoes', PermissaoController::class);
    Route::resource('configuracoes-padrao', ConfiguracaoPadraoController::class)->parameters(['configuracoes-padrao' => 'configuracoes_padrao']);
});

require __DIR__.'/auth.php';