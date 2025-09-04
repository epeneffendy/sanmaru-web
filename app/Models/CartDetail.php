<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Awobaz\Compoships\Compoships;

class CartDetail extends Model
{
    use SoftDeletes, Compoships;

    protected $fillable = [
        'cart_id', 'product_id', 'product_detail_id', 'quantity', 'total_price','note'
    ];

    protected $dates = ['deleted_at'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function product_detail()
    {
        return $this->hasOne(ProductDetail::class, [
            'id', 'product_id'
        ], [
            'product_detail_id', 'product_id'
        ]);
    }
}