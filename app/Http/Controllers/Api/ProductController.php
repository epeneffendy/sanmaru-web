<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

/**
 * @group Product Information
 *
 * APIs for product information
 */
class ProductController extends Controller
{
    /**
     * [GET] Retrieve Product List
     *
     * Retrieve products list, can be filled with category's slug, limit, and offset
     *
     * @urlParam slug slug name of the product category
     * @queryParam limit limit data to be queried. Example: 5
     * @queryParam offset offset data to be queried. Example: 5
     * @response {
     *   "data": [
     *       {
     *           "name": "Satu Set Kemeja Batik SMP",
     *           "slug": "satu-set-kemeja-batik-smp",
     *           "details": [
     *              {
     *                  "size": "S",
     *                  "stock": 100,
     *                  "price": "10000.00"
     *              },
     *              {
     *                  "size": "M",
     *                  "stock": 100,
     *                  "price": "10000.00"
     *              }
     *           ],
     *           "image": "http://localhost:8000/img/default-seragam.jpg"
     *       },
     *       {
     *           "name": "Kemeja Coklat SMA",
     *           "slug": "kemeja-coklat-sma",
     *           "details": [
     *              {
     *                  "size": "S",
     *                  "stock": 100,
     *                  "price": "10000.00"
     *              }
     *           ],
     *           "image": "http://localhost:8000/img/default-seragam.jpg"
     *       }
     *   ],
     *   "meta": {
     *       "limit": 0,
     *       "offset": 0,
     *       "total": 2
     *   }
     *}
     *
     * @response 422 {
     *    "message": "Error message"
     *}
     *
     * @authenticated
     */
    public function index(Request $request, $categorySlug = null, ProductService $productService)
    {
        $total = $productService->countProducts($categorySlug);
        $data = $productService->listProducts(
            $categorySlug,
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
     * [GET] Retrieve Product Detail
     *
     * Retrieve product detail based on product slug
     *
     * @urlParam slug slug name of the product category
     *
     * @response {
     *  "data": {
     *       "name": "Kemeja Coklat SMA",
     *       "slug": "kemeja-coklat-sma",
     *       "weight": 300,
     *       "merk": "Purnama",
     *           "detail": [
     *              {
     *                  "stok": 10,
     *                  "price": "1000.00",
     *                  "size": "S"
     *              },
     *              {
     *                  "stok": 120,
     *                  "price": "1000.00",
     *                  "size": "M"
     *              }
     *           ],
     *       "type_name": "Kemeja SMP",
     *       "category_name": "Pakaian Pria",
     *       "image": "http://localhost:8000/img/default-seragam.jpg"
     *   }
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
        $data = $productService->getProduct($slug);
        $return = array(
            'data'    => $data,
        );

        return response()->json($return, 200);
    }
}
