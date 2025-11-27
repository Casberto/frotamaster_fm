<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL; // <--- Importante: Adicionei esta linha
use App\Models\Empresa;
use App\Models\Manutencao;
use App\Observers\ManutencaoObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Força HTTPS em ambientes de produção/teste para evitar erro "Mixed Content"
        if (config('app.env') !== 'local' || request()->server('HTTP_X_FORWARDED_PROTO') == 'https') {
            URL::forceScheme('https');
        }

        // Compartilha a licença ativa com a view de navegação (Sidebar)
        View::composer('layouts.navigation', function ($view) {
            if (Auth::check() && Auth::user()->id_empresa) {
                $empresa = Empresa::with('activeLicense')->find(Auth::user()->id_empresa);
                $view->with('activeLicense', $empresa ? $empresa->activeLicense : null);
            } else {
                $view->with('activeLicense', null);
            }
        });
    }
}