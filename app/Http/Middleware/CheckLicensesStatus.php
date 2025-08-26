<?php
// app/Http/Middleware/CheckLicenseStatus.php (ATUALIZADO)

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Licenca; // Usando o model diretamente para a verificação

class CheckLicensesStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Super-admin sempre tem acesso e não pertence a uma empresa
        if ($user->role === 'super-admin' || !$user->id_empresa) {
            return $next($request);
        }

        // Permite o acesso à página de "licença expirada" e à rota de logout
        // para evitar um loop de redirecionamento.
        $allowedRoutes = ['licenca.expirada', 'logout'];
        if (in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }

        // Verifica se a empresa do usuário possui pelo menos uma licença com status 'ativo'.
        $hasActiveLicense = Licenca::where('id_empresa', $user->id_empresa)
                                   ->where('status', 'ativo')
                                   ->exists();

        // Se não houver licença ativa, redireciona para a página de expiração.
        if (!$hasActiveLicense) {
            return redirect()->route('licenca.expirada');
        }

        // Caso contrário, o usuário tem uma licença ativa e pode prosseguir.
        return $next($request);
    }
}