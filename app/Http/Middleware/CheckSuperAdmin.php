<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está autenticado E se a sua role é 'super-admin'
        if (auth()->check() && auth()->user()->role === 'super-admin') {
            return $next($request); // Se for, permite o acesso à rota
        }

        // Se não for, aborta a requisição com um erro 403 (Acesso Proibido)
        abort(403, 'Acesso não autorizado.');
    }
}
