<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductOrder;
use App\Enums\ProductTypeEnum;
use App\Http\Controllers\Controller;
use App\Services\ProductOrderService;

class ProductOrderKantinController extends Controller
{
    private $page = [
        "parent" => "shop",
        "child" => "product-order"
    ];

    public function create(ProductOrderService $productOrderService)
    {
        $data = $productOrderService->generateAddingData($this->page, ProductTypeEnum::KANTIN);

        return view('administrator.product-order.add_kantin', $data);
    }

    public function show($id)
    {
        $productOrder = ProductOrder::where('id', $id)->with('productOrderDetails', 'productOrderDetails.product', 'productOrderDetails.productDetail')->firstOrFail();

        $data = [
            'productOrder' => $productOrder,
            'nav' => $this->page
        ];

        return view('administrator.product-order.show_kantin', $data);
    }
}
