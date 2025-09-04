<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\DataPPDBExport;
use App\Models\Period;

class ExportDataController extends Controller
{
    private $page = [
        "parent" => "ppdb",
        "child" => "export-data"
    ];
  
    public function index()
    {
        $data = Period::byUserRole()->with(['unit', 'class', 'ppdbUsers' => function($query) {
            $query->select('id', 'name', 'created_at', 'periode', 'status', 'payment_form');
        }])->get();

        $params = [
            'nav' => $this->page,
            'data' => $data,
        ];

        return view('administrator.export-data.list', $params);
    }

    public function export($id)
    {
        $dataPPDBExport = new DataPPDBExport();
        $dataPPDBExport->setFilter($id);
        $title = "Exports Data PPDB ". date('Y-m-d H:i:s') . ".xlsx";

        return $dataPPDBExport->download($title);
    }

}
