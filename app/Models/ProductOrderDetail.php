<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOrderDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'product_order_id',
        'product_detail_id',
        'quantity',
        'total_price',
        'note',
    ];

    protected $casts = [
        'total_price' => 'integer'
    ];

    public function productOrder()
    {
        return $this->belongsTo(ProductOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class);
    }

    public function getPriceAttribute()
    {
        $price = 0;
        if ($this->productOrder->user->type == 'ppdb') {
            $price = $this->productDetail->price_ppdb;
        }
        if ($this->productOrder->user->type == 'siswa') {
            $price = $this->productDetail->price_siswa;
        }

        return $price;
    }

    protected static function boot() {
        parent::boot();

        static::created(function ($productOrderDetail) {
            $productOrderDetail->reduceStock();
        });

        static::updating(function ($productOrderDetail) {
            if (array_key_exists('quantity', $newData = $productOrderDetail->getDirty())) {
                $productOrderDetail->reduceStock($newData['quantity'] - $productOrderDetail->getOriginal('quantity'));
            } else {
                $productOrderDetail->reduceStock(0);
            }
        });

        static::deleting(function ($productOrderDetail) {
            $productOrderDetail->addStock();
        });
    }

    private function addStock($number = null) 
    {
        $this->productDetail->update([
            'stock' => ($this->productDetail->stock + ($number ?? $this->quantity))
        ]);
        // $this->productDetail->increment('stock', $number ?? $this->quantity);
    }

    private function reduceStock($number = null) 
    {
        $this->productDetail->update([
            'stock' => ($this->productDetail->stock - ($number ?? $this->quantity))
        ]);
        // $this->productDetail->decrement('stock', $number ?? $this->quantity);
    }
}
