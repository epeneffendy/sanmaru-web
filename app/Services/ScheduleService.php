<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Schedule;

class ScheduleService
{
    public function getCourse(int $user_id, $day) {
        return Schedule::userCourse($user_id, $day)->with('scheduleable')->get();
    }

    public function countCourse(int $user_id, $day) {
        return Schedule::userCourse($user_id, $day)->count();
    }
}
