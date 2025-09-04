<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HeadlineRequest;
use App\Services\HeadlineService;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Response;

class HeadlineController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "headline"
    ];

    public function index(HeadlineService $headlineService)
    {
        $data = $headlineService->generateIndexData($this->page);
        return view('administrator.headline.list', $data);
    }

    public function add(HeadlineService $headlineService)
    {
        $data = $headlineService->generateAddingData($this->page);
        return view('administrator.headline.add', $data);
    }

    public function insert(HeadlineRequest $request, HeadlineService $headlineService)
    {
        $headlineService->create($request->validated());
        return redirect(route('admin.headline.index'))->with('message', 'Headline berhasil ditambahkan');
    }

    public function edit($id, HeadlineService $headlineService)
    {
        $data = $headlineService->generateEditableData($id, $this->page);
        return view('administrator.headline.add', $data);
    }

    public function update(HeadlineRequest $request, $id, HeadlineService $headlineService)
    {
        $headlineService->update($id, $request->validated());
        return redirect(route('admin.headline.index'))->with('message', 'Headline berhasil diupdate');
    }

    public function delete($id, HeadlineService $headlineService)
    {
        $headlineService->delete($id);
        return redirect(route('admin.headline.index'))->with('message', 'Headline berhasil dihapus');
    }

    public function toggle(Request $request, $id, HeadlineService $headlineService)
    {
        if (!Helper::canPublishArticle()) {
            return redirect()->back();
        }

        $status = $headlineService->toggleStatus($id);
        return redirect()->route('admin.headline.index')->with('message', "Headline id '{$id}' is " . $status);
    }
}
