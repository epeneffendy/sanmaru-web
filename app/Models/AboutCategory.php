<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasActivityLogs;
use Illuminate\Support\Str;
use App\Contracts\ActivityLog\ModelMetadata;

class AboutCategory extends Model implements ModelMetadata
{
    use HasActivityLogs;
    
    protected $fillable = [
        'name', 'slug', 'active', 'order'
    ];

    public function getRouteKeyName() 
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function getActiveLabelAttribute()
    {
        $attributes = [
            'class' => 'danger',
            'label' => 'Inactive'
        ];

        if ($this->active)
            $attributes = [
                'class' => 'success',
                'label' => 'Active'
            ];
        
        return "<span class='label label-{$attributes['class']}'>{$attributes['label']}</span>";
    }

    public function abouts()
    {
        return $this->hasMany(About::class)->orderBy('publish_date', 'desc');
    }

    public function aboutsPublished()
    {
        return $this->hasMany(About::class)->published();
    }

    public function getModelMetadata()
    {
        return [
            'name' => $this->name
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($aboutCategory) {
            $slug = Str::slug($aboutCategory->name);
            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            $aboutCategory->slug = $count ? "{$slug}-{$count}" : $slug;
        });
        static::updating(function($aboutCategory) {
            $slug = Str::slug($aboutCategory->name);
            $count = static::whereRaw("id <> {$aboutCategory->id} and slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            $aboutCategory->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }
}
