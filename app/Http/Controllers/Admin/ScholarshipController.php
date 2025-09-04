<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ScholarshipRequest;
use App\Http\Controllers\Controller;
use App\Services\ScholarshipService;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class ScholarshipController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "scholarship"
    ];

    public function index(Request $request, ScholarshipService $scholarshipService)
    {
        $data = $scholarshipService->generateIndexData($request, $this->page);
        return view('administrator.scholarship.list', $data);
    }

    public function add(ScholarshipService $scholarshipService)
    {
        $data = $scholarshipService->generateAddingData($this->page);
        return view('administrator.scholarship.add', $data);
    }

    public function insert(ScholarshipRequest $request, ScholarshipService $scholarshipService)
    {
        $scholarshipService->create($request->validated());
        return redirect(route('admin.scholarship.index'))->with('message', 'berhasil ditambahkan');
    }

    public function edit($id, ScholarshipService $scholarshipService)
    {
        $data = $scholarshipService->generateEditableData($id, $this->page);
        return view('administrator.scholarship.add', $data);
    }

    public function update(ScholarshipRequest $request, $id, ScholarshipService $scholarshipService)
    {
        $scholarshipService->update($id, $request->validated());
        return redirect(route('admin.scholarship.index'))->with('message', 'berhasil diupdate');
    }

    public function delete($id, ScholarshipService $scholarshipService)
    {
        $scholarshipService->delete($id);
        return redirect(route('admin.scholarship.index'))->with('message', 'berhasil dihapus');
    }

    public function toggle(Request $request, $id, ScholarshipService $scholarshipService)
    {
        if (!Helper::canPublishArticle()) {
            return redirect()->back();
        }

        $status = $scholarshipService->toggleStatus($id);
        return redirect()->route('admin.scholarship.index')->with('message', "Beasiswa id '{$id}' is " . $status);
    }

    public function show($id, ScholarshipService $scholarshipService)
    {
        $data = $scholarshipService->generateShowData($id, $this->page);
        return view('administrator.scholarship.show', $data);
    }
}
