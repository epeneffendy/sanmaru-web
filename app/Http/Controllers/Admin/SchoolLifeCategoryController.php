<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SchoolLifeCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolLifeCategoryRequest;

class SchoolLifeCategoryController extends Controller
{
    private $page = [
        "parent" => "konten",
        "child" => "school-life-category"
    ];

    public function index()
    {
        $data = [
            'nav' => $this->page,
            'schoolLifeCategories' => SchoolLifeCategory::get()
        ];

        return view('administrator.school-life-category.list', $data);
    }

    public function add()
    {
        $data = [
            'schoolLifeCategory' => '',
            'nav' => $this->page
        ];

        return view('administrator.school-life-category.add', $data);
    }

    public function insert(SchoolLifeCategoryRequest $request)
    {
        SchoolLifeCategory::create($request->validated());

        return redirect(route('admin.school-life.category.index'))->with('message', 'Berhasil ditambahkan');
    }

    public function edit(SchoolLifeCategory $schoolLifeCategory)
    {
        $data = [
            'status' => 'edit',
            'schoolLifeCategory' => $schoolLifeCategory,
            'nav' => $this->page
        ];

        return view('administrator.school-life-category.add', $data);
    }

    public function update(SchoolLifeCategoryRequest $request, SchoolLifeCategory $schoolLifeCategory)
    {
        $schoolLifeCategory->update($request->validated());

        return redirect(route('admin.school-life.category.index'))->with('message', 'Berhasil diupdate');
    }

    public function delete(SchoolLifeCategory $schoolLifeCategory)
    {
        $schoolLifeCategory->delete();

        return redirect(route('admin.scool-life.category.index'))->with('message', 'Berhasil dihapus');
    }

    public function updateOrder(Request $request)
    {
        if ($request->has('ids')) {
            $categoryIds = explode(',', $request->input('ids'));

            foreach($categoryIds as $index => $id) {
                $category = SchoolLifeCategory::where('id',$id)->firstOrFail();
                $category->order = $index;
                $category->save();
            }
            return response()->json(['success' => true, 'message' => 'berhasil diupdate']);
        }
    }
}
