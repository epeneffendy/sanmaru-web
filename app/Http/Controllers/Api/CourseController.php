<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CourseService;

/**
 * @group Course Information
 *
 * APIs for course information
 */
class CourseController extends Controller
{
    /**
     * [GET] Retrieve Courses
     *
     * Retrieve student's active courses
     *
     * @queryParam limit limit data to be queried. Example: 5
     * @queryParam offset offset data to be queried. Example: 5
     * @response {
     *    "data": [
     *        {
     *            "name": "Matematika",
     *            "code": "MX120"
     *        },
     *        {
     *            "name": "Fisika",
     *            "code": "FX120"
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
        $meta = array(
            'limit' => intval($request->input('limit')),
            'offset' => intval($request->input('offset')),
            'total' => $courseService->countCourses($request->user()->id)
        );
        $data = $courseService->getCourses(
            $request->user()->id,
            $request->input('offset'),
            $request->input('limit')
        );
        $return = array(
            'data'    => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }
}
