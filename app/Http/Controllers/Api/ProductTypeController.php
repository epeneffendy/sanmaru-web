<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;

/**
 * @group Product Type Information
 *
 * APIs for product type information
 */
class ProductTypeController extends Controller
{
    /**
     * [GET] Retrieve Product Types
     *
     * Retrieve product's types, can be filled with limit and offset
     *
     * @queryParam limit limit data to be queried. Example: 5
     * @queryParam offset offset data to be queried. Example: 5
     * @response {
     *  "data": [
     *      {
     *          "slug": "kemeja-smp",
     *          "name": "Kemeja SMP"
     *      }
     *  ],
     *  "meta": {
     *      "total": 1
     *  }
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function index(ProductService $productService)
    {
        $total = $productService->countTypes();
        $data = $productService->listTypes();
        $meta = array(
            'total' => $total
        );
        $return = array(
            'data'    => $data,
            'meta' => $meta
        );

        return response()->json($return, 200);
    }

    /**
     * [GET] Retrieve Product Type Detail
     *
     * Retrieve product type detail based on slug
     *
     * @urlParam slug slug name of the product types
     *
     * @response {
     *    "data": {
     *        "id": 1,
     *        "name": "Kemeja SMP",
     *        "description": "Ini barang-barang kemeja untuk SMP",
     *        "slug": "kemeja-smp",
     *        "deleted_at": null,
     *        "created_at": "2020-02-21 09:46:39",
     *        "updated_at": "2020-02-21 09:46:39"
     *    }
     *}
     *
     * @response 404 {
     *    "message": "Not Found"
     *}
     *
     * @authenticated
     */
    public function show($slug, ProductService $productService)
    {
        $data = $productService->getType($slug);
        $return = array(
            'data'    => $data,
        );

        return response()->json($return, 200);
    }
}
