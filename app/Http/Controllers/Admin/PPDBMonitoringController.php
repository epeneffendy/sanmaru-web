<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PPDBSettingClassExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportExcelRequest;
use App\Imports\PPDBSettingClassesImport;
use App\Models\Period;
use App\Models\PPDBUser;
use App\Models\Stage;
use App\Models\Unit;
use App\Services\PPDBMonitoringService;
use App\Services\PPDBUserService;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;

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

        $stageAdministrasi = $PPDBMonitoringService->stagesAdministrasi($period, false, 'administration');
        $detailStages = $PPDBMonitoringService->stages($stages, $period);
        $lastStage = $PPDBMonitoringService->stagesAdministrasi($period, false, 'last-stage');

        $data = [
            'nav' => $this->page,
            'period' => $period,
            'stages' => $stages,
            'stageAdministrasi' => $stageAdministrasi,
            'detailStages' => $detailStages,
            'lastStage' => $lastStage
        ];
        return view('administrator/ppdb-monitoring/show-detail-period', $data);
    }

    public function showDetailStage(Request $request, $id, $type, $stage_id, PPDBMonitoringService $PPDBMonitoringService)
    {
        $id = 178;
        $period = Period::find($id);

        if ($type == 'administration') {
            $data = $PPDBMonitoringService->stagesAdministrasi($period, true, 'administration');

            $data = [
                'nav' => $this->page,
                'period' => $period,
                'type' => $type,
                'data' => $data
            ];

        } elseif ($type == 'development-statement') {

            $data = $PPDBMonitoringService->stagesAdministrasi($period, true, 'development-statement');

            $stage = Stage::where('id', $stage_id)->where('active', 1)->first();

            $data = [
                'nav' => $this->page,
                'period' => $period,
                'type' => $type,
                'data' => $data,
                'stage' => $stage
            ];
        } elseif ($type == 'last-stage') {
            $data = $PPDBMonitoringService->stagesAdministrasi($period, true, 'last-stage');
            $stage = [];

            $data = [
                'nav' => $this->page,
                'period' => $period,
                'type' => $type,
                'data' => $data,
                'stage' => $stage
            ];
        } elseif ($type == 'setting-class') {
            $data = $PPDBMonitoringService->stagesAdministrasi($period, true, 'setting-class');
            $stage = [];

            $data = [
                'nav' => $this->page,
                'period' => $period,
                'type' => $type,
                'data' => $data,
                'stage' => $stage
            ];
        } else {
            $stage = Stage::where('id', $stage_id)->where('active', 1)->first();
            $data = [];

            $data = [
                'nav' => $this->page,
                'period' => $period,
                'type' => $type,
                'data' => $data,
                'stage' => $stage
            ];
        }

        return view('administrator/ppdb-monitoring/partials/stage-view/detail-stage', $data);
    }

    public function userLastStage($id, PPDBMonitoringService $PPDBMonitoringService)
    {
        $period = Period::findOrFail($id);

        $passedUserIds = $this->studentPassedOrder($id);

        $ppdbUsers = PPDBUser::where('unit_id', $period->unit->id)
            ->where('periode', $id)
            ->whereIn('ppdb_users.id', $passedUserIds)
            ->select('ppdb_users.id', 'name', 'register_number', 'unit_id', 'periode', 'status')
            ->get();

        return response()->json($ppdbUsers);
    }

    public function studentPassedOrder($period)
    {
        $ppdbUser = PPDBUser::where('periode', $period)->get();
        $arr = [];
        foreach ($ppdbUser as $user) {
            if ($user->isOrderConfirmed) {
                $arr[] = $user->id;
            }
        }

        return $arr;
    }

    public function postUsers(Request $request, $id, PPDBUserService $ppdbUserService)
    {
        $ppdb = PPDBUser::where('periode', $id)->get();
        $response = 'success';

        DB::beginTransaction();
        try {
            $statuses = $request->input('status', []);
            foreach ($ppdb as $ind => $item) {
                if (isset($request->statuses[$item->id])) {
                    $status = isset($request->statuses[$item->id]) ? $request->statuses[$item->id] : null;
                    $finalStatus = PPDBUser::STATUS_SUBMITTED;
                    if ($status == 'accepted') {
                        $finalStatus = PPDBUser::STATUS_ACCEPTED;
                    } elseif ($status == 'not_selected') {
                        $finalStatus = PPDBUser::STATUS_NOT_SELECTED;
                    }
                    $item->status = $finalStatus;
                    $item->save();
                }
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PPDB Post Users Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'debug' => $e->getMessage() // Opsional: hapus jika sudah production
            ], 500);
        }
    }

    public function templateSettingClass(Request $request)
    {
        $userStageExport = new PPDBSettingClassExport($request->all());
        $title = "Template Import daftar siswa.xlsx";

        return $userStageExport->download($title);
    }

    public function importUserStudent(ImportExcelRequest $request, $id)
    {
        $sessionFlash = [];
        $period= Period::where('id', $id)->firstOrFail();
        $input = $request->validated();

        $userStagesImport = new PPDBSettingClassesImport($period, app(PPDBUserService::class));

        if ($input['type'] === 'overwrite') {
            $userStagesImport->setOverwrite(true);
        }

        $userStagesImport->import($input['file']);
        $reports = $userStagesImport->getReport();

        $message = count($reports['success']) . ' data berhasil diimport.';
        $sessionFlash = ['message' => $message];

        if (!empty($reports['failure'])) {
            $messageBag = new MessageBag();

            $messageBag->add('errors', count($reports['failure']) . ' data gagal diimport:');
            foreach ($reports['failure'] as $error) {
                $messageBag->add('errors', $error);
            }
            $sessionFlash['errors'] = $messageBag;
        }

        return redirect()->route('admin.ppdb-monitoring.show-detail-stage', [
            'id'       => $id,
            'type'     => 'setting-class',
            'stage_id' => 0
        ])->with($sessionFlash);

    }

}

