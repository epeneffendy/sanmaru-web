<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;

/**
 * @group Product Category Information
 *
 * APIs for product category information
 */
class ProductCategoryController extends Controller
{
    /**
     * [GET] Retrieve Product Categories
     *
     * Retrieve product's categories, can be filled with limit and offset
     *
     * @queryParam limit limit data to be queried. Example: 5
     * @queryParam offset offset data to be queried. Example: 5
     * @response {
     *  "data": [
     *      {
     *          "slug": "pakaian-pria",
     *          "name": "Pakaian Pria"
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
        $total = $productService->countCategories();
        $data = $productService->listCategories();
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
     * [GET] Retrieve Product Category Detail
     *
     * Retrieve product category detail based on slug
     *
     * @urlParam slug slug name of the product category
     *
     * @response {
     *    "data": {
     *        "id": 1,
     *        "name": "Pakaian Pria",
     *        "description": "Ini barang-barang pakaian untuk pria",
     *        "slug": "pakaian-pria",
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
        $data = $productService->getCategory($slug);
        $return = array(
            'data'    => $data,
        );

        return response()->json($return, 200);
    }
}
