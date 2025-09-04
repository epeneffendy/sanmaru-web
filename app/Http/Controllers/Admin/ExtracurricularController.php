<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExtracurricularStoreRequest;
use App\Services\ExtracurricularService;
use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use Illuminate\Http\Request;

use App\Http\Requests\ImportExcelRequest;
use App\Exports\ExtracurricularsExport;
use App\Imports\ExtracurricularsImport;
use Illuminate\Support\MessageBag;

class ExtracurricularController extends Controller
{
    private $page = [
        "parent" => "master",
        "child" => "extracurricular"
    ];

    public function index(Request $request)
    {
        if (!empty($request->input('name'))) {
            $name = $request->input('name');
            $extracurriculars = Extracurricular::whereRaw("LOWER(name) like '%" . $name . "%")->with('class')->get();
        } else {
            $extracurriculars = Extracurricular::with('class')->get();
        }

        $data = [
            'nav' => $this->page,
            'extracurriculars' => $extracurriculars
        ];

        return view('administrator/extracurricular/list', $data);
    }

    public function add(ExtracurricularService $extracurricularService)
    {
        $data = $extracurricularService->generateAddingData($this->page);
        return view('administrator/extracurricular/add', $data);
    }

    public function insert(ExtracurricularStoreRequest $request, ExtracurricularService $extracurricularService)
    {
        $input = $request->validated();
        $extracurricularService->create($input);
        return redirect()->route('admin.extracurricular.index')->with('message', 'Extracurricular "'. $input['name'] .'" Berhasil ditambahkan');
    }

    public function edit($id, ExtracurricularService $extracurricularService)
    {
        $data = $extracurricularService->generateEditableData($id, $this->page);
        return view('administrator/extracurricular/add', $data);
    }

    public function update(ExtracurricularStoreRequest $request, $id, ExtracurricularService $extracurricularService)
    {
        $input = $request->validated();
        $extracurricularService->update($id, $input);
        return redirect()->route('admin.extracurricular.index')->with('message', 'Extracurricular "'. $input['name'] .'" Berhasil diedit');
    }

    public function delete($id, ExtracurricularService $extracurricularService)
    {
        $extracurricularService->delete($id);
        return redirect()->route('admin.extracurricular.index')->with('message', 'Berhasil dihapus');
    }

    public function export(Request $request)
    {
        $extracurricularsExport = new ExtracurricularsExport();
        $title = "Exports Data Extracurricular " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $extracurricularsExport->setTemplate(true);
            $title = "Template Import Extracurricular.xlsx";
        }

        return $extracurricularsExport->download($title);
    }

    public function import(
        ImportExcelRequest $request,
        ExtracurricularService $extracurricularService
    ) {
        $sessionFlash = [];
        $input = $request->validated();
        $extracurricularsImport = new ExtracurricularsImport($extracurricularService);
        if ($input['type'] === 'overwrite') {
            $extracurricularsImport->setOverwrite(true);
        }

        $extracurricularsImport->import($input['file']);
        $reports = $extracurricularsImport->getReport();

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

        return redirect()->route('admin.extracurricular.index')->with($sessionFlash);
    }
}
