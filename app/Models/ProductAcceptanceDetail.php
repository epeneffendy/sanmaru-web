<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAcceptanceDetail extends Model
{
    protected $table = 'product_acceptance_detail';
    public $timestamps = false;
    protected $fillable = [
        'product_acceptance_id',
        'product_detail_id',
        'size',
        'date',
        'stock',
        'price_siswa',
        'price_ppdb',
        'price_vendor_reguler',
        'price_vendor_ppdb',
    ];
}
