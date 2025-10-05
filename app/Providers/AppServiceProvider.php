<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\Manutencao;
use App\Observers\ManutencaoObserver;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Compartilha a licença ativa com a view de navegação
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