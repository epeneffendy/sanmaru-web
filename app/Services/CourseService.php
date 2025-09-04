<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseUser;

class CourseService
{
    public function getIndex($perPage)
    {
        return Course::with('unit')->paginate($perPage);
    }

    public function filter($unit_id, $name)
    {
        $query = Course::where('name', 'like', '%' . $name . '%');
        if ($unit_id) {
            $query = $query->where('unit_id', $unit_id);
        }
        return $query;
    }

    public function create($params)
    {
        return Course::create($params);
    }

    public function update($id, $params)
    {
        $course = Course::findOrFail($id);
        $course->fill($params);
        return $course->save();
    }

    public function delete($id)
    {
        $course = Course::findOrFail($id);
        return $course->delete();
    }

    public function getCourses(int $userId, $offset, $limit)
    {
        $courseUsers = CourseUser::withUserAndActive($userId);
        if (isset($offset)) {
            $courseUsers->offset($offset);
        }
        if (isset($limit)) {
            $courseUsers->limit($limit);
        }
        return Course::select('name', 'code')->find($courseUsers->pluck('course_id'));
    }

    public function countCourses(int $userId)
    {
        return CourseUser::withUserAndActive($userId)->count();
    }

    public function getUts(int $userId, $offset, $limit)
    {
        $courseUsers = CourseUser::withUserAndActive($userId)->whereNotNull('uts_score');
        if (isset($offset)) {
            $courseUsers->offset($offset);
        }
        if (isset($limit)) {
            $courseUsers->limit($limit);
        }

        return  $courseUsers->select('uts_score', 'course_id')->with('course')->get()->makeHidden('course', 'course_id');
    }

    public function countUts(int $userId)
    {
        return CourseUser::withUserAndActive($userId)->whereNotNull('uts_score')->count();
    }

    public function getUas(int $userId, $offset, $limit)
    {
        $courseUsers = CourseUser::withUserAndActive($userId)->whereNotNull('uas_score');
        if (isset($offset)) {
            $courseUsers->offset($offset);
        }
        if (isset($limit)) {
            $courseUsers->limit($limit);
        }
        return  $courseUsers->select('uas_score', 'course_id')->with('course')->get()->makeHidden('course', 'course_id');
    }

    public function countUas(int $userId)
    {
        return CourseUser::withUserAndActive($userId)->whereNotNull('uas_score')->count();
    }

    public function toggleStatus($id)
    {
        $course = Course::findOrFail($id);
        $course->status = $course->isActive() ? Course::STATUS_INACTIVE : Course::STATUS_ACTIVE;
        return $course->save();
    }

    public function generateEditableData($id, $nav)
    {
        $course = Course::findOrFail($id);
        return array(
            'course' => $course,
            'nav' => $nav,
            'method' => 'edit'
        );
    }
}
