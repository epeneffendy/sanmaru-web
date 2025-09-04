<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseUser extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'course_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id','user_id','uts_score','uas_score','year_taken','semester_taken'
    ];

    protected $appends = ['course_name'];
    protected $dates = ['deleted_at'];

    public function getCourseNameAttribute(){
        return $this->course->name;
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function scopeWithUserAndActive($query, int $userId)
    {
        $courseIds = Course::active()->pluck('id');
        return $query->where('user_id', $userId)->whereIn('course_id', $courseIds);
    }
}
