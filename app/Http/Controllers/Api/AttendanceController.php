<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceIndexRequest;
use App\Services\AttendanceService;
use App\Transformer\AttendanceTransformer;
use Illuminate\Http\Request;

/**
 * @group Attendance Information
 *
 * APIs for user's attendance information
 */
class AttendanceController extends Controller
{
    /**
     * [GET] Retrieve Attendance
     *
     * Retrieve users attendances with 3 types: present (hadir), absent (tidak masuk), onleave (izin).
     *
     * @queryParam limit limit data to be queried. Example: 5. Default: 20.
     * @queryParam offset offset data to be queried. Example: 5
     * @queryParam start_date start date to query attendance data. Example: 2020-03-11
     * @response {
     *  "data": [
     *      {
     *          "date": "2020-03-28",
     *          "type": "present",
     *          "reason": null
     *      },
     *      {
     *          "date": "2020-03-27",
     *          "type": "onleave",
     *          "reason": "perut sakit"
     *      },
     *      {
     *          "date": "2020-03-26",
     *          "type": "absent",
     *          "reason": null
     *      },
     *      {
     *          "date": "2020-03-25",
     *          "type": "present",
     *          "reason": null
     *      },
     *      {
     *          "date": "2020-03-24",
     *          "type": "present",
     *          "reason": null
     *      }
     *  ],
     *  "meta": {
     *      "limit": 20,
     *      "offset": 0,
     *      "total": 5
     *  }
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function index(AttendanceIndexRequest $request, AttendanceService $attendanceService)
    {
        $params = $request->validated();
        $total = $attendanceService->count($request->user()->id, $params);
        $rawData = $attendanceService->list(
            $request->user()->id,
            $params
        );

        $data = fractal($rawData, new AttendanceTransformer)->toArray();

        $meta = array(
            'limit' => $params['limit'],
            'offset' => intval($request->input('offset')),
            'total' => $total
        );
        $return = $data + array(
            'meta' => $meta
        );

        return response()->json($return, 200);
    }
}
