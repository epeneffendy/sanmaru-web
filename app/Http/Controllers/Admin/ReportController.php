<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function developmentReport(Request $request){

    }

    public function exportDevelopmentReport(Request $request)
    {
        // Logika untuk export report
    }

    public function fetchDevelopmentReportData(Request $request)
    {
        // Logika untuk memanggil data via ajax/API
        // return response()->json(['status' => 'success', 'data' => []]);
    }
}
