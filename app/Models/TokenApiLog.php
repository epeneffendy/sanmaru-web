<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenApiLog extends Model
{
    /**
     * @var string
     */
    protected $table = 'token_api_logs';

    protected $fillable = [
        'access_token',
        'expires_at',
    ];
}
