<?php

namespace App\Http\Controllers\Admin;

use App\Models\PPDBUser;
use App\Models\Unit;
use App\Models\Period;
use App\Models\PPDBUserStage;
use App\Helpers\PriceHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class PPDBCheckExcessDateController extends Controller
{
    private $page = [
        'child' => '',
        'parent' => ''
    ];

    public function index()
    {
        $units = Unit::byUserRole()->get();
        return view('administrator.ppdb-check-excess-date.index', [
            'units' => $units,
            'nav' => $this->page
        ]);
    }

    public function unitPeriods($unitId)
    {
        $unit = Unit::where('id', $unitId)->firstOrFail();
        $periods = Period::select('id', 'name')->where('unit_id', $unitId)->get();

        return response()->json($periods, 200);
    }

    public function check(Request $request)
    {
        $input = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'period_id' => 'required|exists:periods,id'
        ]);

        $ppdbs = PPDBUser::query()
            ->with('user', 'user.ppdb', 'unit', 'period')
            ->whereHas('unit', function ($query) use ($input) {
                return $query->byUserRole()->where('id', $input['unit_id']);
            })
            ->where('periode', $input['period_id'])
            ->get();

        $ppdbs = $ppdbs->filter(function ($ppdb) {

            $passed = $ppdb->stages()->filter(function ($stage) {
                return $stage->is_opening_development_feature && $stage->passed == \App\Models\PPDBUserStage::TEXT_LOLOS;
            })->count();

            $check = false;

            if ($ppdb->development_fee_option == 'cicilan') {
                $startAngsuran = PriceHelper::getDevelopmentStartDateFinance($ppdb);
                $limitDate = Carbon::parse($startAngsuran)->addMonths(5)->toDateString();

                if ($ppdb->angsuran_1 > $limitDate) {
                    $check = true;
                }
                if ($ppdb->angsuran_2 > $limitDate) {
                    $check = true;
                }
                if ($ppdb->angsuran_3 > $limitDate) {
                    $check = true;
                }
                if ($ppdb->angsuran_4 > $limitDate) {
                    $check = true;
                }
                if ($ppdb->angsuran_5 > $limitDate) {
                    $check = true;
                }
            }

            return $passed && $check;
        });

        $ppdbs = $ppdbs->map(function ($ppdb) {
            $ppdb->angsuran_start = PriceHelper::getDevelopmentStartDateFinance($ppdb);

            return $ppdb;
        });

        return redirect()->back()->with([
            'ppdbs' => $ppdbs
        ]);
    }
}
