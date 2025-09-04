<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Classes;
use App\Models\Course;
use App\Models\ClassSchedule;
use App\Models\Unit;
use Carbon\Carbon;

class ClassScheduleService 
{
	public function generateIndexData($nav)
	{
		$classSchedules = ClassSchedule::with('course', 'class', 'class.unit')
							->get();
		return [
			'nav' => $nav,
			'classSchedules' => $classSchedules
		];
	}

	public function generateAddingData($nav)
	{
		$classSchedule = new ClassSchedule;

		return [
			'nav' => $nav,
			'classSchedule' => $classSchedule,
			'weekDays' => ClassSchedule::WEEKDAYS,
			'units' => Unit::all(),
			'courses' => Course::active()->get(),
			'classes' => Classes::all(),
		];
	}

	public function generateEditableData($id, $nav)
	{
		$classSchedule = ClassSchedule::with('course', 'class', 'class.unit')
							->where('id', $id)->firstOrFail();
		return [
			'status' => 'edit',
			'classSchedule' => $classSchedule,
			'weekDays' => ClassSchedule::WEEKDAYS,
			'calendarData' => $this->calendarData($classSchedule->class_id),
			'nav' => $nav,
			'units' => Unit::all(),
			'courses' => Course::active()->get(),
			'classes' => Classes::all(),
		];
	}

	public function params($params)
	{
		return $params;
	}

	public function create($params)
	{
		$params = $this->params($params);
        $classSchedule = ClassSchedule::create($params);
		return $classSchedule;
	}

	public function update($id, $params)
	{
		$classSchedule = ClassSchedule::where('id', $id)->firstOrFail();
		$params = $this->params($params);

		$classSchedule->fill($params);
        $classSchedule->save();
        return $classSchedule;
	}

	public function delete($id)
	{
		$classSchedule = ClassSchedule::where('id', $id)->firstOrFail();
		return $classSchedule->delete();
	}

	public function unitClass($unitId)
	{
		$classes = Classes::select('id', 'name')
					->where('unit_id', $unitId)
					->get();

		return $classes;
	}

	public function calendarData($classId)
    {
        $calendarData = [];
        $timeRange = $this->timeRange("07:00:00", "17:00:00");
		$schedules = ClassSchedule::where('class_id', $classId)
                            ->with('class', 'course')
                            ->get();

		$weekDays = ClassSchedule::WEEKDAYS;

        if (!$schedules) {
            return $calendarData;
        }

        foreach ($timeRange as $time) {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $calendarData[$timeText] = [];

            foreach ($weekDays as $key => $day) {
				$schedule = $schedules->where('day', $day)->where('start_time', $time['start'])->first();

                if ($schedule) {
                    array_push($calendarData[$timeText], [
                        'class_name'   => $schedule->class->name,
                        'course_name' => $schedule->course->name,
                        'rowspan'      => Carbon::parse($schedule->start_time)->diffInMinutes($schedule->end_time) / 15 ?? ''
                    ]);
                } else if (!$schedules->where('day', $day)->where('start_time', '<', $time['start'])->where('end_time', '>=', $time['end'])->count()) {
                    array_push($calendarData[$timeText], 1);
                } else {
                    array_push($calendarData[$timeText], 0);
                }
            }
        }

        return [
			'calendarData' => $calendarData,
			'weekDays' => $weekDays
		];
    }

	private function timeRange($from, $to)
    {
        $time = Carbon::parse($from);
        $timeRange = [];

        while ($time->format("H:i:s") <= $to) {
            array_push($timeRange, [
                'start' => $time->format("H:i:s"),
                'end' => $time->addMinutes(15)->format("H:i:s")
            ]);
        }

        return $timeRange;
    }

	public function getSchedules()
	{
		$user = Student::where('user_id', request()->user()->id)->first();
		if (!$user || ($user && !$user->class_id)) {
			return false;
		}

		$datas = ClassSchedule::where('class_id', $user->class_id)
			->with('course', 'class')
			->orderBy('start_time', 'asc')
			->orderBy('day', 'asc');

        $collect = collect();
        foreach ($datas->get() as $data) {
            $collect->push($this->_schedule($data));
        }

        return $collect;
	}

    private function _schedule($data)
    {
        if (!$data) {
            return false;
        }

        return [
			'class' => $data->class->name,
			'course' => $data->course->name,
			'day' => $data->day,
			'start_time' => $data->start_time,
			'end_time' => $data->end_time
        ];
    }
}