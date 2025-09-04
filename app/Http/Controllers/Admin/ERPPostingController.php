<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Period;
use App\Models\PPDBUser;
use Illuminate\Support\Facades\DB;
use App\Models\PPDBUserStage;
use App\Helpers\PriceHelper;

class ERPPostingController extends Controller
{
    private $page = [
        'child' => 'erp-posting',
        'parent' => 'ppdb'
    ];

    public function index()
    {
        $categories = [
            '01' => 'Posting Keuangan Pengembangan PPDB',
            '02' => 'Posting Keuangan Kegiatan PPDB'
        ];
        $units = Unit::byUserRole()->get();

        return view('administrator.erp-posting.add', [
            'categories' => $categories,
            'units' => $units,
            'nav' => $this->page,
        ]);
    }

    public function unitPeriods($unitId)
    {
        $unit = Unit::where('id', $unitId)->firstOrFail();
        $periods = Period::select('id', 'name')->where('unit_id', $unitId)->get();

        return response()->json($periods, 200);
    }

    public function initStore(Request $request)
    {
        $input = $request->validate([
            'category' => 'required|in:01,02',
            'unit_id' => 'required|exists:units,id',
            'period_id' => 'required|exists:periods,id'
        ]);

        $ppdb_count = PPDBUser::query()
            ->whereHas('unit', function ($query) use ($input) {
                return $query->byUserRole()->where('id', $input['unit_id']);
            })
            ->where('periode', $input['period_id'])
            ->count();

        return response()->json($ppdb_count, 200);
    }

    public function store($progress, Request $request)
    {
        $input = $request->validate([
            'category' => 'required|in:01,02',
            'unit_id' => 'required|exists:units,id',
            'period_id' => 'required|exists:periods,id'
        ]);

        $ppdbs = PPDBUser::query()
            ->with('user', 'user.ppdb', 'unit')
            ->whereHas('unit', function ($query) use ($input) {
                return $query->byUserRole()->where('id', $input['unit_id']);
            })
            ->where('periode', $input['period_id'])
            ->limit(20)
            ->offset($progress)
            ->get();
            
        $n = 0;
        foreach ($ppdbs as $ppdb) {
            $n++;
            $passed = $ppdb->stages()->filter(function($stage) {
                return $stage->is_opening_development_feature && $stage->passed == PPDBUserStage::TEXT_LOLOS;
            })->count();

            if ($passed) {
                if ($input['category'] == '01') {
                    event(new \App\Events\PPDB\DevelopmentStatementConfirmed($ppdb));
                } else if ($input['category'] == '02') {
                    event(new \App\Events\PPDB\FinanceActivityUpdated($ppdb));
                }
                
            }
        }
        if ($n > 0) {
            $n += $progress;
        }

        return response()->json($n, 200);
    }
}
