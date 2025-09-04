<?php

namespace App\Models;

use App\Enums\ProductScheduleTypeEnum;
use Illuminate\Database\Eloquent\Model;

class ProductSchedule extends Model
{
    protected $fillable = [
        'type',
        'available_on',
    ];

    protected $casts = [
        'available_on' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function getIsAvailableTodayAttribute()
    {
        if ($this->type == ProductScheduleTypeEnum::PREORDER) {
            $today = today();
            $openDate = $this->available_on['open_date'];
            $closeDate = $this->available_on['close_date'];
            return (($today >= $openDate ) && ($today <= $closeDate));
        } else {
            return false;
        }
    }
}
