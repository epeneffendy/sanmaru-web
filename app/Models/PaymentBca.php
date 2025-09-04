<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentBca extends Model
{
    protected $table = 'payment_bcas';

    protected $fillable = [
        'company_code',
        'channel_type',
        'request_id',
        'customer_number',
        'sub_company',
        'currency',
        'reference',
        'bill_number',
        'status',
        'transaction_date',
        'total_amount',
        'paid_amount',
    ];
}
