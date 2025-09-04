<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    public function sender()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
