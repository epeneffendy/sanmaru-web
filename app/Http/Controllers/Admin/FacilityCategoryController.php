<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacilityCategoryStoreRequest;
use App\Services\FacilityCategoryService;

class FacilityCategoryController extends Controller
{
    private $page = [
        'parent' => 'konten',
        'child' => 'facility-category'
    ];

    public function index(FacilityCategoryService $service)
    {
        $data = $service->generateIndexData($this->page);
        return view('administrator.facility-category.list', $data);
    }

    public function add(FacilityCategoryService $service)
    {
        $data = $service->generateAddingData($this->page);
        return view('administrator.facility-category.add', $data);
    }

    public function insert(FacilityCategoryStoreRequest $request, FacilityCategoryService $service)
    {
        $input = $request->validated();
        $data = $service->create($input);
        return redirect(route('admin.facility-category.index'))->with('message', 'berhasil ditambahkan');
    }

    public function edit($id, FacilityCategoryService $service)
    {
        $data = $service->generateEditableData($id, $this->page);
        return view('administrator.facility-category.add', $data);
    }

    public function update($id, FacilityCategoryStoreRequest $request, FacilityCategoryService $service)
    {
        $input = $request->validated();
        $data = $service->update($id, $input);
        return redirect(route('admin.facility-category.index'))->with('message', 'berhasil diedit');
    }

    public function delete($id, FacilityCategoryService $service)
    {
        $service->delete($id);
        return redirect(route('admin.facility-category.index'))->with('message', 'berhasil dihapus');
    }
}
