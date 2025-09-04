<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Services\WebviewService;

class NewsController extends Controller
{
    private $page = ['parent'=>'news', 'child'=>''];

    public function index(Request $request, WebviewService $webviewService){

        $results = [];
        $latest = [];
        $popular = [];
        $nav = $webviewService->getNavigations($this->page);

        if ($request->input('title')) {
            $results = Blog::whereNull('unit_id')
                    ->published()
                    ->where('title', 'like', '%' . $request->input('title') . '%')
                    ->take(9)
                    ->get(['title','slug','featured_image']);
        } else {
            $latest = Blog::published()
                    ->whereNull('unit_id')
                    ->take(5)
                    ->get(['title','slug','created_at','short_desc','featured_image']);
            $popular = Blog::with('category')
                    ->whereNull('unit_id')
                    ->published()
                    ->take(9)
                    ->get();
        }

        return view('webview.news.index', [
            'nav' => $nav,
            'latest' => $latest,
            'popular' => $popular,
            'results' => $results,
        ]);
    }

    public function all(Request $request, WebviewService $webviewService)
    {
        $news = [];
        $nav = $webviewService->getNavigations($this->page);

        $news = Blog::published()
                ->whereNull('unit_id')
                ->get(['title','slug','created_at','short_desc','featured_image']);

        return view('webview.news.all', [
            'nav' => $nav,
            'news' => $news,
        ]);
    }

    public function show($slug, WebviewService $webviewService) {
        $nav = $webviewService->getNavigations($this->page);

        $blog = Blog::whereNull('unit_id')
                    ->where('slug', $slug)
                    ->firstOrFail();

        return view('webview.news.show',[
            'nav' => $nav,
            'blog' => $blog
        ]);
    }
}
