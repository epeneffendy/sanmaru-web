<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\ImageHandler;
use Carbon\Carbon;
use DomDocument;
use App\Traits\HasActivityLogs;
use App\Contracts\ActivityLog\ModelMetadata;

class SchoolLife extends Model implements ModelMetadata
{
    use ImageHandler;
    use HasActivityLogs;

    protected $table = 'school_lifes';

    protected $fillable = [
        'title',
        'short_desc',
        'slug',
        'category_id',
        'content',
        'published',
        'user_id',
        'featured_image',
        'publish_date'
    ];

    public function category()
    {
        return $this->hasOne(SchoolLifeCategory::class, 'id', 'category_id');
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

    public function scopePublished($query)
    {
        return $query->where('published', true)
                    ->where('publish_date', '<=', Carbon::now()->toDateTimeString())
                    ->orderBy('publish_date', 'DESC');
    }

    public function getModelMetadata()
    {
        return [
            'title' => $this->title
        ];
    }
}
