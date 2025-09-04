<?php

namespace App\Http\Controllers\Admin;

use App\Models\AboutCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AboutCategoryRequest;

class AboutCategoryController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "about-category"
    ];

    public function index()
    {
        $data = [
            'nav' => $this->page,
            'aboutCategories' => AboutCategory::get()
        ];

        return view('administrator.about-category.list', $data);
    }

    public function add()
    {
        $data = [
            'aboutCategory' => '',
            'nav' => $this->page
        ];

        return view('administrator.about-category.add', $data);
    }

    public function insert(AboutCategoryRequest $request)
    {
        AboutCategory::create($request->validated());

        return redirect(route('admin.about.category.index'))->with('message', 'Berhasil ditambahkan');
    }

    public function edit($slug)
    {
        $aboutCategory = AboutCategory::where('slug',$slug)->firstOrFail();
        $data = [
            'status' => 'edit',
            'aboutCategory' => $aboutCategory,
            'nav' => $this->page
        ];

        return view('administrator.about-category.add', $data);
    }

    public function update(AboutCategoryRequest $request, $slug)
    {
        $aboutCategory = AboutCategory::where('slug',$slug)->firstOrFail();
        $aboutCategory->update($request->validated());
        return redirect(route('admin.about.category.index'))->with('message', 'Berhasil diupdate');
    }

    public function delete($slug)
    {
        $aboutCategory = AboutCategory::where('slug',$slug)->firstOrFail();
        $aboutCategory->delete();
        return redirect(route('admin.about.category.index'))->with('message', 'Berhasil dihapus');
    }

    public function updateOrder(Request $request)
    {
        if ($request->has('ids')) {
            $categoryIds = explode(',', $request->input('ids'));

            foreach($categoryIds as $index => $id) {
                $category = AboutCategory::where('id',$id)->firstOrFail();
                $category->order = $index;
                $category->save();
            }
            return response()->json(['success' => true, 'message' => 'berhasil diupdate']);
        }
    }
}
