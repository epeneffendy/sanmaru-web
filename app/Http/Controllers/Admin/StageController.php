<?php

namespace App\Http\Controllers\Admin;

use App\Lib\DbTrx;
use App\Models\Unit;
use App\Models\Stage;
use App\Models\Period;
use App\Models\PPDBUser;
use App\Models\PPDBUserStage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StageRequest;

use App\Http\Requests\ImportExcelRequest;
use Illuminate\Support\MessageBag;
use App\Exports\UserStagesExport;
use App\Imports\UserStagesImport;

class StageController extends Controller
{
    private $page = [
        "parent" => "ppdb",
        "child" => "stage",
    ];

    public function index(Request $request)
    {
        $stages = Stage::query()
            ->with('period');

        if ($request->input('name')) {
            $stages = $stages->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('unit')) {
            $stages = $stages->where('unit_id', $request->input('unit'));
        }
        {
            if ($request->input('period')) {
                $stages = $stages->where('periode', $request->input('period'));
            }
        }
        if ($request->input('year')) {
            $stages = $stages->whereHas('period', function ($query) use ($request) {
                return $query->where('school_year', $request->input('year'));

            });
        }
        $stages = $stages->byUserRole()->orderBy('created_at', 'desc')->paginate();

        $data = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
            'stages' => $stages,
            'periods' => period::byUserRole()->get(),
            'params' => $request->only(['name', 'unit', 'period', 'year'])
        ];

        return view('administrator.stage.list', $data);
    }

    public function add()
    {
        $data = [
            'unitOption' => Unit::byUserRole()->get()->pluck('name', 'id'),
            'periodOption' => [],
            'nav' => $this->page,
        ];

        return view('administrator.stage.add', $data);
    }

    public function insert(StageRequest $request)
    {
        Stage::create($request->validated());

        return redirect(route('admin.stage.index'))->with('message', 'Stage berhasil ditambahkan');
    }

    public function edit($id)
    {
        $stage = Stage::byUserRole()->findOrFail($id);

        $data = [
            'status' => 'edit',
            'stage' => $stage,
            'unitOption' => Unit::byUserRole()->get()->pluck('name', 'id'),
            'periodOption' => Period::where('unit_id', $stage->unit_id)->byUserRole()->get()->pluck('name', 'id'),
            'nav' => $this->page
        ];

        return view('administrator.stage.add', $data);
    }

    public function update(StageRequest $request, $id)
    {
        $stage = Stage::byUserRole()->findOrFail($id);
        $stage->update($request->validated());

        return redirect(route('admin.stage.index'))->with('message', 'Stage berhasil diedit');
    }

    public function delete($id)
    {
        $stage = Stage::byUserRole()->findOrFail($id);
        $stage->delete();

        return redirect(route('admin.stage.index'))->with('message', 'Stage berhasil dihapus');
    }

    private function defaultPeriod()
    {
        return now()->format('d/m/Y') . " - " . now()->addDays(7)->format('d/m/Y');
    }

    public function getUsers($stage, $unit, $period)
    {
        $stage = Stage::byUserRole()->where('id', $stage)->firstOrFail();

        $ppdbUsers = PPDBUser::where('unit_id', $unit)
            ->where('periode', $period)
            ->select('ppdb_users.id', 'name', 'register_number', 'unit_id', 'periode', 'ppdb_user_stages.passed', 'ppdb_user_stages.note')
            ->leftJoin('ppdb_user_stages', function ($join) use ($stage) {
                return $join->on('ppdb_users.id', '=', 'ppdb_user_stages.ppdb_user_id')->where('stage_id', $stage->id);
            })
            ->get();

        if ($stage->is_opening_shop_feature) {
            $accepted = [];
            $development = Stage::where('unit_id', $unit)->where('periode', $period)->where('active', 1)->where('is_opening_development_feature', 1)->first();
            if ($development) {
                $accepted = PPDBUserStage::where('stage_id', $development->id)
                    ->where('passed', 1)->pluck('ppdb_user_id')->all();
            }

            $ppdbUsers = $ppdbUsers->filter(function ($ppdbUser) use ($accepted) {
                return in_array($ppdbUser->id, $accepted);
            })->values();

        }

        return response()->json($ppdbUsers);
    }

    public function getPeriods($unit)
    {
        Unit::byUserRole()->where('id', $unit)->firstOrFail();
        $periods = Period::where('unit_id', $unit)->select('id', 'name')->get();

        return response()->json($periods);
    }

    public function postUsers($stage, Request $request)
    {
        //TODO move to stageservice
        $stage = Stage::byUserRole()->findOrFail($stage);
        $response = 'success';
        $isPassedAll = false;

        if (($request->has('passed_all')) && ($request->passed_all == "true")) {
            $isPassedAll = true;
        }

        DbTrx::useTrx(function () use ($stage, $request, $isPassedAll) {
            $datas = [];
            PPDBUserStage::where('stage_id', $stage->id)->delete();
            foreach ($request->statuses as $id => $data) {
                if (!is_null($data) || $isPassedAll) {
                    $now = date('Y-m-d H:i:s');
                    $datas[] = [
                        'ppdb_user_id' => $id,
                        'stage_id' => $stage->id,
                        'passed' => $isPassedAll ? 1 : $data,
                        'note' => $data == 1 ? $request->notes[$id] : NULL,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                }
            }
            PPDBUserStage::insert($datas);
        }, function () use (&$response) {
            $response = 'failed';
        });

        return response()->json(['status' => $response]);
    }

    public function export()
    {
        $userStageExport = new UserStagesExport();
        $title = "Template Import daftar seleksi.xlsx";

        return $userStageExport->download($title);
    }

    public function import(
        $stage,
        ImportExcelRequest $request
    )
    {
        $sessionFlash = [];
        $stage = Stage::byUserRole()->where('id', $stage)->firstOrFail();
        $input = $request->validated();
        $userStagesImport = new UserStagesImport($stage);
        if ($input['type'] === 'overwrite') {
            $userStagesImport->setOverwrite(true);
        }

        $userStagesImport->import($input['file']);
        $reports = $userStagesImport->getReport();

        $sessionFlash = [
            'message' => count($reports['success']) . ' data berhasil diimport',
        ];

        if (isset($reports['failure']) && count($reports['failure'])) {
            $sessionFlash['errors'] = new MessageBag([
                'errors' => [
                    count($reports['failure']) . ' data gagal diimport<br/>' . implode('<br/>', $reports['failure'])
                ]
            ]);
        }

        return redirect()->route('admin.stage.edit', ['stage' => $stage->id])->with($sessionFlash);
    }
}
