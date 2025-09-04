<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CourseAssignmentService;

/**
 * @group Course Assignment Information
 *
 * APIs for course assignment information
 */
class CourseAssignmentController extends Controller
{
    /**
     * [GET] Retrieve Assignments
     *
     * Retrieve student's assignments based on active student's course
     *
     * @queryParam limit limit data to be queried. Example: 5
     * @queryParam offset offset data to be queried. Example: 5
     * @response {
     *    "data": [
     *        {
     *            "score": 80,
     *            "course_assignment_id": 1,
     *            "assignment_name": "Tugas 1 Matematika"
     *        },
     *        {
     *            "score": 85,
     *            "course_assignment_id": 2,
     *            "assignment_name": "Tugas 1 Fisika"
     *        },
     *        {
     *            "score": 70,
     *            "course_assignment_id": 3,
     *            "assignment_name": "Tugas 2 Fisika"
     *        }
     *    ],
     *    "meta": {
     *        "limit": 0,
     *        "offset": 0,
     *        "total": 3
     *    }
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function index(Request $request, CourseAssignmentService $courseAssignmentService)
    {
        $total = $courseAssignmentService->countAssignments($request->user()->id);
        $data = $courseAssignmentService->getAssignments(
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
