<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UnitStoreRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;

use Illuminate\Support\MessageBag;
use App\Exports\UnitsExport;
use App\Imports\UnitsImport;
use App\Services\UnitService;

class UnitController extends Controller
{
    private $page = [
        "parent" => "master",
        "child" => "unit"
    ];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (!empty($request->input('name'))) {
            $name = $request->input('name');
            $units = Unit::byUserRole()->whereRaw("LOWER(name) like '%" . $name . "%")->get();
        } else {
            $units = Unit::byUserRole()->get();
        }

        $data = [
            'units' => $units,
            'nav' => $this->page
        ];

        return view('administrator/unit/list', $data);
    }

    public function add()
    {
        $data = [
            'unit' => '',
            'nav' => $this->page
        ];

        return view('administrator/unit/add', $data);
    }

    public function show(Request $request, $id)
    {
        $unit = Unit::byUserRole()->findOrFail($id);
        $data = [
            'status' => 'edit',
            'unit' => $unit,
            'nav' => $this->page
        ];

        return view('administrator/unit/show', $data);
    }

    public function insert(UnitStoreRequest $request, UnitService $unitService)
    {
        $input = $request->validated();
        $input = $this->params($input);
        $unitService->create($input);
        return redirect()->route('admin.unit.index')->with('message', 'Berhasil ditambahkan');
    }

    public function edit(Request $request, $id)
    {
        $unit = Unit::byUserRole()->findOrFail($id);
        $data = [
            'status' => 'edit',
            'unit' => $unit,
            'nav' => $this->page
        ];

        return view('administrator/unit/edit', $data);
    }

    public function update(UnitStoreRequest $request, $id, UnitService $unitService)
    {
        $input = $request->validated();
        $input = $this->params($input);
        $unitService->update($id, $input);

        return redirect()->route('admin.unit.index')->with('message', 'Berhasil diedit');
    }

    public function delete(Request $request, $id)
    {
        try {
            Unit::findOrFail($id)->delete();
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.unit.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        return redirect()->route('admin.unit.index')->with('message', 'Berhasil dihapus');
    }

    public function export(Request $request)
    {
        $unitsExport = new UnitsExport();
        $title = "Exports Data Unit " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $unitsExport->setTemplate(true);
            $title = "Template Import Unit.xlsx";
        }

        return $unitsExport->download($title);
    }

    public function import(Request $request, UnitService $unitService)
    {
        $sessionFlash = [];
        $input = $request->all();
        $validator = Validator::make($input, [
            'file' => ['required', 'file', 'mimes:xls,xlsx'],
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.unit.index')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $unitsImport = new UnitsImport($unitService);
            if ($request->input('type') === 'overwrite') {
                $unitsImport->setOverwrite(true);
            }

            $unitsImport->import($request->file('file'));
            $reports = $unitsImport->getReport();

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
                ->route('admin.unit.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        return redirect()->route('admin.unit.index')->with($sessionFlash);
    }

    private function params($params)
    {
        $telp = [];
        $fax = [];

        if (isset($params['telp']) && $params['telp']) {
            $telp = array_map('trim', explode(',', $params['telp']));
        }

        if (isset($params['fax']) && $params['fax']) {
            $fax = array_map('trim', explode(',', $params['fax']));
        }

        $params['telp'] = $telp;
        $params['fax'] = $fax;

        return $params;
    }
}
