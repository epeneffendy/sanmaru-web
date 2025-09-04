<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\NewOrderRequest;
use App\Services\ProductOrderService;
use App\Http\Controllers\Controller;
use Response;

/**
 * @group Order Information
 *
 * APIs for Order
 */
class ProductOrderController extends Controller
{
    /**
     * [GET] Retrieve User Orders Transaction
     *
     * Retrieve cart data by user
     *
     * @response 200 {
     *    "data": [
     *        {
     *            "id": 1211,
     *            "invoice_no": "21030820001175",
     *            "status": "confirmed",
     *            "payment_image": "http://localhost/images/payment_image/cdndo_7068706a716b534a49.jpeg",
     *            "pickup_status": "not_pickup",
     *            "pickup_date": null,
     *            "pickup_image": null,
     *            "payment_confirmed_date": "2021-06-02 01:46:06",
     *            "payment_confirmed_mail_sent": null,
     *            "details": [
     *                {
     *                    "quantity": 1,
     *                    "total_price": 90000,
     *                    "name": "[SD] Kemeja Serviam",
     *                    "slug": "sd-sby-kemeja-serviam",
     *                    "price": 90000,
     *                    "size": "11",
     *                    "image": "http://localhost/images/product/5b5344205342595d204b656d656a61205365727669616d70687058364c655253.jpeg"
     *                },
     *                {
     *                    "quantity": 1,
     *                    "total_price": 95000,
     *                    "name": "[SD] Kemeja Nasional",
     *                    "slug": "sd-sby-kemeja-nasional",
     *                    "price": 95000,
     *                    "size": "11",
     *                    "image": "http://localhost/images/product/5b5344205342595d204b656d656a61204e6173696f6e616c706870716a53626d4a.jpeg"
     *                },
     *                {
     *                    "quantity": 2,
     *                    "total_price": 50000,
     *                    "name": "[SD] Kaos Kaki",
     *                    "slug": "sd-sby-kaos-kaki",
     *                    "price": 25000,
     *                    "size": "19-22",
     *                    "image": "http://localhost/images/product/5b5344205342595d204b616f73204b616b6970687059696e587668.jpeg"
     *                }
     *            ],
     *            "vouchers": null,
     *            "grand_total": 750000,
     *            "discount_total": 0,
     *            "grand_total_after_discount": 750000
     *        }
     *    ],
     *    "meta": {
     *        "status": 200
     *    }
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function index(ProductOrderService $service)
    {
        $data = $service->getOrders(request()->user());

        $meta = array(
            'status' => 200
        );

        $return = array(
            'data' => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }

    /**
     * [GET] Retrieve User Order Transaction
     *
     * Retrieve cart data by user
     *
     * @response 200 {
     *    "data": {
     *            "id": 1211,
     *            "invoice_no": "21030820001175",
     *            "status": "confirmed",
     *            "payment_image": "http://localhost/images/payment_image/cdndo_7068706a716b534a49.jpeg",
     *            "pickup_status": "not_pickup",
     *            "pickup_date": null,
     *            "pickup_image": null,
     *            "payment_confirmed_date": "2021-06-02 01:46:06",
     *            "payment_confirmed_mail_sent": null,
     *            "details": [
     *                {
     *                    "quantity": 1,
     *                    "total_price": 90000,
     *                    "name": "[SD] Kemeja Serviam",
     *                    "slug": "sd-sby-kemeja-serviam",
     *                    "price": 90000,
     *                    "size": "11",
     *                    "image": "http://localhost/images/product/5b5344205342595d204b656d656a61205365727669616d70687058364c655253.jpeg"
     *                },
     *                {
     *                    "quantity": 1,
     *                    "total_price": 95000,
     *                    "name": "[SD] Kemeja Nasional",
     *                    "slug": "sd-sby-kemeja-nasional",
     *                    "price": 95000,
     *                    "size": "11",
     *                    "image": "http://localhost/images/product/5b5344205342595d204b656d656a61204e6173696f6e616c706870716a53626d4a.jpeg"
     *                },
     *                {
     *                    "quantity": 2,
     *                    "total_price": 50000,
     *                    "name": "[SD] Kaos Kaki",
     *                    "slug": "sd-sby-kaos-kaki",
     *                    "price": 25000,
     *                    "size": "19-22",
     *                    "image": "http://localhost/images/product/5b5344205342595d204b616f73204b616b6970687059696e587668.jpeg"
     *                }
     *            ],
     *            "vouchers": null,
     *            "grand_total": 750000,
     *            "discount_total": 0,
     *            "grand_total_after_discount": 750000
     *    },
     *    "meta": {
     *        "status": 200
     *    }
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function show($id, ProductOrderService $service)
    {
        $data = $service->getOrder($id, request()->user());

        $meta = array(
            'status' => 200
        );

        $return = array(
            'data' => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }

    /**
     * [POST] Cancel User Order Transaction
     *
     * Cancel user order transaction
     *
     * @response 200 {
     *    "meta": {
     *        "message": "order berhasil ditabalkan",
     *        "status": 200
     *    }
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function cancel($id, ProductOrderService $service)
    {
        try {
            $data = $service->cancel(['product_order_id' => $id], request()->user());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'data pemesanan tidak ditemukan '
            ], 400);
        }

        $meta = array(
            'status' => $data ? 200 : 400,
            'message' => $data ? 'order berhasil dibatalkan' : 'order tidak bisa dibatalkan'
        );

        $return = array(
            'data' => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }

    /**
     * [POST] Post payment image of User Order Transaction
     *
     * upload payment image of user order transaction
     *
     * @bodyParam image file required    payment image file
     * 
     * @response 200 {
     *    "meta": {
     *        "message": "bukti pembayaran berhasil diunggah",
     *        "data": {
     *          "path_upload": "payment_image/323238397068703430422e746d70.jpeg",
     *          "path_url": "http://sanmaru.test/images/payment_image/323238397068703430422e746d70.jpeg",
     *          "path": "http://sanmaru.test/images/payment_image/323238397068703430422e746d70.jpeg",
     *          "filename": "323238397068703430422e746d70.jpeg"
     *        },
     *        "status": 200
     *    }
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function uploadPayment($id, ProductOrderService $service)
    {
        $validator = Validator::make(request()->all(), [
            'image' => 'required|file|mimes:png,bmp,jpg,jpeg,webp,gif'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        $data = $service->uploadPaymentImage($id, request()->user(), request()->all());

        $meta = array(
            'status' => $data ? 200 : 400,
            'message' => $data ? 'sukses upload pembayaran!' : 'upload pembayaran gagal'
        );

        $return = array(
            'data' => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }

    /**
     * [POST] Post Order
     *
     * Make new order
     *
     * @bodyParam details array required    details content of cart  changed. Example: [['slug' => 'smp-sby-kemeja-serviam-pa', 'size' => '15', 'qty' => 1]]
     * @bodyParam details.*.slug    string  required     product slug
     * @bodyParam details.*.size string required    product size
     * @bodyParam details.*.quantity integer    required     product quantity
     *
     *
     * @response {
     *    "data": [
     *        {
     *            "message": "sukses menambahkan order baru!"
     *        }
     *    ]
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function store(ProductOrderService $service)
    {
        $validator = Validator::make(request()->all(), (new NewOrderRequest)->rules());

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        $data = $service->createNewOrder(request()->all(), request()->user());

        $meta = array(
            'status' => $data ? 200 : 400,
            'message' => $data ? 'sukses menambahkan order baru!' : 'order gagal'
        );

        $return = array(
            'data' => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }
}