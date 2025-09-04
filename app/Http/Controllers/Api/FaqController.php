<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FaqService;
use Illuminate\Http\Request;

/**
 * @group FAQ Information
 *
 * APIs for FAQs information
 */
class FaqController extends Controller
{
    /**
     * [GET] Retrieve FAQs list
     *
     * Retrieve FAQs list, can be filled with limit, and offset
     *
     * @queryParam limit limit data to be queried. Example: 5
     * @queryParam offset offset data to be queried. Example: 5
     * @queryParam published published or unpublished faq status. Example: 1 / 0
     * @response {
     *      "data": [
     *         {
     *             "title": "Lory. Alice replied thoughtfully. They have their tails.",
     *             "slug": "lory-alice-replied-thoughtfully-they-have-their-tails",
     *             "content": "aasda",
     *             "answer": "asdasd",
     *             "category": "web-school",
     *             "publish_date": "2021-07-10 00:00:00",
     *             "published": 1
     *         }
     *     ],
     *     "meta": {
     *         "limit": 0,
     *         "offset": 0,
     *         "total": 6
     *     }
     * }
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function index(Request $request, FaqService $service)
    {
        $total = $service->countFaqs();
        $data = $service->listFaqs(
            $request->input('offset'),
            $request->input('limit'),
            $request->input('published')
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

    /**
     * [GET] Retrieve Blog Category
     *
     * Retrieve blog category detail based on slug
     *
     * @urlParam slug slug name of the blog category
     *
     * @response {
     *     "data": {
     *         "name": "Kegiatan Sekolah",
     *         "slug": "kegiatan-sekolah",
     *         "is_active": 1,
     *         "total_blogs": 1
     *     }
     *}
     *
     * @response 404 {
     *    "message": "Not Found"
     *}
     *
     * @authenticated
     */
    public function show($slug, FaqService $service)
    {
        $data = $service->getFaq($slug);
        $return = array(
            'data'    => $data,
        );

        return response()->json($return, 200);
    }
}
