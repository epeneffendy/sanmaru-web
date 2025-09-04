<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacilityStoreRequest;
use App\Http\Requests\GalleryRequest;
use App\Services\FacilityService;
use App\Services\GalleryService;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    private $page = [
        'parent' => 'konten',
        'child' => 'facility'
    ];

    public function index(FacilityService $service)
    {
        $data = $service->generateIndexData($this->page);
        return view('administrator.facility.list', $data);
    }

    public function add(FacilityService $service)
    {
        $data = $service->generateAddingData($this->page);
        return view('administrator.facility.add', $data);
    }

    public function insert(FacilityStoreRequest $request, FacilityService $service)
    {
        $input = $request->validated();
        $data = $service->create($input);
        return redirect(route('admin.facility.index'))->with('message', 'berhasil ditambahkan');
    }

    public function edit($id, FacilityService $service)
    {
        $data = $service->generateEditableData($id, $this->page);
        return view('administrator.facility.add', $data);
    }

    public function update($id, FacilityStoreRequest $request, FacilityService $service)
    {
        $input = $request->validated();
        $data = $service->update($id, $input);
        return redirect(route('admin.facility.index'))->with('message', 'berhasil diedit');
    }

    public function delete($id, FacilityService $service)
    {
        $service->delete($id);
        return redirect(route('admin.facility.index'))->with('message', 'berhasil dihapus');
    }

    public function galleryData(FacilityService $service)
    {
        $data = $service->getGalleries();
        $html = view('administrator.facility.gallery', $data)->render(); 
        return response()->json(['html' => $html],200);
    }

    public function insertGallery(GalleryRequest $request, GalleryService $service)
    {
        $input = $request->validated();
        $data = $service->create($input);
        $data->content_image_url = $data->getContentImageUrl();
        return response()->json($data, 200);
    }
}
