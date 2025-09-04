<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasActivityLogs;
use App\Contracts\ActivityLog\ModelMetadata;

class SchoolLifeCategory extends Model implements ModelMetadata
{
    use HasActivityLogs;
    
    protected $fillable = [
        'name', 'slug', 'active', 'order'
    ];

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

    public function schoolLifes()
    {
        return $this->hasMany(SchoolLife::class, 'category_id')->orderBy('publish_date', 'desc');
    }

    public function getModelMetadata()
    {
        return [
            'name' => $this->name
        ];
    }
}
