<?php

namespace App\Models;

use App\Helpers\PriceHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ImageHandler;
use App\Contracts\ActivityLog\ModelMetadata;

class Product extends Model implements ModelMetadata
{
    use SoftDeletes, ImageHandler;

    /**
     * @var string
     */
    protected $table = 'products';

    const STATUS_PUBLISHED = 'published';
    const STATUS_UNPUBLISHED = 'unpublished';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'weight',
        'merk',
        'level',
        'unit_id',
        'status',
        'type_id',
        'type_name',
        'category_id',
        'category_name',
        'stand_id',
        'stand_name',
        'image_path',
        'description',
        'vendor_id'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = ['image_path'];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        return (empty($this->image_path)) ?  app('url')->to('/img/default-seragam.jpg') : $this->getImageUrl($this->image_path);
    }

    public function getPriceSiswaRangeAttribute()
    {
        $prices = $this->details;

        $min = $prices->min('price_siswa');
        $max = $prices->max('price_siswa');

        if ($min === $max) {
            return PriceHelper::rupiah($min);
        }

        return PriceHelper::rupiah($min).' - '.PriceHelper::rupiah($max);
    }

    public function getPricePpdbRangeAttribute()
    {
        $prices = $this->details;

        $min = $prices->min('price_ppdb');
        $max = $prices->max('price_ppdb');

        if ($min === $max) {
            return PriceHelper::rupiah($min);
        }

        return PriceHelper::rupiah($min).' - '.PriceHelper::rupiah($max);
    }

    public function getTotalStockAttribute()
    {
        $details = $this->details;

        return $details->sum('stock');
    }

    public function scopePublished($query)
    {
        return $query->where('status', $this::STATUS_PUBLISHED);
    }

    public function isPublished()
    {
        return $this->status === $this::STATUS_PUBLISHED;
    }

    /* Relations */
    public function type()
    {
        return $this->hasOne('App\Models\ProductType', 'id', 'type_id');
    }

    public function category()
    {
        return $this->hasOne('App\Models\ProductCategory', 'id', 'category_id');
    }

    public function stand()
    {
        return $this->hasOne('App\Models\ProductStand', 'id', 'stand_id');
    }

    public function details()
    {
        return $this->hasMany('App\Models\ProductDetail', 'product_id', 'id');
    }

    public function units()
    {
        return $this->belongsToMany('App\Models\Unit', 'product_units', 'product_id', 'unit_id');
    }

    public function productUnits()
    {
        return $this->hasMany('App\Models\ProductUnit', 'product_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }

    public function schedule()
    {
        return $this->hasOne('App\Models\ProductSchedule', 'product_id', 'id');
    }

    public function scopeWithCategoryAndActive($query, $categorySlug)
    {
        $categoryId = ProductCategory::where('slug', $categorySlug)->pluck('id');
        return Product::published()->where('category_id', $categoryId)->exclude(['type_id', 'category_id', 'status', 'deleted_at']);
    }

    public function scopeExclude($query, $value = array())
    {
        return $query->select(array_diff($this->fillable, (array) $value));
    }

    //
    public function syncDetails($details)
    {
        $ids = [];
        foreach ($details as $detail) {
            if ($detail['id']) {
                $productDetail = ProductDetail::find($detail['id']);
            } else {
                $productDetail = ProductDetail::firstOrNew([
                    'product_id' => $this->id,
                    'size' => $detail['size']
                ]);
            }
            if (!isset($detail['product_id'])) {
                $detail['product_id'] = $this->id;
            }
            $productDetail->fill($detail);
            $productDetail->save();
            $ids[] = $productDetail->id;
        }

        $cannotDeletedDetails = collect();
        ProductDetail::where('product_id', $this->id)
                ->whereNotIn('id', $ids)
                ->get()
                ->each(function($detail) use($cannotDeletedDetails) {
                    $isExists = ProductOrderDetail::whereHas('productDetail', function ($query) use ($detail) {
                            $query->where('id', $detail->id);
                        })->exists() || CartDetail::whereHas('product_detail', function ($query) use ($detail) {
                            $query->where('id', $detail->id);
                        })->exists();
                    if ($isExists) {
                        $cannotDeletedDetails->push($detail);
                    } else {
                        $detail->delete();
                    }
                });
        // if ($cannotDeletedDetails) {
        //     $errorMessage = "Maaf, produk details dengan size ";
        //     foreach ($cannotDeletedDetails as $value) {
        //         $errorMessage .= $value->size;
        //         if ($value == $cannotDeletedDetails->last()) {
        //             $errorMessage .= ' ';
        //         } else {
        //             $errorMessage .= ', ';
        //         }
        //     }
        //     $errorMessage .= "karena masih ada pesanan aktif produk terkait.";
        //     return collect([
        //         'errorOccurred' => true,
        //         'message' => $errorMessage,
        //     ]);
        // } else {
        //     return [
        //         'errorOccurred' => false,
        //     ];
        // }
    }

    public function syncUnits($units)
    {
        $ids = [];
        foreach ($units as $unit) {
            $productUnit = ProductUnit::firstOrCreate([
                'product_id' => $this->id,
                'unit_id' => $unit
            ]);
            $ids[] = $productUnit->id;
        }

        ProductUnit::where('product_id', $this->id)->whereNotIn('id', $ids)->delete();
    }

    public function getModelMetadata()
    {
        return [
            'name' => $this->name,
        ];
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

    public function scopeByType($query, $type)
    {
        return $query->where(function ($q) use ($type) {
            $q->whereHas('type', function ($qType) use ($type) {
                $qType->whereType($type);
            });
            $q->orWhereHas('category', function ($qType) use ($type) {
                $qType->whereType($type);
            });
        });
    }
}
