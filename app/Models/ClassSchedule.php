<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    protected $fillable = [
        'class_id',
        'course_id',
        'day',
        'start_time',
        'end_time'
    ];

    const WEEKDAYS = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];

    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    public function class()
    {
        return $this->belongsTo('App\Models\Classes', 'class_id');
    }
}
