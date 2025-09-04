<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CampusUnitRequest;
use App\Services\CampusUnitService;

class CampusUnitController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "campus"
    ];

    public function index($campusId, CampusUnitService $campusUnitService)
    {
        $data = $campusUnitService->generateIndexData($campusId, $this->page);
        return view('administrator.campus-unit.list', $data);
    }

    public function add($campusId, CampusUnitService $campusUnitService)
    {
        $data = $campusUnitService->generateAddingData($campusId, $this->page);
        return view('administrator.campus-unit.add', $data);
    }

    public function insert($campusId, CampusUnitRequest $request, CampusUnitService $campusUnitService)
    {
        $campusUnit = $campusUnitService->create($campusId, $request->validated());
        return redirect(route('admin.campus.unit.index', $campusId))->with('message', 'Berhasil ditambahkan');
    }

    public function edit($campusId, $id, CampusUnitService $campusUnitService)
    {
        $data = $campusUnitService->generateEditableData($campusId, $id, $this->page);
        return view('administrator.campus-unit.add', $data);
    }

    public function update($campusId, $id, CampusUnitRequest $request, CampusUnitService $campusUnitService)
    {
        $campus = $campusUnitService->update($campusId, $id, $request->validated());
        return redirect(route('admin.campus.unit.index', $campusId))->with('message', 'Berhasil diupdate');
    }

    public function delete($campusId, $id, CampusUnitService $campusUnitService)
    {
        $campusUnitService->delete($campusId, $id);
        return redirect(route('admin.campus.unit.index', $campusId))->with('message', 'Berhasil dihapus');
    }

}
