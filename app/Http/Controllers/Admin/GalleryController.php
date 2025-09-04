<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;
use App\Services\GalleryService;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class GalleryController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "gallery"
    ];

    public function index(GalleryService $galleryService)
    {
        $data = $galleryService->generateIndexData($this->page);
        return view('administrator.gallery.list', $data);
    }

    public function show($id, GalleryService $galleryService)
    {
        $data = $galleryService->generateShowingData($id, $this->page);
        return view('administrator.gallery.show', $data);
    }

    public function add(GalleryService $galleryService)
    {
        $data = $galleryService->generateAddingData($this->page);
        return view('administrator.gallery.add', $data);
    }

    public function insert(GalleryRequest $request, GalleryService $galleryService)
    {
        $galleryService->create($request->validated());
        return redirect(route('admin.gallery.index'))->with('message', 'berhasil ditambahkan');
    }

    public function edit($id, GalleryService $galleryService)
    {
        $data = $galleryService->generateEditableData($id, $this->page);
        return view('administrator.gallery.add', $data);
    }

    public function update(GalleryRequest $request, $id, GalleryService $galleryService)
    {
        $galleryService->update($id, $request->validated());
        return redirect(route('admin.gallery.index'))->with('message', 'berhasil diupdate');
    }

    public function delete($id, GalleryService $galleryService)
    {
        $galleryService->delete($id);
        return redirect(route('admin.gallery.index'))->with('message', 'berhasil dihapus');
    }

    public function toggle(Request $request, $id, GalleryService $galleryService)
    {
        if (!Helper::canPublishArticle()) {
            return redirect()->back();
        }
        
        $status = $galleryService->toggleStatus($id);
        return redirect()->route('admin.gallery.index')->with('message', "Gallery item id '{$id}' is " . $status);
    }
}
