<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = Auth::user();

        if (!$user || !$user->empresa) {
             // Se não tem empresa ou user, deixa o auth middleware tratar ou nega
             return $next($request);
        }

        // Se for Super Admin, passa direto
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (!$user->empresa->hasModule($module)) {
            // Se for uma requisição AJAX, retorna JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Módulo não contratado ou sem permissão.'], 403);
            }

            // Redireciona para dashboard com erro
            return redirect()->route('dashboard')->with('error', 'Sua empresa não tem acesso ao módulo solicitado: ' . ucfirst($module));
        }

        return $next($request);
    }
}
