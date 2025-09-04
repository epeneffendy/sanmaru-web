<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'bills';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','due_date','amount','category_id'
    ];

    protected $dates = ['deleted_at'];

    public function scopeNotDeleted($query)
    {
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'scheduleable');
    }
}
