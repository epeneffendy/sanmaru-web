<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CartUpdateRequest;
use App\Http\Controllers\Controller;
use App\Services\CartService;
use Response;

/**
 * @group Cart Information
 *
 * APIs for Cart
 */
class CartController extends Controller
{
    /**
     * [GET] Retrieve User Cart
     *
     * Retrieve cart data by user
     *
     * @response 200 {
     *     "data": {
     *         "status": "new_added",
     *         "grand_total": 120000,
     *         "discount_total": 100000,
     *         "grand_total_after_discount": 20000,
     *         "vouchers": {
     *             "id": 22,
     *             "code": "032122KHUSUSPI",
     *             "rule": "100000",
     *             "note": null,
     *             "type": "discount_fixed",
     *             "usage_limit": 1
     *         },
     *         "details": [
     *             {
     *                 "quantity": 1,
     *                 "total_price": "120000.00",
     *                 "name": "[SMP] Kemeja Serviam Putra",
     *                 "slug": "smp-sby-kemeja-serviam-pa",
     *                 "size": "15",
     *                 "image": "http://localhost/images/product/5b534d50205342595d204b656d656a61205365727669616d2050417068706351744f4352.jpeg"
     *             }
     *         ]
     *     },
     *     "meta": {
     *         "status": 200
     *     }
     * }
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function index(CartService $service)
    {
        $data = $service->getCart();

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
     * [POST] Update Cart
     *
     * Update user's cart content
     *
     * @bodyParam details array required    details content of cart  changed. Example: [['slug' => 'smp-sby-kemeja-serviam-pa', 'size' => '15', 'qty' => 1]]
     * @bodyParam details.*.slug    string  required     product slug
     * @bodyParam details.*.size string required    product size
     * @bodyParam details.*.quantity integer    required     product quantity
     * @bodyParam voucher string/null voucher code to be applied
     *
     *
     * @response {
     *    "data": [
     *        {
     *            "message": "Cart berhasil diupdate!"
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
    public function update(CartService $service)
    {
        $validator = Validator::make(request()->all(), (new CartUpdateRequest)->rules());

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        $service->updateCart(request()->all(), request()->user());

        $data = $service->getcart();

        $meta = array(
            'status' => 200,
            'message' => 'cart berhasil diupdate'
        );

        $return = array(
            'data' => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }

    /**
     * [POST] Apply Voucher to Cart
     *
     * Update user's cart voucher
     *
     * @bodyParam voucher string/null   required     voucher code to be applied
     *
     *
     * @response {
     *    "data": [
     *        {
     *            "message": "Voucher berhasil diupdate!"
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
    public function applyVoucher(CartService $service)
    {
        $validator = Validator::make(request()->all(), [
            'voucher' => [
                'string', 'exists:vouchers,code', 'nullable'
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        if (request()->input('voucher')) {
            if ($service->applyVoucher(request()->all(), request()->user()->toArray())) {
                $message = 'Voucher berhasil diterapkan';
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Voucher tidak berlaku, mohon cek kembali'
                ], 200);
            }
        } else {
            $service->deleteVoucher(request()->user()->toArray());
            $message = 'Voucher berhasil dihapus';
        }

        $data = $service->getCart();

        $meta = array(
            'status' => 200,
            'message' => $message
        );

        $return = array(
            'data' => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }

    /**
     * [POST] Add Product to Cart
     *
     * Update user's cart 
     *
     * @bodyParam slug string   required    product slug
     * @bodyParam size string   required    product size
     * @bodyParam quantity integer  required    product quantity
     *
     *
     * @response {
     *    "data": [
     *        {
     *            "message": "Cart berhasil diupdate!"
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
    public function store(CartService $service)
    {
        $validator = Validator::make(request()->all(), [
            'slug' => ['string', 'exists:products,slug', 'required'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'size' => ['string', 'exists:product_details,size', 'required']
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        $service->addToCart(request()->all(), request()->user());

        $data = $service->getcart();

        $meta = array(
            'status' => 200,
            'message' => 'Seragam berhasil ditambahkan'
        );

        $return = array(
            'data' => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }

    /**
     * [POST] Remove Product from Cart
     *
     * Remove product from user's cart 
     *
     * @bodyParam slug string   required    product slug
     * @bodyParam size string   required    product size
     *
     *
     * @response {
     *    "data": [
     *        {
     *            "message": "Cart berhasil diupdate!"
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
    public function remove(CartService $service)
    {
        $validator = Validator::make(request()->all(), [
            'slug' => ['string', 'exists:products,slug', 'required'],
            'size' => ['string', 'exists:product_details,size', 'required']
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 422);
        }

        try {
            $service->removeFromCart(request()->all(), request()->user());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus produk'
            ], 400);
        }
        $data = $service->getcart();

        $meta = array(
            'status' => 200,
            'message' => 'Produk berhasil dihapus'
        );

        $return = array(
            'data' => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }
}