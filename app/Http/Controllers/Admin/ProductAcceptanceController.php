<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Vendor;
use App\Services\ProductAcceptanceService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductAcceptanceController extends Controller
{
    private $page = [
        'parent' => 'shop',
        'child' => 'product-acceptance'
    ];

    public function index(ProductAcceptanceService $productAcceptanceService)
    {
        $data = $productAcceptanceService->get();
        return view('administrator.product-acceptance.list', [
            'nav' => $this->page,
            'data'=>$data
        ]);
    }

    public function add(Request $request, ProductService $productService){
        $params = [
            'products' => $productService->getUniform(),
            'typeNames' => $productService->getTypeName(),
            'vendors' => Vendor::get(),
            'nav' => $this->page
        ];

        return view('administrator/product-acceptance/add', $params);
    }

    public function findByProduct(Request $request){
        $product = Product::where('id',$request->product_id)->first();

        return view('administrator/product-acceptance/partial/_list_product', ['products' => $product]);
    }

    public function store(Request $request, ProductAcceptanceService $productAcceptanceService){

        DB::beginTransaction();
        try {
            $store = $productAcceptanceService->insert($request->all());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.product-acceptance.index')->with('errors', collect(['Gagal ditambahkan']));
        }
        return redirect()->route('admin.product-acceptance.index')->with('message', 'Berhasil ditambahkan');

    }

    public function show(Request $request, ProductAcceptanceService $productAcceptanceService){
        $data = $productAcceptanceService->getById($request->id);

        return view('administrator/product-acceptance/show', [
            'nav' => $this->page,
            'data' => $data,
        ]);
    }

    public function ajax(Request $request, ProductAcceptanceService $productAcceptanceService){
        dd("Asdasd");
    }

}
