<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Unit;
use App\Services\FinanceService;

class FinanceReportController extends Controller
{
    private $page = [
        "parent" => "report",
        "child" => "finance-report"
    ];

    public function index(Request $request, FinanceService $financeService){

        $params = $this->getParams($request);
        $data_ppdb = [];
        if(!empty($request->all())){
            $data_ppdb = $financeService->getFinanceReport($params);
        }

        $data = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get()->pluck('name', 'id'),
            'periods' => Period::byUserRole()->get()->pluck('name', 'id'),
            'data_ppdb' => $data_ppdb,
            'params' => $params,
        ];

        return view('administrator/report/finance-report/index', $data);
    }


    private function getParams(Request $request)
    {
        $params = $request->all();
        $arr_year = explode('-', $request->get('school_year', date('Y')));

        $params['unit'] = $request->get('unit');
        $params['period'] = $request->get('period');
        $params['year'] = $arr_year[0];


        return $params;
    }
}
