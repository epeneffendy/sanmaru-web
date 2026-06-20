<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDispensationRequest extends Model
{
    protected $table = 'payment_dispensation_request';

    protected $fillable = [
        'ppdb_user_id',
        'unit_id',
        'school_year',
        'dispensation_type',
        'description',
        'reason',
        'attachment',
        'status',
        'verified_date',
        'verified_user_id',
    ];

    const STATUS_WAITING = 'waiting';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
}
