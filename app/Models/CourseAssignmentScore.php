<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseAssignmentScore extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'course_assignment_scores';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_assignment_id', 'user_id', 'score'
    ];

    protected $appends = ['assignment_name'];
    protected $dates = ['deleted_at'];

    public function getAssignmentNameAttribute(){
        return $this->assignment->name;
    }

    public function assignment() {
        return $this->belongsTo('App\Models\CourseAssignment','course_assignment_id');
    }

    public function scopeWithUserAndActive($query, int $userId)
    {
        $courseIds = Course::active()->pluck('id');
        $courseAssignmentIds = CourseAssignment::whereIn('course_id', $courseIds)->pluck('id');
        return $query->whereIn('course_assignment_id', $courseAssignmentIds)
            ->where('user_id', $userId);
    }
}
