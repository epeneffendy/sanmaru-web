<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\ImageHandler;
use App\Traits\HasActivityLogs;
use DomDocument;
use App\Contracts\ActivityLog\ModelMetadata;

class CampusUnit extends Model implements ModelMetadata
{
    use ImageHandler;
    use HasActivityLogs;

    protected $table = "campus_units";

    protected $fillable = [
        'campus_id', 
        'unit_id', 
        'permalink',
        'image_path',
        'image_landscape_path',
        'image_potrait_path',
        'image_path',
        'about',
        'keunggulan',
        'sambutan'
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function getImagePathUrl()
    {
        return (empty($this->image_path)) ? 'https://placehold.it/180x135' : $this->getImageUrl($this->image_path);
    }

    public function getImageLandscapePathUrl()
    {
        return (empty($this->image_landscape_path)) ? 'https://placehold.it/180x135' : $this->getImageUrl($this->image_landscape_path);
    }

    public function getImagePotraitPathUrl()
    {
        return (empty($this->image_potrait_path)) ? 'https://placehold.it/180x135' : $this->getImageUrl($this->image_potrait_path);
    }

    public function getHtmlAboutAttribute()
    {
        $content = $this->about;
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

    public function getHtmlKeunggulanAttribute()
    {
        $content = $this->keunggulan;
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
            'campus_name' => $this->campus->name,
            'unit_name' => $this->unit->name
        ];
    }
}
