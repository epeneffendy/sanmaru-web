<?php

namespace App\Models;

use App\Traits\ImageHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DomDocument;
use App\Traits\HasActivityLogs;
use App\Contracts\ActivityLog\ModelMetadata;

class Scholarship extends Model implements ModelMetadata
{
    use ImageHandler;
    use HasActivityLogs;
    
    const PUBLISH = 1;
    const UNPUBLISH = 0;

    const STATUS = [
        'unpublished',
        'published'
    ];
    protected $table = 'scholarships';
    protected $fillable = [
        'name',
        'description',
        'published',
        'publish_date',
        'is_unit',
        'unit_id'
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

    public function getShortDescAttribute()
    {
        $text = strip_tags($this->description);
        $short = Str::limit($text, 100);

        return $short;
    }

    public function getPubDateAttribute()
    {
        return Carbon::parse($this->publish_date)->format('m/d/Y H:i:s');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($scholarship) {
            $publish_date = date('Y-m-d H:i:s', strtotime($scholarship->publish_date));
            $scholarship->publish_date = $publish_date;
        });
        static::updating(function($scholarship) {
            $publish_date = date('Y-m-d H:i:s', strtotime($scholarship->publish_date));
            $scholarship->publish_date = $publish_date;
        });
    }

    public function getHtmlDescriptionAttribute()
    {
        $description = $this->description;
        $domHtml = new DOMDocument();
        libxml_use_internal_errors(true);
        $domHtml->loadHTML($description);
        $imgTags = $domHtml->getElementsByTagName('img');

        foreach($imgTags as $img) {
            $src = $img->getAttribute('src');
            
            if (Str::startsWith($src, 'content_image')) {
                $description = str_replace($src, $this->getImageUrl($src), $description);    
            }
        }
        libxml_clear_errors();
        return $description;
    }

    public function getModelMetadata()
    {
        return [
            'name' => $this->name
        ];
    }
}
