<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageHandler;
use App\Traits\HasActivityLogs;

class Testimonial extends Model
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
        'subject',
        'photo_path',
        'content',
        'student_id',
        'published',
        'unit_id'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
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

    public function getPhotoPathUrl($default = null)
    {
        return (!empty($this->photo_path)) ? $this->getImageUrl($this->photo_path) : ($default?:'https://placehold.it/180x135');
    }

    public function getPreviewAttribute()
    {
        $url = 'https://placehold.it/180x135';

        if (!empty($this->photo_path)) {
            $url = $this->getImageUrl($this->photo_path);
        }

        return "<img class='img-responsive img-circle' style='width:50px;height:50px;' src='". $url ."'>";
    }

    public function scopePublished($query)
    {
        return $query->where('published', true)
                    ->orderBy('updated_at', 'DESC');
    }
}
