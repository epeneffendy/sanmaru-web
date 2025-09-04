<?php
namespace App\Http\Controllers\WebUnit;

use App\Models\Blog;

class NewsController extends BaseController
{
    public function index()
    {
        $unitIds = $this->units->pluck('id')->all();

        $popular = Blog::select(['title','slug','short_desc','featured_image','blog_category_id','created_at'])
                    ->with('category')
                    ->whereIn('unit_id', $unitIds)
                    ->published()->take(3)->get();

        $latest = Blog::select(['title','slug','short_desc','featured_image','blog_category_id','created_at'])
                    ->with('category')
                    ->whereIn('unit_id', $unitIds)
                    ->published()->take(9)->get();

        $data = [
            'popular' => $popular,
            'latest' => $latest,
        ];

        return $this->view('news.index', $data);
    }

    public function all()
    {
        $unitIds = $this->units->pluck('id')->all();

        $news = Blog::select(['title','slug','short_desc','featured_image','blog_category_id','created_at'])
                    ->with('category')
                    ->whereIn('unit_id', $unitIds)
                    ->published()->get();

        $data = [
            'news' => $news,
        ];

        return $this->view('news.all', $data);
    }

    public function show()
    {
        $unitIds = $this->units->pluck('id')->all();
        
        $slug = request()->route('slug');
        $news = Blog::whereIn('unit_id', $unitIds)
                    ->where('slug', $slug)
                    ->published()->first();

        $related = Blog::select(['title', 'slug', 'created_at', 'featured_image','blog_category_id'])
                    ->with('category')
                    ->whereIn('unit_id', $unitIds)
                    ->published()->take(2)->get();

        $data = [
            'news' => $news,
            'related' => $related,
        ];

        return $this->view('news.show', $data);
    }
}