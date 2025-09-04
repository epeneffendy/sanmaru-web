<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CourseService;

/**
 * @group Course UTS Information
 *
 * APIs for course UTS information
 */
class CourseUtsController extends Controller
{
    /**
     * [GET] Retrieve students UTS
     *
     * Retrieve student's UTS based on active student's course
     *
     * @queryParam limit limit data to be queried. Example: 5
     * @queryParam offset offset data to be queried. Example: 5
     * @response {
     *    "data": [
     *        {
     *            "uts_score": 70,
     *            "course_id": 1,
     *            "course_name": "Matematika"
     *        },
     *        {
     *            "uts_score": 80,
     *            "course_id": 2,
     *            "course_name": "Fisika"
     *        }
     *    ],
     *    "meta": {
     *        "limit": 0,
     *        "offset": 0,
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
    public function index(Request $request, CourseService $courseService)
    {
        $total = $courseService->countUts($request->user()->id);
        $data = $courseService->getUts(
            $request->user()->id,
            $request->input('offset'),
            $request->input('limit')
        );
        $meta = array(
            'limit' => intval($request->input('limit')),
            'offset' => intval($request->input('offset')),
            'total' => $total
        );
        $return = array(
            'data'    => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }
}
