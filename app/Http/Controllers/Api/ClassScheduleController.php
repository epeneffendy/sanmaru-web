<?php

namespace App\Http\Controllers\Api;

use App\Services\ClassScheduleService;
use App\Http\Controllers\Controller;
use Response;

/**
 * @group Class Schedule Information
 *
 * APIs for Class Schedule
 */
class ClassScheduleController extends Controller
{
    /**
     * [GET] Retrieve Class Schedule
     *
     * Retrieve class schedule by user
     *
     * @response 200 {
     *     "data": [
     *         {
     *             "class": "KB A CATHERINE",
     *             "course": "Matematika",
     *             "day": "monday",
     *             "start_time": "08:00:00",
     *             "end_time": "10:00:00"
     *         },
     *         {
     *             "class": "KB A CATHERINE",
     *             "course": "Matematika",
     *             "day": "tuesday",
     *             "start_time": "10:00:00",
     *             "end_time": "12:00:00"
     *         }
     *     ],
     *     "meta": {
     *         "status": 200
     *     }
     * }
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function index(ClassScheduleService $service)
    {
        $data = $service->getSchedules();

        $meta = array(
            'status' => 200
        );

        $return = array(
            'data' => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }
}