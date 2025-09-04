<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Services\BlogService;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogStoreRequest;

class BlogController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "blog"
    ];

    public function index(BlogService $blogService)
    {
        $data = $blogService->generateIndexData($this->page);
        return view('administrator.blog.list', $data);
    }

    public function add(BlogService $blogService)
    {
        $data = $blogService->generateAddingData($this->page);
        return view('administrator.blog.add', $data);
    }

    public function insert(BlogStoreRequest $request, BlogService $blogService)
    {
        $blogService->create($request->validated());
        return redirect(route('admin.blog.index'))->with('message', 'Berhasil ditambahkan');
    }

    public function edit(Blog $blog, BlogService $blogService)
    {
        $data = $blogService->generateEditableData($blog, $this->page);
        return view('administrator.blog.add', $data);
    }

    public function update(BlogStoreRequest $request, Blog $blog, BlogService $blogService)
    {
        $blogService->update($blog->id, $request->validated());

        return redirect(route('admin.blog.index'))->with('message', 'Berhasil diedit');
    }

    public function delete(Blog $blog, BlogService $blogService)
    {
        $blogService->delete($blog);

        return redirect(route('admin.blog.index'))->with('message', 'Berhasil dihapus');
    }
}
