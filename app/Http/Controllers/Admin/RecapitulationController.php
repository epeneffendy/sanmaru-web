<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Unit;
use App\Services\PPDBUserService;
use App\Exports\AdmissionReportExport;
use App\Exports\RecapitulationAdmissionExport;

class RecapitulationController extends Controller
{
    private $page = [
        "parent" => "report",
        "child" => "recapitulation-admission"
    ];

    public function index(Request $request, PPDBUserService $ppdbUserService){

        $params = $this->getParams($request);
        $data_ppdb = [];
        if(!empty($request->all())){
            $data_ppdb = $ppdbUserService->getRecapitulationAdmission($params);
        }

        $data = [
            'nav' => $this->page,
            'periods' => Period::byUserRole()->get()->pluck('name', 'id'),
            'data' => $data_ppdb,
            'params' => $params,
        ];

        return view('administrator/report/recapitulation-admission/index', $data);
    }


    private function getParams(Request $request)
    {
        $params = $request->all();
        $arr_year = explode('-', $request->get('school_year', date('Y')));

        $params['unit'] = $request->get('unit');
        $params['year'] = $arr_year[0];


        return $params;
    }

    public function export(Request $request, PPDBUserService $ppdbUserService){
        $params = $this->getParams($request);
        $data_ppdb = [];

        if(!empty($request->all())){
            $data_ppdb = $ppdbUserService->getRecapitulationAdmission($params);
        }

        $admissionReportExport = new RecapitulationAdmissionExport(collect($data_ppdb));
        $title = 'Exports Rekapitulasi Pendaftaran Per Unit.xlsx';

        return $admissionReportExport->download($title);
    }
}
