<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageHandler;

class Event extends Model
{
    use Notifiable, SoftDeletes, ImageHandler;

    const STATUS_PUBLISHED = 'published';
    const STATUS_UNPUBLISHED = 'unpublished';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'location',
        'event_time',
        'created_by',
        'last_updated_by',
        'image_path',
        'status'
    ];

    protected $appends = ['image'];

    protected $hidden = ['image_path'];

    protected $dates = ['event_time', 'deleted_at'];

    public function getImageAttribute()
    {
        return (empty($this->image_path)) ?  app('url')->to('/img/default-event.jpg') : $this->image_path;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOnGoing($query)
    {
        return $query->where('event_time', '>=', date("Y-m-d H:i:s"))->orderBy('event_time', 'asc');
    }

    public function isPublished()
    {
        return $this->status === $this::STATUS_PUBLISHED;
    }

    public function getImageUrl()
    {
        if (!$this->image_path) {
            return NULL;
        }

        return $this->getImageUrl($this->photo_path);
    }
}
