<?php

namespace App\Http\Controllers\Admin;

use App\Models\SchoolLife;
use App\Models\SchoolLifeCategory;
use App\Services\SchoolLifeService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolLifeRequest;

class SchoolLifeController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "school-life"
    ];

    public function index($schoolLifeCategoryId)
    {
        $schoolLifeCategory = SchoolLifeCategory::find($schoolLifeCategoryId);
        $data = $schoolLifeCategory->schoolLifes;

        return view('administrator.school-life.list', [
            'data' => $data,
            'nav' => $this->page,
            'schoolLifeCategory' => $schoolLifeCategory,
            'categories' => SchoolLifeCategory::active()->get()
        ]);
    }

    public function add($schoolLifeCategoryId)
    {
        if (!$schoolLifeCategory = SchoolLifeCategory::find($schoolLifeCategoryId))
            return view('administrator.school-life.select-category');

        $data = [
            'schoolLifeCategory' => $schoolLifeCategory,
            'schoolLife' => '',
            'nav' => $this->page
        ];

        return view('administrator.school-life.add', $data);
    }

    public function insert($schoolLifeCategoryId, SchoolLifeRequest $request, SchoolLifeService $schoolLifeService)
    {
        if (!$schoolLifeCategory = SchoolLifeCategory::find($schoolLifeCategoryId))
            return view('administrator.school-life.select-category');

        $schoolLifeService->create($request->validated());

        return redirect(route('admin.school-life.index', $schoolLifeCategoryId))->with('message', 'Berhasil ditambahkan');
    }

    public function edit($schoolLifeCategoryId, SchoolLife $schoolLife)
    {
        if (!$schoolLifeCategory = SchoolLifeCategory::find($schoolLifeCategoryId))
            return view('administrator.school-life.select-category');

        $data = [
            'status' => 'edit',
            'schoolLife' => $schoolLife,
            'schoolLifeCategory' => $schoolLifeCategory,
            'nav' => $this->page
        ];

        return view('administrator.school-life.add', $data);
    }

    public function update($schoolLifeCategoryId, SchoolLifeRequest $request, SchoolLife $schoolLife, SchoolLifeService $SchoolLifeService)
    {
        if (!$schoolLifeCategory = schoolLifeCategory::find($schoolLifeCategoryId))
            return view('administrator.school-life.select-category');

        $SchoolLifeService->update($schoolLife->id, $request->validated());

        return redirect(route('admin.school-life.index', $schoolLifeCategoryId))->with('message', 'Berhasil diupdate');
    }

    public function delete($schoolLifeCategoryId, SchoolLife $schoolLife, SchoolLifeService $SchoolLifeService)
    {
        if (!$schoolLifeCategory = SchoolLifeCategory::find($schoolLifeCategoryId))
            return view('administrator.school-life.select-category');

        $SchoolLifeService->delete($schoolLife);

        return redirect(route('admin.school-life.index', $schoolLifeCategoryId))->with('message', 'Berhasil dihapus');
    }

    public function selectCategory()
    {
        $data = [
            'nav' => $this->page,
            'categories' => SchoolLifeCategory::with('schoolLifes')->orderBy('order')->get()
        ];

        return view('administrator.school-life.select-category', $data);
    }
}
