<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class Notificacao extends DatabaseNotification
{
    protected $table = 'notificacoes';
    protected $primaryKey = 'not_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Custom timestamps
    const CREATED_AT = 'not_created_at';
    const UPDATED_AT = 'not_updated_at';

    protected $casts = [
        'not_data' => 'array',
        'not_read_at' => 'datetime',
    ];

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable()
    {
        return $this->morphTo('not_notifiable', 'not_notifiable_type', 'not_notifiable_id');
    }

    /**
     * Mark the notification as read.
     *
     * @return void
     */
    public function markAsRead()
    {
        if (is_null($this->not_read_at)) {
            $this->forceFill(['not_read_at' => $this->freshTimestamp()])->save();
        }
    }

    /**
     * Mark the notification as unread.
     *
     * @return void
     */
    public function markAsUnread()
    {
        if (! is_null($this->not_read_at)) {
            $this->forceFill(['not_read_at' => null])->save();
        }
    }

    /**
     * Determine if a notification has been read.
     *
     * @return bool
     */
    public function read()
    {
        return $this->not_read_at !== null;
    }

    /**
     * Determine if a notification has not been read.
     *
     * @return bool
     */
    public function unread()
    {
        return $this->not_read_at === null;
    }

    /**
     * Get the data attribute.
     * Alias for not_data to maintain compatibility.
     *
     * @return array
     */
    public function getDataAttribute()
    {
        return $this->not_data;
    }

    /**
     * Set the data attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setDataAttribute($value)
    {
        $this->attributes['not_data'] = json_encode($value);
    }
}
