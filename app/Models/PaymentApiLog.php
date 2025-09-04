<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentApiLog extends Model
{
    /**
     * @var string
     */
    protected $table = 'payment_api_logs';

    protected $fillable = [
        'type',
        'request',
        'response',
    ];
}
