<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use Notifiable, SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * @var string
     */
    protected $table = 'courses';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'unit_id',
    ];
    protected $dates = ['deleted_at'];

    public function scopeNotDeleted($query)
    {
    }

    /**
     * @param $query
     * @return mixed
     */

    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'scheduleable');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function isActive()
    {
        return $this->status === $this::STATUS_ACTIVE;
    }

    public function scopeActive($query)
    {
        return $query->where('status', $this::STATUS_ACTIVE);
    }
}
