<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductOrderPickupQRCodeController extends Controller
{
    private $page = [
        "parent" => "shop",
        "child" => "product-order-pickup-qrcode"
    ];

    public function index()
    {
        $data = [
            'nav' => $this->page,
        ];

        return view('administrator.product-order-pickup-qrcode.index', $data);
    }
}

