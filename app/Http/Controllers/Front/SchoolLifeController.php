<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SchoolLife;
use App\Models\SchoolLifeCategory;
use App\Services\WebviewService;

class SchoolLifeController extends Controller
{
    private $page = ['parent' => 'school-life', 'child' => ''];

    public function pembelajaranDaring(){
        $data = ['nav' => $this->page];

        return view('webview.school-life.pembelajaran-daring', $data);
    }

    public function index() {
        $schoolLifeCategory = SchoolLifeCategory::active()->orderBy('order')->first();
        return redirect(route('web.school-life.category.show', $schoolLifeCategory->slug));
    }

    public function showByCategory($categorySlug, WebViewService $webviewService)
    {
        $category = SchoolLifeCategory::where('slug',$categorySlug)->firstOrFail();
        $this->page['child'] = $category->slug;
        $nav = $webviewService->getNavigations($this->page);
        $schoolLifes = SchoolLife::published()->where('category_id', $category->id)->get();
        $data = [
            'nav' => $nav,
            'schoolLifes' => $schoolLifes,
            'schoolLifeCategory' => $category
        ];
        return view('webview.school-life.show', $data);
    }

    public function show($categorySlug, $slug, WebViewService $webviewService){
        //
    }
}
