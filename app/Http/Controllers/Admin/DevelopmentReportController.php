<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DevelopmentReportExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Services\PPDBUserService;

class DevelopmentReportController extends Controller
{
    private $page = [
        "parent" => "report",
        "child" => "development-report"
    ];

    public function index(Request $request, PPDBUserService $ppdbUserService){
        $params = $this->getParams($request);

        $data_ppdb = [];
        if(!empty($request->all())){
            $data_ppdb = $ppdbUserService->getDevelopmentReport($params);
        }

        $data = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get()->pluck('name', 'id'),
            'data_ppdb' => $data_ppdb,
            'params' => $params,
        ];

        return view('administrator/report/development-report/index', $data);
    }

    private function getParams(Request $request)
    {
        $params = $request->all();
        $arr_year = explode('-', $request->get('school_year', date('Y')));

        $params['unit'] = $request->get('unit');
        $params['year'] = $arr_year[0];


        return $params;
    }

    public function export(Request $request, PPDBUserService $ppdbUserService)
    {

            $params = $this->getParams($request);
            $data_ppdb = [];

            if(!empty($request->all())){
                $data_ppdb = $ppdbUserService->getDevelopmentReport($params);
            }

            $ppdbUserExport = new DevelopmentReportExport(collect($data_ppdb));
            $title = 'Exports Laporan Dana Pengembangan.xlsx';

            return $ppdbUserExport->download($title);
    }

}
