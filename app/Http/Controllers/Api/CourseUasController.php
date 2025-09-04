<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CourseService;

/**
 * @group Course UAS Information
 *
 * APIs for course's UAS information
 */
class CourseUasController extends Controller
{
    /**
     * [GET] Retrieve Student's UAS
     *
     * Retrieve student's UAS based on active student's course
     *
     * @queryParam limit limit data to be queried. Example: 5
     * @queryParam offset offset data to be queried. Example: 5
     * @response {
     *    "data": [
     *        {
     *            "uas_score": 90,
     *            "course_id": 2,
     *            "course_name": "Fisika"
     *        }
     *    ],
     *    "meta": {
     *        "limit": 0,
     *        "offset": 0,
     *        "total": 1
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
        $total = $courseService->countUas($request->user()->id);
        $data = $courseService->getUas(
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
