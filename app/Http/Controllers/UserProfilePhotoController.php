<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserProfilePhotoController extends Controller
{
    /**
     * Serve a user profile photo securely.
     */
    public function show(string $filename)
    {
        $user = Auth::user();

        // Determina a pasta base da empresa (ou individual) baseada no usuário logado.
        // Se a lógica futura permitir ver perfis de outros, aqui precisaria de mais validação.
        // Por ora, assumimos ver o próprio perfil ou de alguém da mesma empresa.
        // Mas como o filename é único, podemos buscar o arquivo direto se o usuario tiver permissão.
        
        // Simplificação: 
        // 1. Tenta achar o arquivo na pasta da empresa do usuário
        $empresaId = $user->id_empresa ?? 'individual';
        $path = "profile_pics/{$empresaId}/{$filename}";

        // Segurança básica: Checa se o arquivo existe e pertence à empresa do context
        // Em um cenário mais complexo, verificaríamos se o arquivo pertence a um usuário da mesma empresa.
        
        if (!Storage::exists($path)) {
            abort(404);
        }

        return Storage::response($path);
    }
}
