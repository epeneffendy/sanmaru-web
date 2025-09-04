<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TestimonialRequest;
use App\Http\Controllers\Controller;
use App\Services\TestimonialService;
use App\Helpers\Helper;
use Response;

class TestimonialController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "testimonial"
    ];

    public function index(TestimonialService $testimonialService)
    {
        $data = $testimonialService->generateIndexData($this->page);
        return view('administrator.testimonial.list', $data);
    }

    public function add(TestimonialService $testimonialService)
    {
        $data = $testimonialService->generateAddingData($this->page);
        return view('administrator.testimonial.add', $data);
    }

    public function insert(TestimonialRequest $request, TestimonialService $testimonialService)
    {
        $testimonialService->create($request->validated());
        return redirect(route('admin.testimonial.index'))->with('message', 'berhasil ditambahkan');
    }

    public function edit($id, TestimonialService $testimonialService)
    {
        $data = $testimonialService->generateEditableData($id, $this->page);
        return view('administrator.testimonial.add', $data);
    }

    public function update(TestimonialRequest $request, $id, TestimonialService $testimonialService)
    {
        $testimonialService->update($id, $request->validated());
        return redirect(route('admin.testimonial.index'))->with('message', 'berhasil diupdate');
    }

    public function delete($id, TestimonialService $testimonialService)
    {
        $testimonialService->delete($id);
        return redirect(route('admin.testimonial.index'))->with('message', 'berhasil dihapus');
    }

    public function toggle($id, TestimonialService $testimonialService)
    {
        if (!Helper::canPublishArticle()) {
            return redirect()->back();
        }

        $status = $testimonialService->toggleStatus($id);
        return redirect()->route('admin.testimonial.index')->with('message', "Testimonial id '{$id}' is " . $status);
    }
}
