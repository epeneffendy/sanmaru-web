<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqRequest;
use App\Services\FaqService;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class FaqController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "faq"
    ];

    public function index(FaqService $faqService)
    {
        $data = $faqService->generateIndexData($this->page);
        return view('administrator.faq.list', $data);
    }

    public function add(FaqService $faqService)
    {
        $data = $faqService->generateAddingData($this->page);
        return view('administrator.faq.add', $data);
    }

    public function insert(FaqRequest $request, FaqService $faqService)
    {
        $faqService->create($request->validated());
        return redirect(route('admin.faq.index'))->with('message', 'Berhasil ditambahkan');
    }

    public function edit($id, FaqService $faqService)
    {
        $data = $faqService->generateEditableData($id, $this->page);
        return view('administrator.faq.add', $data);
    }

    public function update(FaqRequest $request, $id, FaqService $faqService)
    {
        $faqService->update($id, $request->validated());
        return redirect(route('admin.faq.index'))->with('message', 'Berhasil diedit');
    }

    public function delete($id, FaqService $faqService)
    {
        $faqService->delete($id);
        return redirect(route('admin.faq.index'))->with('message', 'Berhasil dihapus');
    }

    public function toggle(Request $request, $id, FaqService $faqService)
    {
        if (!Helper::canPublishArticle()) {
            return redirect()->back();
        }

        $status = $faqService->toggleStatus($id);
        return redirect()->route('admin.faq.index')->with('message', "Faq id '{$id}' is " . $status);
    }

    public function show($id, FaqService $faqService)
    {
        $data = $faqService->generateShowData($id, $this->page);
        return view('administrator.faq.show', $data);
    }
}
