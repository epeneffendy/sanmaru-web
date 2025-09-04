<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\CourseAssignmentScore;

class CourseAssignmentService
{
    public function getAssignments(int $userId, $offset, $limit)
    {
        $courseAssignmentScore = CourseAssignmentScore::withUserAndActive($userId);
        if (isset($offset)) {
            $courseAssignmentScore->offset($offset);
        }
        if (isset($limit)) {
            $courseAssignmentScore->limit($limit);
        }
        return $courseAssignmentScore->with('assignment')
            ->select('score', 'course_assignment_id')
            ->get()->makeHidden('assignment', 'course_assignment_id');
    }

    public static function countAssignments(int $userId)
    {
        return CourseAssignmentScore::withUserAndActive($userId)->count();
    }
}
