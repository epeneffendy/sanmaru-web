<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDispensationDetails extends Model
{
    protected $table = 'payment_dispensation_details';

    protected $fillable = [
        'payment_dispensation_id',
        'installment_number',
        'virtual_account',
        'date',
        'plan_date',
        'nominal',
        'amount_paid',
        'status',
    ];

    const STATUS_UNPAID = 'unpaid';
    const STATUS_PAID = 'paid';
    const STATUS_PARTIAL = 'partial';

    const DISPENSATION_TYPE_DEVELOPMENT = 'development';
    const DISPENSATION_TYPE_ACTIVITY = 'activity';


}
