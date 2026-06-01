<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Unit;
use App\Services\PaymentDispensationsService;
use App\Services\StudentBillService;

class PaymentPPDBReportController extends Controller
{
    private $page = [
        "parent" => "report",
        "child" => "payment-ppdb-report"
    ];

    public function index(Request $request, PaymentDispensationsService $paymentDispensationsService, StudentBillService $studentBillService){

        $params = $this->getParams($request);
        $type = $params['type'] ?? 'all';
        $data_ppdb = [];
        if(!empty($request->all())){
            if($params['type'] == 'all'){
                $data_ppdb = $studentBillService->getStudentBillReport($params);
            }else{
                $data_ppdb = $paymentDispensationsService->getDevelopmentPaymentReport($params);    
            }
            
        }

        $data = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get()->pluck('name', 'id'),
            'periods' => Period::byUserRole()->get()->pluck('name', 'id'),
            'data' => $data_ppdb,
            'params' => $params,
            'type' => $type,
        ];

        return view('administrator/report/payment-ppdb-report/index', $data);
    }


    private function getParams(Request $request)
    {
        $params = $request->all();
        $arr_year = explode('-', $request->get('school_year', date('Y')));

        $params['unit'] = $request->get('unit');
        $params['period'] = $request->get('period');
        $params['year'] = $arr_year[0];
        $params['type'] = $request->get('type');

        return $params;
    }
}
