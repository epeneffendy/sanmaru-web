<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasActivityLogs;
use App\Contracts\ActivityLog\ModelMetadata;

class VoiceOfSanmar extends Model implements ModelMetadata
{
    use HasActivityLogs;
    
    protected $fillable = [
        'title',
        'content_url'
    ];

    public function getVideoUrlAttribute()
    {
        return (!empty($this->content_url)) ? 'https://www.youtube.com/watch?v=' . $this->content_url : null;
    }

    public function getEmbedUrlAttribute()
    {
        return (!empty($this->content_url)) ? 'https://www.youtube.com/embed/' . $this->content_url : null;
    }

    public function getPreviewAttribute()
    {
        if ($this->content_url) {
            $url = 'https://www.youtube.com/embed/' . $this->content_url ;
            return "<iframe class='embed-responsive-item' src='". $url ."' allowfullscreen></iframe>";
        }

        return null;
    }

    public function getModelMetadata()
    {
        return [
            'title' => $this->title
        ];
    }
}
