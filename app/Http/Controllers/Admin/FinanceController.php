<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Unit;
use App\Models\Period;
use App\Models\Finance;
use App\Models\PPDBUser;
use Illuminate\Http\Request;
use App\Exports\FinanceExport;
use App\Imports\FinanceImport;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Http\Requests\FinanceStoreRequest;
use App\Http\Requests\FinanceUpdateRequest;
use Illuminate\Support\Facades\DB;
use App\Events\PPDB\FinanceActivityUpdated;
use App\Services\FinanceService;
use App\Services\PeriodService;

class FinanceController extends Controller
{
    private $page = [
        "parent" => "master",
        "child" => "finance"
    ];

    public function index(Request $request, FinanceService $financeService)
    {
        $scopes = [
            'student_username' => 'Username Siswa',
            'finance_name' => 'Nama Keuangan',
            'period_name' => 'Nama Period',
        ];
        $related = [
            'unit',
            'user',
            'user.student',
            'user.ppdb',
            'period',
            'users',
            'users.ppdb',
            'users.student',
            'users.student',
            'users.ppdb',
        ];
        $finances =  $financeService->filter($request->all(), 10, $related);

        $data = [
            'nav' => $this->page,
            'scopes' => $scopes,
            'types' => Finance::getTypes(),
            'units' => Unit::get(['id', 'name']),
            'periods' => $financeService->getAvailableYears(),
            'finances' => $finances,
            'params' => $request->only(['search', 'scope', 'type', 'unit', 'period']),
        ];

        return view('administrator.finance.list', $data);
    }

    public function add(FinanceService $financeService)
    {
        $data = [
            'finance' => '',
            'units' => Unit::all()->keyBy('name'),
            'students' => User::whereIn('type', ['siswa', 'ppdb'])->with('student', 'ppdb')->get(),
            'periods' => Period::with('unit')->orderBy('unit_id', 'asc')->orderBy('id', 'asc')->get(),
            'years' => $financeService->getCollectedYears(),
            'nav' => $this->page,
        ];

        return view('administrator.finance.add', $data);
    }

    public function insert(FinanceStoreRequest $request)
    {
        $input = $request->validated();
        $status = DB::transaction(function () use ($input) {
            return tap(Finance::create($input), function (Finance $finance) use ($input) {
                $finance->users()->sync($input['user_ids']);
            });
        });

        \Cache::forget('finance-all');

        if ($status
            && isset($input['type']) && ($input['type'] == 'activity')
            && isset($input['user_ids']) && $input['user_ids'] && count($input['user_ids']) ) {
            $ppdb = PPDBUser::whereIn('user_id', $input['user_ids'])->get();

            foreach ($ppdb as $key => $value) {
                event(new FinanceActivityUpdated($value));
            }
        }

        return redirect(route('admin.finance.index'))->with('message', 'Berhasil ditambahkan');
    }

    public function edit(Finance $finance, FinanceService $financeService)
    {
        $data = [
            'status' => 'edit',
            'finance' => $finance,
            'units' => Unit::all()->keyBy('name'),
            'periods' => Period::with('unit')->orderBy('unit_id', 'asc')->orderBy('id', 'asc')->get(),
            'students' => User::whereIn('type', ['siswa', 'ppdb'])->with('student', 'ppdb')->get(),
            'years' => $financeService->getCollectedYears(),
            'nav' => $this->page
        ];

        return view('administrator.finance.add', $data);
    }

    public function update(FinanceUpdateRequest $request, Finance $finance)
    {
        $input = $request->validated();
        $status = DB::transaction(function () use ($finance, $input) {
            return tap($finance->update($input), function () use ($finance, $input) {
                return $finance->users()->sync($input['user_ids']);
            });
        });

        \Cache::forget('finance-all');

        if ($status
            && isset($input['type']) && ($input['type'] == 'activity')
            && isset($input['user_ids']) && $input['user_ids'] && count($input['user_ids']) ) {
            $ppdb = PPDBUser::whereIn('user_id', $input['user_ids'])->get();

            foreach ($ppdb as $key => $value) {
                event(new FinanceActivityUpdated($value));
            }
        }

        return redirect(route('admin.finance.index'))->with('message', 'Berhasil diedit');
    }

    public function delete(Finance $finance)
    {
        DB::transaction(function () use ($finance) {
            return tap($finance->users()->detach(), function () use ($finance) {
                return $finance->delete();
            });
        });

        \Cache::forget('finance-all');

        return redirect(route('admin.finance.index'))->with('message', 'Berhasil dihapus');
    }

    public function export(Request $request)
    {
        $financeExport = new FinanceExport();
        $title = "Export Data Master Keuangan " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $financeExport->setTemplate(true);
            $title = "Template Import Master Keuangan.xlsx";
        }

        return $financeExport->download($title);
    }

    public function import(Request $request)
    {
        $sessionFlash = [];

        $request->validate([
            'file' => ['required', 'file', 'mimes:xls,xlsx'],
            'type' => 'required'
        ]);

        try {
            $financeImport = new FinanceImport();
            if ($request->input('type') === 'overwrite') {
                $financeImport->setOverwrite(true);
            }

            $financeImport->import($request->file('file'));
            $reports = $financeImport->getReport();

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
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.finance.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        return redirect()->route('admin.finance.index')->with($sessionFlash);
    }

    public function unitPeriode($unitId)
    {
        $collect = collect();
        $unit = Unit::where('id', $unitId)->first();
        if ($unit) {
            $periods = Period::where('unit_id', $unitId)->get()->each(function ($period) use ($collect) {
                $collect->push([
                    'id' => $period->id,
                    'name' => "[" . $period->unit->name ."] " . $period->name,
                ]);
            });
        }

        return response()->json($collect, 200);
    }
}
