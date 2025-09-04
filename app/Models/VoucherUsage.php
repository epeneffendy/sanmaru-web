<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherUsage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_order_id',
        'voucher_id',
        'deleted_at'
    ];

    public function orders()
    {
        return $this->hasMany(ProductOrder::class, 'id', 'product_order_id');
    }
}