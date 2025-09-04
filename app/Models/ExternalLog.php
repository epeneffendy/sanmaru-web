<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalLog extends Model
{
    /**
     * @var string
     */
    protected $table = 'payment_external_id_logs';

    protected $fillable = [
        'external_id',
        'request_id',
        'date',
        'flag',
        'english',
        'indonesia',
        'status_code',
        'count'
    ];
}
