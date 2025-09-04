<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductTypeEnum;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Enums\ProductScheduleTypeEnum;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductKantinStoreRequest;

class ProductKantinController extends Controller
{
    private $page = [
        "parent" => "shop",
        "child" => "product"
    ];

    public function create(ProductService $productService)
    {
        $data = $productService->generateAddingData($this->page, ProductTypeEnum::KANTIN);

        return view('administrator.product.add_kantin', $data);
    }

    public function store(ProductKantinStoreRequest $request, ProductService $productService)
    {
        $input = $request->validated();
        if ($input['schedule']['type'] == ProductScheduleTypeEnum::PREORDER) {
            $input['schedule'] = $request->only([
                'schedule.type',
                'schedule.available_on.open_date',
                'schedule.available_on.close_date',
                'schedule.available_on.pickup_date_schedule',
                'schedule.available_on.pickup_start_time',
                'schedule.available_on.pickup_end_time',
                'schedule.available_on.pickup_location',
                'schedule.available_on.pickup_notes'
            ])['schedule'];
        } else {
            $input['schedule'] = $request->except([
                'schedule.available_on.open_date',
                'schedule.available_on.close_date',
                'schedule.available_on.pickup_date_schedule',
                'schedule.available_on.pickup_start_time',
                'schedule.available_on.pickup_end_time',
                'schedule.available_on.pickup_location',
                'schedule.available_on.pickup_notes'
            ])['schedule'];
        }
        $input['product_type'] = ProductTypeEnum::KANTIN;
        $productService->create($input);
        return redirect()->route('admin.product.index', ['#' . ProductTypeEnum::KANTIN])->with('message', 'Product "'. $input['name'] .'" Berhasil ditambahkan');
    }

    public function edit($id, ProductService $productService)
    {
        $data = $productService->generateEditableData($id, $this->page, ProductTypeEnum::KANTIN);
        return view('administrator/product/add_kantin', $data);
    }

    public function update(ProductKantinStoreRequest $request, $id, ProductService $productService)
    {
        $input = $request->validated();
        if ($input['schedule']['type'] == ProductScheduleTypeEnum::PREORDER) {
            $input['schedule'] = $request->only([
                'schedule.type',
                'schedule.available_on.open_date',
                'schedule.available_on.close_date',
                'schedule.available_on.pickup_date_schedule',
                'schedule.available_on.pickup_start_time',
                'schedule.available_on.pickup_end_time',
                'schedule.available_on.pickup_location',
                'schedule.available_on.pickup_notes'
            ])['schedule'];
        } else {
            $input['schedule'] = $request->except([
                'schedule.available_on.open_date',
                'schedule.available_on.close_date',
                'schedule.available_on.pickup_date_schedule',
                'schedule.available_on.pickup_start_time',
                'schedule.available_on.pickup_end_time',
                'schedule.available_on.pickup_location',
                'schedule.available_on.pickup_notes',
            ])['schedule'];
        }
        $input['product_type'] = ProductTypeEnum::KANTIN;
        $productService->update($id, $input);
        return redirect()->route('admin.product.index', ['#' . ProductTypeEnum::KANTIN])->with('message', 'Product "'. $input['name'] .'" Berhasil diedit');
    }

    public function show($id, ProductService $productService)
    {
        $data = $productService->generateShowData($id, $this->page);
        return view('administrator/product/show_kantin', $data);
    }
}
