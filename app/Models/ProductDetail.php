<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;
use App\Contracts\ActivityLog\ModelMetadata;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDetail extends Model implements ModelMetadata
{
    use Compoships, SoftDeletes;

    protected $table = 'product_details';

    protected $fillable = [
        'stock', 'price_siswa', 'size', 'product_id', 'price_ppdb', 'price_vendor_regular', 'price_vendor_ppdb'
    ];

    protected $casts = [
        'price_siswa' => 'integer',
        'stock' => 'integer',
        'price_ppdb' => 'integer',
        'price_vendor_regular' => 'integer',
        'price_vendor_ppdb' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(__NAMESPACE__. '\Product');
    }

    public function getInitialStockAttribute()
    {
        return $this->stock + $this->stock_sold;
    }

    public function getAvailableStockAttribute()
    {
        return $this->stock;
    }

    public function getStockSoldAttribute()
    {
        $orderDetails = $this->orderDetails->filter(function ($orderDetail) {
            return $orderDetail->productOrder->status !== ProductOrder::STATUS_CANCEL;
        });

        return $orderDetails->sum('quantity');
    }

    public function getStockCanceledAttribute()
    {
        $orderDetails = $this->orderDetails->filter(function ($orderDetail) {
            return $orderDetail->productOrder->status === ProductOrder::STATUS_CANCEL;
        });

        return $orderDetails->sum('quantity');
    }

    public function orderDetails()
    {
        return $this->hasMany(ProductOrderDetail::class, 'product_detail_id', 'id')
                     ->with('productOrder');
    }

    public static function isPricePPDBApplied($date=null)
    {
        $now = $date ? Carbon::parse($date) : Carbon::now();
        $applied = Carbon::parse('2021-08-17');
        return $now->greaterThanOrEqualTo($applied);
    }

    public function getModelMetadata()
    {
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->product->name,
            'size' => $this->size,
            'price_siswa' => $this->price_siswa,
            'stock' => $this->stock,
            'price_ppdb' => $this->price_ppdb,
        ];
    }

    public function activityLogs()
    {
        return $this->morphMany('App\Models\ActivityLog', 'model');
    }

    public function getTodayStockAdditionAttribute()
    {
        $addition = 0;
        if ($this->activityLogs) {
            foreach ($this->activityLogs as $log) {
                if ($log->data && $log->origin) {
                    $data = json_decode($log->data, TRUE);
                    $origin = json_decode($log->origin, TRUE);
                    $addition += max(0, ($data['stock'] - $origin['stock']));
                }
            }
        }
        return $addition;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            ActivityLog::createModel($product);
        });

        static::updated(function ($product) {
            ActivityLog::updateModel($product);
        });

        static::deleted(function ($product) {
            ActivityLog::deleteModel($product);
        });
    }
}
