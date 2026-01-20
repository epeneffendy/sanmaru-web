<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionOrderDetail extends Model
{
    protected $table = 'distribution_order_detail';
    protected $fillable = [
        'distribution_order_id',
        'name',
        'product_name',
        'size',
        'qty',
    ];
}
