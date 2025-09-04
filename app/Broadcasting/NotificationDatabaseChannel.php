<?php

namespace App\Broadcasting;

use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Notification;

class NotificationDatabaseChannel extends DatabaseChannel
{
    /**
     * Build an array payload for the DatabaseNotification Model.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array
     */
    protected function buildPayload($notifiable, Notification $notification)
    {
        $notificationEmail = 0;
        if ( isset($notification->details['send_email']) ) {
            $notificationEmail = $notification->details['send_email'];
        }

        return [
            'id' => $notification->id,
            'type' => get_class($notification),
            'data' => $this->getData($notifiable, $notification),
            'read_at' => null,
            'created_by' => auth()->user()->id,
            'send_email' => $notificationEmail,
            'sended_email' => null,
        ];
    }
}
