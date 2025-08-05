<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Log::with('user');

        // --- LÓGICA DE VISIBILIDADE CORRIGIDA ---
        if ($user->role === 'super-admin') {
            // Super-admin vê apenas os logs de suas próprias ações (ex: gestão de empresas).
            // Essas ações não têm id_empresa, então filtramos pelo user_id.
            $query->where('user_id', $user->id);
        } else {
            // Usuários padrão (master, etc.) veem todos os logs de sua própria empresa.
            $query->where('id_empresa', $user->id_empresa);
        }

        // Filtros da tela
        if ($request->filled('data_inicio')) {
            $query->where('created_at', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->where('created_at', '<=', $request->data_fim . ' 23:59:59');
        }
        if ($request->filled('user_id')) {
            // O filtro de usuário só se aplica para a visão da empresa.
            if ($user->role !== 'super-admin') {
                $query->where('user_id', $request->user_id);
            }
        }
        if ($request->filled('tela')) {
            $query->where('tela', $request->tela);
        }
        if ($request->filled('acao')) {
            $query->where('acao', $request->acao);
        }

        $logs = $query->latest()->paginate(25)->withQueryString();

        // Para popular o dropdown de filtro de usuários
        $usuarios = collect(); // Inicia uma coleção vazia
        if ($user->role !== 'super-admin') {
            $usuarios = User::where('id_empresa', $user->id_empresa)->orderBy('name')->get();
        }

        return view('logs.index', compact('logs', 'usuarios'));
    }
}
