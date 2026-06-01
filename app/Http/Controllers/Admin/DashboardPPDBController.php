<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\PPDBUserService;

class DashboardPPDBController extends Controller
{
    private $page = [
        "parent" => "dashboard",
        "child" => "dashboard-ppdb"
    ];

    public function index(PPDBUserService $ppdbUserService)
    {
        $data = $ppdbUserService->dashboardPPDB();

        $data = [
            'nav' => $this->page,
            'data'=>$data
        ];

        return view('administrator/dashboard-ppdb', $data);
    }
}
