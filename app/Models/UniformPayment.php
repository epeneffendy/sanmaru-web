<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniformPayment extends Model
{
    protected $fillable = [
    	'product_order_id', 
    	'payment_number', 
    	'payment_name', 
    	'payment_date', 
    	'payment_amount',
    	'payment_method'
    ];

    public function productOrder()
    {
    	return $this->belongsTo(ProductOrder::class, 'product_order_id', 'id');
    }

    public function getOverpaymentAttribute()
    {
    	if ($this->productOrder) {
    		return ($this->payment_amount - $this->productOrder->grand_total);
    	}

    	return 0;
    }
}
