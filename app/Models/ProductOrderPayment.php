<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOrderPayment extends Model
{
    protected $table = 'product_order_payments';

    protected $fillable = [
        'product_order_id',
        'bank',
        'payment_bca_id',
        'total_payment',
        'status',
        'payment_date',
    ];
}
