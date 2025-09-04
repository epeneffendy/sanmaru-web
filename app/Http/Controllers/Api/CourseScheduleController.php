<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ScheduleService;

/**
 * @group Course Schedule Information
 *
 * APIs for course's schedule information
 */
class CourseScheduleController extends Controller
{
    /**
     * [GET] Retrieve Course Schedule
     *
     * Retrieve course schedule per day
     *
     * @urlParam day day name of the week, in english
     *
     * @response {
     *    "data": [
     *        {
     *            "start_time": "08:00:00",
     *            "day": "monday",
     *            "name": "Matematika"
     *        },
     *        {
     *            "start_time": "09:00:00",
     *            "day": "monday",
     *            "name": "Fisika"
     *        }
     *    ],
     *    "meta": {
     *        "total": 2
     *    }
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function index(Request $request, $day, ScheduleService $scheduleService)
    {
        $total = $scheduleService->countCourse($request->user()->id, $day);
        $data = $scheduleService->getCourse($request->user()->id, $day);
        $meta = array(
            'total' => $total
        );
        $return = array(
            'data'    => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }
}
