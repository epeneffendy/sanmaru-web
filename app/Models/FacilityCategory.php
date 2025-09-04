<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FacilityCategory extends Model
{
    protected $fillable = ['name', 'section'];

    public function facilities()
    {
        return $this->hasMany(Facility::class, 'facility_category_id', 'id');
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }
}
