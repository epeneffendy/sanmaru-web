<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];

    public function faqs()
    {
        return $this->morphedByMany('App\Models\Faq', 'taggable');
    }
}
