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
use App\Mail\PeriodConfirmed;
use App\Models\StudentBills;
use App\Services\EmailService;

class PPDBMonitoringController extends Controller
{

    private $page = [
        "parent" => "ppdb",
        "child" => "ppdb-monitoring",
    ];

    public function index(Request $request, PPDBMonitoringService $PPDBMonitoringService)
    {
        $periods = new Period();

       if ($request->input('name')) {
           $periods = $periods->where('name', 'like', '%' . $request->input('name') . '%');
       }
       if ($request->input('unit')) {
           $periods = $periods->where('unit_id', $request->input('unit'));
       }

       if ($request->input('year')) {
           $periods = $periods->where('school_year', $request->input('year'));
       }

        $currentSchoolYear = now()->month > 6 ? now()->year + 1 : now()->year;

        if ($request->filled('year') && $request->input('year') != 'all') {
            $periods = $periods->where('school_year', $request->input('year'));
        } elseif (!$request->has('year')) {
            $periods = $periods->where('school_year', $currentSchoolYear);
        }

        $per_page = 15;
        $paginator = $periods->byUserRole()->with('unit', 'class')->withCount('ppdbUsers')->orderBy('id', 'desc')->paginate($per_page);

        $paginator->appends($request->query());

        $paginator->getCollection()->transform(function ($period) {
            return [
                'id' => $period->id,
                'name' => $period->name,
                'desc' => $period->short_desc,
                'unit' => $period->unit->name,
                'periode' => $period->period,
                'active' => $period->active_label,
                'school_year' => $period->school_year,
                'count_student' => $period->ppdb_users_count . ' Siswa'
            ];
        });

        $data = [
            'nav' => $this->page,
            'data' => $paginator,
            'units' => Unit::byUserRole()->get(),
            'periods' => Period::byUserRole()->get(),
            'params' => $request->only(['name', 'unit', 'period', 'year'])
        ];
        return view('administrator/ppdb-monitoring/index', $data);
    }

    public function showDetailPeriod(Request $request, $id, PPDBMonitoringService $PPDBMonitoringService)
    {
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
        $period = Period::find($id);
        $collection = [];
        if ($type == 'administration') {
            $data = $PPDBMonitoringService->stagesAdministrasi($period, true, 'administration');

            $data = $this->paginateArray($data, 15, $request);

            $data = [
                'nav' => $this->page,
                'period' => $period,
                'type' => $type,
                'data' => $data
            ];

        } elseif ($type == 'development-statement') {
            $data = $PPDBMonitoringService->stagesAdministrasi($period, true, 'development-statement');

            $stage = Stage::where('id', $stage_id)->where('active', 1)->first();

            $data = $this->paginateArray($data, 15, $request);

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

    public function syncStageDevelopment(Request $request, $id, $stage_id, PPDBUserService $ppdbUserService)
    {
        $syncStage = $ppdbUserService->syncStageDevelopmnet($id, $stage_id);

        if ($syncStage) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disinkronisasi.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal melakukan sinkronisasi data.'
        ]);
    }

    public function setInactive($id, StudentService $studentService){
        $ppdb = PPDBUser::findOrFail($id);

        $student = $studentService->setInactive($ppdb->student->id);

        return redirect()->route('admin.ppdb.show', [$id])->with('message', 'Siswa berhasil dinonaktifkan.');
    }

    /**
     * Paginate an array manually.
     *
     * @param array $items
     * @param int $perPage
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function paginateArray(array $items, $perPage, Request $request)
    {
        $total = count($items);
        $currentPage = $request->input("page") ?? 1;
        $startingPoint = ($currentPage * $perPage) - $perPage;
        $slicedItems = array_slice($items, $startingPoint, $perPage, true);

        return new Paginator($slicedItems, $total, $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }

    public function periodVerified(Request $request, PPDBUserService $ppdbUserService){
        DB::beginTransaction();
        try {
            $ppdb = PPDBUser::findOrFail($request->input('ppdb_user_id'));
            $ppdb->periode = $request->input('periode');
            $ppdb->period_verified = PPDBUser::PERIOD_VERIFIED;
            if($ppdb->save()){
               StudentBills::where('ppdb_user_id', $ppdb->id)->delete();

                $newPpdb = PPDBUser::findOrFail($request->input('ppdb_user_id'));

                $bills = $ppdbUserService->studentBills($newPpdb);

                $email = $ppdb->user->email;
                $template = (new PeriodConfirmed($ppdb));
                (new EmailService())->sendMail($template, $email);
            }
            DB::commit();

            return redirect()->route('admin.ppdb.show', ['id' => $ppdb->id])->with('message', 'Periode pendaftaran siswa berhasil diverifikasi.');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            Log::error('PPDB Period Verified Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memverifikasi periode: ' . $e->getMessage());
        }
    }
}
