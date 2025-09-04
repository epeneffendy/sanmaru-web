<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Uniform extends Model
{
    use Notifiable, SoftDeletes;

    const STATUS_PUBLISHED = 'published';
    const STATUS_UNPUBLISHED = 'unpublished';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'level',
        'size',
        'gender',
        'prize_basic',
        'prize_male',
        'prize_female',
        'brand',
        'unit_id',
        'status'
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopePublished($query)
    {
        return $query->where('status', $this::STATUS_PUBLSHED);
    }

    public function isPublished()
    {
        return $this->status === $this::STATUS_PUBLISHED;
    }

    public function unit()
    {
        return $this->hasOne(__NAMESPACE__.'\Unit', 'id', 'unit_id');
    }
}
