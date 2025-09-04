<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeploymentLog extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
        // Perubahan
    }
}
