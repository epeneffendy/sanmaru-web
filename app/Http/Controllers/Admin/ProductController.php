<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Product;
use App\Models\CartDetail;
use Illuminate\Http\Request;
use App\Enums\ProductTypeEnum;
use App\Services\ImageService;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Models\ProductCategory;
use App\Services\ProductService;
use Illuminate\Http\UploadedFile;
use App\Models\ProductOrderDetail;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportExcelRequest;
use App\Http\Requests\ProductStoreRequest;

class ProductController extends Controller
{
    private $page = [
        "parent" => "shop",
        "child" => "product"
    ];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, ProductService $productService)
    {
        $data = $productService->generateIndexData($this->page, $request);

        return view('administrator/product/list', $data);
    }

    public function add(ProductService $productService)
    {
        $data = $productService->generateAddingData($this->page);
        return view('administrator.product.add', $data);
    }

    public function insert(ProductStoreRequest $request, ProductService $productService)
    {
        $input = $request->validated();
        $productService->create($input);
        return redirect()->route('admin.product.index')->with('message', 'Product "'. $input['name'] .'" Berhasil ditambahkan');
    }

    public function edit($id, ProductService $productService)
    {
        $data = $productService->generateEditableData($id, $this->page);
        return view('administrator/product/add', $data);
    }

    public function update(ProductStoreRequest $request, $id, ProductService $productService)
    {
        $input = $request->validated();
        $update = $productService->update($id, $input);

        if (isset($update['errors'])) {
            return redirect()->route('admin.product.edit', $id)->with('errors', $update['errors']);
        }
        return redirect()->route('admin.product.index')->with('message', 'Product "'. $input['name'] .'" Berhasil diedit');
    }

    public function toggle($id, ProductService $productService)
    {
        $productService->toggleStatus($id);
        return redirect()->route('admin.product.index')->with('message', "Status Product Berhasil diubah");
    }

    public function delete($id, ProductService $productService)
    {
        $checkProductOrder = ProductOrderDetail::where('product_id',$id)->exists();
        $checkCartDetail = CartDetail::where('product_id', $id)->exists();
        if ($checkCartDetail || $checkProductOrder) {
           return redirect()->route('admin.product.index')->with('recorded', [
                'type' => 'Success',
                'text' => 'Added successfully',
            ]);;
        } else {
            $productService->delete($id);
            return redirect()->route('admin.product.index')->with('message', 'Berhasil dihapus');
        }
    }

    public function export(Request $request)
    {
        $productsExport = new ProductsExport($request->all());
        $title = "Exports Data Product " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $productsExport->setTemplate(true);
            $title = "Template Import Product   .xlsx";
        }

        return $productsExport->download($title);
    }

    public function import(
        ImportExcelRequest $request,
        ProductService $productService
    ) {
        $sessionFlash = [];
        $input = $request->validated();
        $productsImport = new ProductsImport($productService);
        if ($input['type'] === 'overwrite') {
            $productsImport->setOverwrite(true);
        }

        $productsImport->import($input['file']);
        $reports = $productsImport->getReport();

        $sessionFlash = [
            'message' => count($reports['success']) . ' data berhasil diimport',
        ];

        if (isset($reports['failure']) && count($reports['failure'])) {
            $sessionFlash['errors'] = new MessageBag([
                'errors' => [
                    count($reports['failure']) . ' data gagal diimport<br/>' . implode('<br/>', $reports['failure'])
                ]
            ]);
        }

        return redirect()->route('admin.product.index')->with($sessionFlash);
    }


    public function show($id, ProductService $productService)
    {
        $data = $productService->generateShowData($id, $this->page);
        return view('administrator/product/show', $data);
    }

    public function historyStock(ProductService $productService)
    {
        $data = $productService->generateHistoryStockData($this->page);
        return view('administrator/product/history-stock', $data);
    }
}
