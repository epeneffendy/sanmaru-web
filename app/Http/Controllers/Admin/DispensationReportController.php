<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Unit;
use App\Services\FinanceService;
use App\Services\PaymentDispensationsService;

class DispensationReportController extends Controller
{
    private $page = [
        "parent" => "report",
        "child" => "dispensation-report"
    ];

    public function index(Request $request, PaymentDispensationsService $paymentDispensationsService){

        $params = $this->getParams($request);
        $data_ppdb = [];
        if(!empty($request->all())){
            $data_ppdb = $paymentDispensationsService->getDispensationReport($params);
        }

        $data = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get()->pluck('name', 'id'),
            'periods' => Period::byUserRole()->get()->pluck('name', 'id'),
            'data' => $data_ppdb,
            'params' => $params,
        ];

        return view('administrator/report/dispensation-report/index', $data);
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
