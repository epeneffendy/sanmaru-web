<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageHandler;
use App\Traits\HasActivityLogs;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DomDocument;
use App\Contracts\ActivityLog\ModelMetadata;

class About extends Model implements ModelMetadata
{
    use ImageHandler;
    use HasActivityLogs;

    protected $fillable = [
        'title',
        'short_desc',
        'slug',
        'about_category_id',
        'content',
        'published',
        'user_id',
        'featured_image',
        'publish_date',
        'unit_id',
    ];

    public function getRouteKeyName() 
    {
        return 'slug';
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(AboutCategory::class, 'about_category_id');
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

    public function getFeaturedImageUrl()
    {
        return (empty($this->featured_image)) ?  'https://placehold.it/180x135': $this->getImageUrl($this->featured_image);
    }

    public function getPubDateAttribute()
    {
        return Carbon::parse($this->publish_date)->format('m/d/Y H:i:s');
    }

    public function scopePublished($query)
    {
        return $query->where('published', true)
                    ->where('publish_date', '<=', Carbon::now()->toDateTimeString())
                    ->orderBy('publish_date', 'DESC');
    }

    public function getHtmlContentAttribute()
    {
        $content = $this->content;
        $domHtml = new DOMDocument();
        libxml_use_internal_errors(true);
        $domHtml->loadHTML($content);
        $imgTags = $domHtml->getElementsByTagName('img');

        foreach($imgTags as $img) {
            $src = $img->getAttribute('src');
            
            if (Str::startsWith($src, 'content_image')) {
                $content = str_replace($src, $this->getImageUrl($src), $content);    
            }
        }
        libxml_clear_errors();
        return $content;
    }

    public function getModelMetadata()
    {
        return [
            'title' => $this->title
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($about) {
            $slug = Str::slug($about->title);
            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            $about->slug = $count ? "{$slug}-{$count}" : $slug;
        });
        static::updating(function($about) {
            $slug = Str::slug($about->title);
            $count = static::whereRaw("id <> {$about->id} and slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            $about->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }
}
