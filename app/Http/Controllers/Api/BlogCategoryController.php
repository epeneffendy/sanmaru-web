<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BlogCategoryService;
use Illuminate\Http\Request;

/**
 * @group Blog Category Information
 *
 * APIs for Blogs Category information
 */
class BlogCategoryController extends Controller
{
    /**
     * [GET] Retrieve Blog Category list
     *
     * Retrieve blog category list, can be filled with limit, and offset
     *
     * @queryParam limit limit data to be queried. Example: 5
     * @queryParam offset offset data to be queried. Example: 5
     * @response {
     *     "data": [
     *         {
     *             "name": "Kegiatan Sekolah",
     *             "slug": null,
     *             "total_blogs": 1
     *         },
     *         {
     *             "name": "Pengumuman",
     *             "slug": null,
     *             "total_blogs": 2
     *         },
     *         {
     *             "name": "Mata Pelajaran",
     *             "slug": null,
     *             "total_blogs": 0
     *         },
     *         {
     *             "name": "Seputar Guru",
     *             "slug": null,
     *             "total_blogs": 2
     *         },
     *         {
     *             "name": "Tips Belajar",
     *             "slug": null,
     *             "total_blogs": 1
     *         },
     *         {
     *             "name": "Berita",
     *             "slug": null,
     *             "total_blogs": 7
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
    public function index(Request $request, BlogCategoryService $service)
    {
        $total = $service->countCategories();
        $data = $service->listCategories(
            $request->input('offset'),
            $request->input('limit'),
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
    public function show($slug, BlogCategoryService $service)
    {
        $data = $service->getCategory($slug);
        $return = array(
            'data'    => $data,
        );

        return response()->json($return, 200);
    }
}
