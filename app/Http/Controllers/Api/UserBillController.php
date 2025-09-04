<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BillService;
use Illuminate\Http\Request;

/**
 * @group User Bill Information
 *
 * APIs for bill information
 */
class UserBillController extends Controller
{
    /**
     * [GET] Retrieve User's Bill List
     *
     * Retrieve user's bill based on bill category
     *
     * @urlParam categoryId id of bill's category
     * @queryParam limit limit data to be queried. Example: 5
     * @queryParam offset offset data to be queried. Example: 5
     * @response {
     *"data": [
     *        {
     *            "id": 1,
     *            "name": "Uang Bulanan",
     *            "due_date": "2020-03-21",
     *            "amount": 500000,
     *            "status": "unpaid"
     *        },
     *        {
     *            "id": 2,
     *            "name": "Uang Gedung",
     *            "due_date": "2020-03-21",
     *            "amount": 2000000,
     *            "status": "paid"
     *        },
     *        {
     *            "id": 3,
     *            "name": "Pendaftaran Sekolah",
     *            "due_date": "2020-03-21",
     *            "amount": 2000000,
     *            "status": "paid"
     *        },
     *        {
     *            "id": 5,
     *            "name": "UKS",
     *            "due_date": "2020-03-21",
     *            "amount": 2000000,
     *            "status": "paid"
     *        }
     *    ],
     *    "meta": {
     *        "limit": 0,
     *        "offset": 0,
     *        "total": 4
     *    }
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function index(
        Request $request,
        $categoryId,
        BillService $billService
    ) {
        $total = $billService->countUsersBills($request->user()->id, $categoryId);
        $data = $billService->listUsersBills(
            $request->user()->id,
            $categoryId,
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

    /**
     * [GET] Retrieve Bill Detail
     *
     * Retrieve user's bill detail
     *
     * @urlParam billId id of bill wanna be shown
     * @response {
     *  "data": {
     *      "id": 1,
     *      "name": "Uang Bulanan",
     *      "due_date": "2020-03-21",
     *      "amount": 500000,
     *      "status": "unpaid"
     *  }
     *}
     *
     * @response 404 {
     *    "message": "Not Found"
     *}
     *
     * @authenticated
     */
    public function show(
        Request $request,
        $billId,
        BillService $billService
    ) {
        $data = $billService->getBillUser($request->user()->id, $billId);
        $return = array(
            'data'    => $data,
        );
        $status_code = 200;

        return response()->json($return, $status_code);
    }
}
