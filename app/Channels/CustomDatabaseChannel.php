<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\DatabaseChannel as BaseDatabaseChannel;

class CustomDatabaseChannel extends BaseDatabaseChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function send($notifiable, Notification $notification)
    {
        // Pega os dados padrão preparados pelo Laravel (id, type, data, read_at)
        // Mas a gente constroi manualmente para ter controle total das chaves
        
        // CORREÇÃO: Usar o relacionamento notifications() definido no User, que já configura o morphMany correto.
        // O routeNotificationFor('database') geralmente retorna array de destinatários ou null, não o builder.
        
        return $notifiable->notifications()->create([
            'not_id' => $notification->id,
            'not_type' => get_class($notification),
            'not_data' => $this->getData($notifiable, $notification),
            'not_read_at' => null,
            // 'not_notifiable_type' e 'not_notifiable_id' serão preenchidos automaticamente pelo relacionamento
        ]);
    }
}
