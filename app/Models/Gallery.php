<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageHandler;
use App\Traits\HasActivityLogs;
use App\Contracts\ActivityLog\ModelMetadata;

class Gallery extends Model implements ModelMetadata
{
    use ImageHandler;
    use HasActivityLogs;

    const PUBLISH = 1;
    const UNPUBLISH = 0;

    const STATUS = [
        'unpublished',
        'published'
    ];

    protected $fillable = [
        'title',
        'description',
        'content_url',
        'user_id',
        'published',
        'unit_id'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function isPublished()
    {
        return $this->published === $this::PUBLISH;
    }

    public function getStatusAttribute()
    {
        return $this::STATUS[$this->published];
    }

    public function getPublishedLabelAttribute()
    {
        $attributes = [
            'class' => 'danger',
            'label' => 'Unpublished'
        ];

        if ($this->published)
            $attributes = [
                'class' => 'success',
                'label' => 'Published'
            ];

        return "<span class='label label-{$attributes['class']}'>{$attributes['label']}</span>";
    }

    public function scopePublished($query)
    {
        return $query->where('published', true)->orderBy('updated_at', 'DESC');
    }

    public function getContentImage()
    {
        return (!empty($this->content_url)) ? $this->getImageUrl($this->content_url) : 'https://placehold.it/180x135';
    }

    public function getContentImageUrl()
    {
        return (empty($this->content_url)) ?  'https://placehold.it/180x135': $this->getImageUrl($this->content_url);
    }

    public function getModelMetadata()
    {
        return [
            'title' => $this->title
        ];
    }
}
