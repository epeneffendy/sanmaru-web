<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogCategory;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCategoryStoreRequest;

class BlogCategoriesController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "blog-category"
    ];

    public function index()
    {
        $data = [
            'nav' => $this->page,
            'blogCategories' => BlogCategory::get()
        ];

        return view('administrator.blog-category.list', $data);
    }

    public function add()
    {
        $data = [
            'blogCategory' => '',
            'nav' => $this->page,
        ];

        return view('administrator.blog-category.add', $data);
    }

    public function insert(BlogCategoryStoreRequest $request)
    {
        $params = $request->validated();
        $params['slug'] = Str::slug($params['name']);
        BlogCategory::create($params);

        return redirect(route('admin.blog-category.index'))->with('message', 'Berhasil ditambahkan');
    }

    public function edit(BlogCategory $blogCategory)
    {
        $data = [
            'status' => 'edit',
            'blogCategory' => $blogCategory,
            'nav' => $this->page
        ];

        return view('administrator.blog-category.add', $data);
    }

    public function update(BlogCategoryStoreRequest $request, BlogCategory $blogCategory)
    {
        $blogCategory->update($request->validated());

        return redirect(route('admin.blog-category.index'))->with('message', 'Berhasil diedit');
    }

    public function delete(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return redirect(route('admin.blog-category.index'))->with('message', 'Berhasil dihapus');
    }
}
