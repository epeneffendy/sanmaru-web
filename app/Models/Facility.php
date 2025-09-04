<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Facility extends Model
{
    protected $fillable = ['unit_id', 'facility_category_id', 'name', 'description'];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(FacilityCategory::class, 'facility_category_id', 'id');
    }

    public function galleries()
    {
        return $this->morphToMany('App\Models\Gallery', 'galleriable');
    }

    public function getExcerptAttribute()
    {
        return Str::limit($this->description, 100);
    }
}
