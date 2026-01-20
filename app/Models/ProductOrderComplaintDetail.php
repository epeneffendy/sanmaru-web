<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOrderComplaintDetail extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_RESPONSE = 'response';

    protected $table = 'product_order_complaint_detail';

    protected $fillable = [
        'product_order_complaint_id',
        'product_order_id',
        'product_order_detail_id',
        'product_id',
        'complaint',
        'complaint_response',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function productOrderDetail()
    {
        return $this->hasOne('App\Models\ProductOrderDetail', 'id', 'product_order_detail_id');
    }

}
