<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\AboutCategory;
use App\Services\WebviewService;

class AboutController extends Controller
{
    private $page = ['parent' => 'about', 'child' => ''];

    public function index() {
        $aboutCategory = AboutCategory::active()->orderBy('order')->first();
        return redirect(route('web.about.category.show', $aboutCategory));
    }

    public function showByCategory($categorySlug, WebViewService $webviewService)
    {
        $category = AboutCategory::where('slug',$categorySlug)->firstOrFail();
        $this->page['child'] = $category->slug;
        $nav = $webviewService->getNavigations($this->page);
        $abouts = About::published()
                    ->whereNull('unit_id')
                    ->where('about_category_id', $category->id)
                    ->get();
        $data = [
            'nav' => $nav,
            'abouts' => $abouts,
            'aboutCategory' => $category
        ];
        return view('webview.about.show', $data);
    }

    public function show($categorySlug, $slug, WebViewService $webviewService){
        //
    }
}
