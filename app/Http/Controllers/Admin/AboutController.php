<?php

namespace App\Http\Controllers\Admin;

use App\Services\AboutService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AboutRequest;

class AboutController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "about"
    ];

    public function index($categorySlug, AboutService $aboutService)
    {
        $data = $aboutService->generateIndexData($categorySlug, $this->page);
        return view('administrator.about.list', $data);
    }

    public function add($categorySlug, AboutService $aboutService)
    {
        $data = $aboutService->generateAddingData($categorySlug, $this->page);
        return view('administrator.about.add', $data);
    }

    public function insert($categorySlug, AboutRequest $request, AboutService $aboutService)
    {
        $aboutService->create($categorySlug, $request->validated());
        return redirect(route('admin.about.index', $categorySlug))->with('message', 'Berhasil ditambahkan');
    }

    public function edit($categorySlug, $slug, AboutService $aboutService)
    {
        $data = $aboutService->generateEditableData($categorySlug, $slug, $this->page);
        return view('administrator.about.add', $data);
    }

    public function update($categorySlug, AboutRequest $request, $slug, AboutService $aboutService)
    {
        $aboutService->update($categorySlug, $slug, $request->validated());
        return redirect(route('admin.about.index', $categorySlug))->with('message', 'Berhasil diupdate');
    }

    public function delete($categorySlug, $slug, AboutService $aboutService)
    {
        $aboutService->delete($categorySlug, $slug);
        return redirect(route('admin.about.index', $categorySlug))->with('message', 'Berhasil dihapus');
    }

    public function selectCategory(AboutService $aboutService)
    {
        $data = $aboutService->generateCategoryData($this->page);
        return view('administrator.about.select-category', $data);
    }
}
