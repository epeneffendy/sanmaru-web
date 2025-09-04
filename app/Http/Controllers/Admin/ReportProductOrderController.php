<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReportProductOrdersExport;
use App\Models\Unit;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductOrder;
use App\Models\User;
use App\Services\ProductOrderService;
use Carbon\Carbon;

class ReportProductOrderController extends Controller
{
    use ImageHandler;

    private $page = [
        "parent" => "shop",
        "child" => "product-order"
    ];

    private function getParam(Request $request)
    {
        $params = $request->all();
        if (!array_key_exists('date_range', $params)) {
            $params['date_range'] = sprintf('%s - %s', Carbon::now()->subMonth()->format('m/d/Y'), Carbon::now()->format('m/d/Y'));
        }

        $params['start_date'] = Carbon::parse(trim(explode('-', $params['date_range'])[0]));
        $params['end_date'] = Carbon::parse(trim(explode('-', $params['date_range'])[1]))->endOfDay();
        $params['find_ppdb'] = ($request->get('status_student') ?? User::PPDB) === User::PPDB;
        $params['find_regular'] = ($request->get('status_student') ?? User::STUDENT) === User::STUDENT;

        return $params;
    }

    public function index(Request $request, ProductOrderService $productOrderService)
    {
        $params = $this->getParam($request);
        $products = $productOrderService->getSummaryProductOrder($params);

        $data = [
            'nav' => $this->page,
            'products' => $products,
            'units' => Unit::byUserRole()->get()->pluck('name','id'),
            'params' => $params,
        ];

        return view('administrator.report-product-order.list', $data);
    }

    public function export(Request $request, ProductOrderService $productOrderService)
    {
        $params = $this->getParam($request);
        $products = $productOrderService->getSummaryProductOrder($params);

        $productOrdersExport = new ReportProductOrdersExport($products);
        $title = sprintf('Exports Laporan Pesanan %s - %s .xlsx', $params['start_date'], $params['end_date']);

        return $productOrdersExport->download($title);
    }
}
