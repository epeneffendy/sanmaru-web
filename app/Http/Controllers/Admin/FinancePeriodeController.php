<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Services\FinancePeriodeService;

class FinancePeriodeController extends Controller
{
    private $page = [
        "parent" => "finance-configuration",
        "child" => "finance-periode"
    ];

    public function index(Request $request, FinancePeriodeService $financePeriodeService)
    {
        $data = $financePeriodeService->get();

        $data = [
            'nav' => $this->page,
            'data'=>$data
        ];

        return view('administrator.finance-periode.list', $data);
    }
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:finance_periode,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        $periode = \App\Models\FinancePeriode::find($request->id);
        $periode->start_date = $request->start_date;
        $periode->end_date = $request->end_date;
        $periode->status = $request->status;
        $periode->save();

        return redirect()->back()->with('success', 'Periode pembayaran berhasil diupdate.');
    }
}
