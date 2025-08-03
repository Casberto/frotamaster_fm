<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        // Adicionando a nossa lógica de busca segura para Manutenção
        Route::bind('manutencao', function ($value) {
            if (!Auth::check()) {
                return null;
            }
            // Procura a manutenção pelo ID E pelo id_empresa do usuário logado.
            // firstOrFail() irá disparar um erro 404 (Não Encontrado) automaticamente se não encontrar.
            return \App\Models\Manutencao::where('id', $value)
                ->where('id_empresa', Auth::user()->id_empresa)
                ->firstOrFail();
        });

        // Adicionando a mesma lógica para Veiculo para consistência e segurança
        Route::bind('veiculo', function ($value) {
            if (!Auth::check()) {
                return null;
            }
            
            return \App\Models\Veiculo::where('id', $value)
                ->where('id_empresa', Auth::user()->id_empresa)
                ->firstOrFail();
        });
    }
}
