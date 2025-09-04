<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ClassStoreRequest;
use App\Http\Controllers\Controller;
use App\Services\ClassService;
use Illuminate\Http\Request;
use App\Models\Classes;

use App\Http\Requests\ImportExcelRequest;
use Illuminate\Support\MessageBag;
use App\Exports\ClassesExport;
use App\Imports\ClassesImport;

class ClassController extends Controller
{
    private $page = [
        "parent" => "master",
        "child" => "class"
    ];

    public function index(Request $request)
    {
        if (!empty($request->input('name'))) {
            $name = $request->input('name');
            $classes = Classes::whereRaw("LOWER(name) like '%" . $name . "%")->with('unit')->get();
        } else {
            $classes = Classes::with('unit')->get();
        }

        $data = [
            'nav' => $this->page,
            'classes' => $classes
        ];

        return view('administrator/class/list', $data);
    }

    public function add(ClassService $classService)
    {
        $data = $classService->generateAddingData($this->page);
        return view('administrator/class/add', $data);
    }

    public function insert(ClassStoreRequest $request, ClassService $classService)
    {
        $input = $request->validated();
        $classService->create($input);
        return redirect()->route('admin.class.index')->with('message', 'Class "'. $input['name'] .'" Berhasil ditambahkan');
    }

    public function edit($id, ClassService $classService)
    {
        $data = $classService->generateEditableData($id, $this->page);
        return view('administrator/class/add', $data);
    }

    public function update(ClassStoreRequest $request, $id, ClassService $classService)
    {
        $input = $request->validated();
        $classService->update($id, $input);
        return redirect()->route('admin.class.index')->with('message', 'Class "'. $input['name'] .'" Berhasil diedit');
    }

    public function delete($id, ClassService $classService)
    {
        $classService->delete($id);
        return redirect()->route('admin.class.index')->with('message', 'Berhasil dihapus');
    }

    public function export(Request $request)
    {
        $classesExport = new ClassesExport();
        $title = "Exports Data Class " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $classesExport->setTemplate(true);
            $title = "Template Import Class.xlsx";
        }

        return $classesExport->download($title);
    }

    public function import(
        ImportExcelRequest $request,
        ClassService $classService
    ) {
        $sessionFlash = [];
        $input = $request->validated();
        $classesImport = new ClassesImport($classService);
        if ($input['type'] === 'overwrite') {
            $classesImport->setOverwrite(true);
        }

        $classesImport->import($input['file']);
        $reports = $classesImport->getReport();

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

        return redirect()->route('admin.class.index')->with($sessionFlash);
    }
}
