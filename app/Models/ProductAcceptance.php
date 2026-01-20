<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAcceptance extends Model
{
    protected $table = 'product_acceptance';

    protected $fillable = [
        'product_id',
        'vendor_id',
        'date',
        'description',
        'created_by'
    ];

    public function vendor()
    {
        return $this->hasOne(__NAMESPACE__ . '\Vendor', 'id', 'vendor_id');
    }

    public function product()
    {
        return $this->hasOne(__NAMESPACE__ . '\Product', 'id', 'product_id');
    }
}
