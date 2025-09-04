<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BillService;

/**
 * @group Bill Category Information
 *
 * APIs for bill information
 */
class BillCategoryController extends Controller
{

    /**
     * [GET] Retrieve Bill's Category List
     *
     * Retrieve bill categories
     *
     * @queryParam limit limit data to be queried. Example: 5
     * @queryParam offset offset data to be queried. Example: 5
     * @response {
     * "data": [
     *    {
     *        "id": 1,
     *            "name": "Biaya Kelas 2 SMA"
     *        }
     *    ],
     *    "meta": {
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
    public function index(BillService $billService)
    {
        $total = $billService->countCategories();
        $data = $billService->listCategories();
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
