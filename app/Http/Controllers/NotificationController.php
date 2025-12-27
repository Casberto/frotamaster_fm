<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Retorna lista de notificações e contagem de não lidas.
     */
    public function index()
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                // Formatar dados para o frontend
                $data = $notification->data; // Accessor já lida com not_data
                return [
                    'id' => $notification->not_id, // not_id (Corrigido acesso)
                    'title' => $data['titulo'] ?? 'Notificação',
                    'message' => $data['mensagem'] ?? '',
                    'link' => $data['link'] ?? '#',
                    'type' => $data['tipo'] ?? 'info',
                    'icon' => $data['icon'] ?? 'bell',
                    'read' => !is_null($notification->not_read_at),
                    'date_human' => $notification->not_created_at->diffForHumans(),
                    'created_at' => $notification->not_created_at,
                ];
            })->values();

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications
        ]);
    }

    /**
     * Marca uma notificação como lida.
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        if ($notification->unread()) {
            $notification->markAsRead();
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Marca todas as notificações como lidas.
     */
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'success']);
    }

    /**
     * Remove uma notificação.
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['status' => 'success']);
    }
}
