<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Traits\HasActivityLogs;
use App\Traits\ImageHandler;
use App\Contracts\ActivityLog\ModelMetadata;
use DomDocument;

class Faq extends Model implements ModelMetadata
{
    use HasActivityLogs;
    use ImageHandler;
    
    const PUBLISH = 1;
    const UNPUBLISH = 0;

    const STATUS = [
        'unpublished',
        'published'
    ];

    const CATEGORY = [
        'web-school',
        'web-PPDB'
    ];


    protected $table = 'faqs';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'answer',
        'published',
        'publish_date',
        'category'
    ];

    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
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

    public function getShortDescAttribute()
    {
        $text = strip_tags($this->content);
        $short = Str::limit($text, 100);

        return $short;
    }

    public function listCategory()
    {
        $constantWithLabel = [];
        $categories = $this::CATEGORY;
    
        foreach($categories as $key => $value) {
            $constantWithLabel[] = [
                'value' => $value,
                'name' => ucwords(str_replace("-", " ", $value))
            ];
        }

        return $constantWithLabel;
    }

    public function getPubDateAttribute()
    {
        return Carbon::parse($this->publish_date)->format('m/d/Y H:i:s');
    }

    public function getCategoryNameAttribute()
    {
        return ucwords(str_replace('-', ' ', $this->category));
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

        static::creating(function($faq) {
            $slug = Str::slug($faq->title);
            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            $faq->slug = $count ? "{$slug}-{$count}" : $slug;

            $publish_date = date('Y-m-d H:i:s', strtotime($faq->publish_date));
            $faq->publish_date = $publish_date;
        });
        static::updating(function($faq) {
            $slug = Str::slug($faq->title);
            $count = static::whereRaw("id <> {$faq->id} and slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            $faq->slug = $count ? "{$slug}-{$count}" : $slug;

            $publish_date = date('Y-m-d H:i:s', strtotime($faq->publish_date));
            $faq->publish_date = $publish_date;
        });
    }

    public function scopePublished($query)
    {
        return $query->where('published', true)
                    ->where('publish_date', '<=', Carbon::now()->toDateTimeString())
                    ->orderBy('publish_date', 'DESC');
    }

    public function getHtmlAnswerAttribute()
    {
        $content = $this->answer;
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
    
}
