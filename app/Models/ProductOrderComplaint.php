<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOrderComplaint extends Model
{
    protected $table = 'product_order_complaint';

    protected $fillable = [
        'product_order_id',
        'user_id',
        'created_at',
        'updated_at'
    ];

    public function complaintDetails()
    {
        return $this->hasMany(ProductOrderComplaintDetail::class);
    }
}
