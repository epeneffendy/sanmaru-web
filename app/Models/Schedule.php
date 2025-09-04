<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'schedules';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'scheduleable_id', 'scheduleable_type', 'day', 'time'
    ];

    protected $dates = ['deleted_at'];
    protected $appends = ['name'];

    protected $hidden = [
        'scheduleable_type','scheduleable_id','scheduleable'
    ];

    public function getNameAttribute(){
        return $this->scheduleable->name;
    }


    public function scheduleable()
    {
        return $this->morphTo();
    }

    public function scopeCourse($query)
    {
        return $query->where('scheduleable_type', 'App\Models\Course');
    }

    public function scopeUserCourse($query, int $userId, $day)
    {
        $courseUserIds = CourseUser::where('user_id', $userId)->pluck('course_id');
        $courseId = Course::active()->whereIn('id', $courseUserIds)->pluck('id');
        return $query->where('scheduleable_type', 'App\Models\Course')
                ->select('scheduleable_id','scheduleable_type','start_time','day')
                ->where('day', $day)->whereIn('scheduleable_id',$courseId)->orderBy('start_time','asc');
    }
}
