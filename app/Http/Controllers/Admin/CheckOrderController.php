<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CheckOrdersExport;
use App\Http\Controllers\Controller;
use App\Services\CheckOrderService;
use Illuminate\Http\Request;

class CheckOrderController extends Controller
{
    private $page = [
        "parent" => "ppdb",
        "child" => "check-order"
    ];

    public function index(Request $request, CheckOrderService $checkOrderService)
    {
        $data = $checkOrderService->generateIndexData($request->except(['page']), $this->page);
        return view('administrator.check-order.list', $data);
    }

    public function export(Request $request)
    {
        $checkOrdersExport = new CheckOrdersExport($request->only(['unit', 'name', 'order_status']));
        $title = "Exports Data Cek Pesanan " . date('Y-m-d H:i:s') . ".xls";

        return $checkOrdersExport->download($title);
    }

    public function dashboard(CheckOrderService $checkOrderService)
    {
        $data = $checkOrderService->generateDashboardData($this->page);
        return view('administrator.check-order.dashboard', $data);
    }
}
