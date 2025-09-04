<?php

namespace App\Services;

use App\Models\Attendance;

class AttendanceService
{
    public function count($userId, $params)
    {
        $attendances = Attendance::where('user_id', $userId);
        if (isset($params['start_date'])) {
            $attendances->where('date', '<=', $params['start_date']);
        }
        return $attendances->count();
    }

    public function list(int $userId, $params)
    {
        $attendances = Attendance::where('user_id', $userId)->orderBy('date', 'desc');

        if (isset($params['offset'])) {
            $attendances->offset($params['offset']);
        }
        if (isset($params['limit'])) {
            $attendances->limit($params['limit']);
        }
        if (isset($params['start_date'])) {
            $attendances->where('date', '<=', $params['start_date']);
        }

        return $attendances->get();
    }
}
