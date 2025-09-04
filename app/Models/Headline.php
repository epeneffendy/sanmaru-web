<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageHandler;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Traits\HasActivityLogs;
use App\Contracts\ActivityLog\ModelMetadata;

class Headline extends Model implements ModelMetadata
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
        'is_unit',
        'unit_id',
        'type',
        'published',
        'content_url',
        'color_overlay'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function isPublished()
    {
        return $this->published === $this::PUBLISH;
    }

    public function getStatusAttribute()
    {
        return $this::STATUS[$this->published];
    }

    public function isUnit()
    {
        return $this->is_unit === true;
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

    public function getImageUrlAttribute()
    {
        return (!empty($this->content_url)) ? $this->getImageUrl($this->content_url) : 'https://placehold.it/180x135';
    }

    public function getVideoUrlAttribute()
    {
        return (!empty($this->content_url)) ? 'https://www.youtube.com/watch?v=' . $this->content_url : null;
    }

    public function getPreviewAttribute()
    {
        if ($this->type === "image") {
            $url = $this->getImageUrl($this->content_url);
            return "<img class='img-responsive' src='". $url ."'>";
        }

        if ($this->type === "video") {
            $url = 'https://www.youtube.com/embed/' . $this->content_url ;
            return "<iframe class='embed-responsive-item' src='". $url ."' allowfullscreen></iframe>";
        }

        return null;
    }

    public function getUrl()
    {
        $url = $this->content_url;

        if ($this->type === "image")
            $url = $this->getImageUrl($this->content_url);

        if ($this->type === "video")
            $url = 'https://www.youtube.com/embed/' . $this->content_url ;

        return $url;
    }

    public function scopePublished($query)
    {
        return $query->where('published', true)
                    ->orderBy('updated_at', 'DESC');
    }

    public function getModelMetadata()
    {
        $metadata = [];
        if ($this->unit) {
            $metadata = array_merge($metadata, [
                'unit_name' => $this->unit->name
            ]);
        }

        return $metadata;
    }
}
