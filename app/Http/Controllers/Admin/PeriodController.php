<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Period;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PeriodRequest;
use App\Exports\PeriodPPDBUsersExport;
use App\Services\PeriodService;

class PeriodController extends Controller
{
    private $page = [
        "parent" => "ppdb",
        "child" => "period"
    ];

    public function index(Request $request)
    {

        $periods = new Period();

        if ($request->input('name')) {
            $periods = $periods->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('unit')) {
            $periods = $periods->where('unit_id', $request->input('unit'));
        }

        $periods = $periods->byUserRole()->with('unit', 'class')->orderBy('id','desc')->paginate();

        $data = [
            'nav' => $this->page,
            'periods' => $periods,
            'units' => Unit::byUserRole()->get(),
            'params' => $request->only(['name', 'unit', 'page'])
        ];

        return view('administrator.period.list', $data);
    }

    public function add(PeriodService $periodService)
    {
        $originSchoolOptions = Unit::get()->pluck('name_santa_maria')->all();

        $data = [
            'period' => [
                'period' => $this->defaultPeriod()
            ],
            'originSchoolOptions' => $originSchoolOptions,
            'unitOption' => Unit::byUserRole()->get()->pluck('name', 'id'),
            'classOption' => Classes::get()->pluck('name', 'id'),
            'schoolYearOptions' => $periodService->getSchoolYearOptions(),
            'suggestedSchoolYear' => now()->month > 6 ? (now()->year + 1) : now()->year,
            'nav' => $this->page,
        ];

        return view('administrator.period.add', $data);
    }

    public function insert(PeriodRequest $request)
    {
        $params = $this->params($request->validated());
        Period::create($params);

        return redirect(route('admin.period.index'))->with('message', 'Period berhasil ditambahkan');
    }

    public function show($id)
    {
        $period = Period::byUserRole()->findOrFail($id);

        $data = [
            'status'    => 'show',
            'period'    => $period,
            'nav'       => $this->page
        ];

        return view('administrator.period.show', $data);
    }

    public function edit($id, PeriodService $periodService)
    {
        $period = Period::byUserRole()->findOrFail($id);
        $originSchoolOptions = Unit::get()->pluck('name_santa_maria')->all();

        if ($period->is_feeder_school) {
            $originSchoolOptions = array_diff($originSchoolOptions, $period->origin_school_options);
            $originSchoolOptions = array_merge(array_values($originSchoolOptions), $period->origin_school_options);
        }

        $data = [
            'status' => 'edit',
            'period' => $period,
            'originSchoolOptions' => $originSchoolOptions,
            'unitOption' => Unit::byUserRole()->get()->pluck('name', 'id'),
            'classOption' => Classes::get()->pluck('name', 'id'),
            'schoolYearOptions' => $periodService->getSchoolYearOptions(),
            'suggestedSchoolYear' => now()->month > 6 ? (now()->year + 1) : now()->year,
            'nav' => $this->page
        ];

        return view('administrator.period.add', $data);
    }

    public function update(PeriodRequest $request, $id)
    {
        $period = Period::byUserRole()->findOrFail($id);
        $params = $this->params($request->validated());
        $period->update($params);

        return redirect(route('admin.period.index'))->with('message', 'Period berhasil diedit');
    }

    public function delete($id)
    {
        $period = Period::byUserRole()->findOrFail($id);
        $period->delete();

        return redirect(route('admin.period.index'))->with('message', 'Period berhasil dihapus');
    }

    private function defaultPeriod()
    {
        return now()->format('d/m/Y') . " - " . now()->addDays(7)->format('d/m/Y');
    }

    private function params($params)
    {
        $originSchoolOptions = [];
        if (isset($params['origin_school_options']) && count($params['origin_school_options'])) {
            $originSchoolOptions = array_merge($originSchoolOptions, $params['origin_school_options']);
        }

        if (isset($params['additional_origin_school']) && $params['additional_origin_school']) {
            $additionalOriginSchool = array_map('trim', explode(',', $params['additional_origin_school']));
            $originSchoolOptions = array_merge($originSchoolOptions, $additionalOriginSchool);
            unset($params['additional_origin_school']);
        }

        $params['origin_school_options'] = $originSchoolOptions;

        return $params;
    }

    public function export(Request $request)
    {
        $period = Period::where('id', $request->id)->firstOrFail();
        $periodPPDBUsersExport = new PeriodPPDBUsersExport($request->all());

        $title = "Exports Data Nominal Keuangan PPDB" . date('Y-m-d H:i:s') . ".xlsx";

        return $periodPPDBUsersExport->download($title);
    }

    public function fetch(Request $request, PeriodService $periodService)
    {
        $periods = $periodService->filter($request->all(), null, ['unit']);
        return $periods;
    }
}
