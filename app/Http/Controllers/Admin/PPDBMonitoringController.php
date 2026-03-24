<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\PPDBUser;
use App\Models\Stage;
use App\Models\Unit;
use App\Services\PPDBMonitoringService;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class PPDBMonitoringController extends Controller
{

    private $page = [
        "parent" => "ppdb",
        "child" => "ppdb-monitoring",
    ];

    public function index(Request $request, PPDBMonitoringService $PPDBMonitoringService)
    {
        $periods = new Period();

//        if ($request->input('name')) {
//            $periods = $periods->where('name', 'like', '%' . $request->input('name') . '%');
//        }
//        if ($request->input('unit')) {
//            $periods = $periods->where('unit_id', $request->input('unit'));
//        }
//
        $periods = $periods->byUserRole()->with('unit', 'class')->orderBy('id', 'desc')->get();

        $data_period = [];
        foreach ($periods as $ind => $period) {
            $count_student = PPDBUser::where('periode', $period->id)->count();

            $data_period[$ind]['id'] = $period->id;
            $data_period[$ind]['name'] = $period->name;
            $data_period[$ind]['desc'] = $period->short_desc;
            $data_period[$ind]['unit'] = $period->unit->name;
            $data_period[$ind]['periode'] = $period->period;
            $data_period[$ind]['active'] = $period->active_label;
            $data_period[$ind]['school_year'] = $period->school_year;
            $data_period[$ind]['count_student'] = $count_student . ' Siswa';
        }

        $total = count($data_period);
        $per_page = 15;
        $current_page = $request->input("page") ?? 1;

        $starting_point = ($current_page * $per_page) - $per_page;

        $data = array_slice($data_period, $starting_point, $per_page, true);

        $data = new Paginator($data, $total, $per_page, $current_page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        $data = [
            'nav' => $this->page,
            'periods' => $periods,
            'data' => $data,
            'units' => Unit::byUserRole()->get(),
            'periods' => period::byUserRole()->get(),
            'params' => $request->only(['name', 'unit', 'period', 'year'])
        ];
        return view('administrator/ppdb-monitoring/index', $data);
    }

    public function showDetailPeriod(Request $request, $id, PPDBMonitoringService $PPDBMonitoringService)
    {
        $id = 178;
        $period = Period::find($id);
        $stages = Stage::where('periode', $id)->where('active', 1)->get();

        $stageAdministrasi = $PPDBMonitoringService->stagesAdministrasi($period, false);
        $detailStages = $PPDBMonitoringService->stages($stages, $period);

        $data = [
            'nav' => $this->page,
            'period' => $period,
            'stages' => $stages,
            'stageAdministrasi' => $stageAdministrasi,
            'detailStages' => $detailStages,
        ];
        return view('administrator/ppdb-monitoring/show-detail-period', $data);
    }

    public function showDetailStage(Request $request, $id, $type, $stage_id, PPDBMonitoringService $PPDBMonitoringService)
    {
        $id = 178;
        $period = Period::find($id);

        if ($type == 'administration') {
            $data = $PPDBMonitoringService->stagesAdministrasi($period, true);

            $data = [
                'nav' => $this->page,
                'period' => $period,
                'type' => $type,
                'data' => $data
            ];

        } else {
            $stage = Stage::where('id', $stage_id)->where('active', 1)->first();
            $data = [];

            $data = [
                'nav' => $this->page,
                'period' => $period,
                'type' => $type,
                'data' => $data,
                'stage'=>$stage
            ];
        }


        return view('administrator/ppdb-monitoring/partials/stage-view/detail-stage', $data);
    }
}

