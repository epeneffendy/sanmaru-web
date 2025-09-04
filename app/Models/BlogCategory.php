<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasActivityLogs;
use App\Contracts\ActivityLog\ModelMetadata;

class BlogCategory extends Model implements ModelMetadata
{
    use HasActivityLogs;

    protected $fillable = [
        'name', 'slug', 'active', 'parent_id'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'blog_category_id', 'id');
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

    public function getModelMetadata()
    {
        return [
            'name' => $this->name
        ];
    }
}
