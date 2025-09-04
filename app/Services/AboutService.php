<?php

namespace App\Services;

use App\Models\AboutCategory;
use App\Traits\ImageHandler;
use App\Helpers\Helper;
use App\Models\About;
use App\Models\Unit;
use Carbon\Carbon;

class AboutService
{
    use ImageHandler;

    private function params($params)
    {
        if (isset($params['featured_image'])) {
            if ($params['featured_image'] && $image = $this->uploadImage(request(), $params)) {
                if (isset($params['current_featured_image']) && $this->imageExists($params['current_featured_image'])) {
                    $this->deleteImage($params['current_featured_image']);
                }
                $params['featured_image'] = $image;
            }
        } else {
            if (isset($params['current_featured_image']) && $this->imageExists($params['current_featured_image'])) {
                $params['featured_image'] = $params['current_featured_image'];
            }
        }

        if (isset($params['content'])) {
            $imageService = new ImageService();
            $params['content'] = $imageService->filterUploadHTML($params['content'], 'content_image');
        }

        if (isset($params['current_content'])) {
            $imageService = new ImageService();
            $imageService->filterUpdateHTML($params['current_content'], $params['content']);
        }

        if (isset($params['publish_date'])) {
            $params['publish_date'] = Carbon::parse($params['publish_date'])->toDateTimeString();
        }

        if (!Helper::canPublishArticle() && isset($params['published'])) {
            unset($params['published']);
        }

        return $params;
    }

    private function uploadImage($request, $params)
    {
        if ($request->hasFile('featured_image')) {
            $type = 'featured_image';
            if ($upload = $this->doUploadImage($request->file('featured_image'), $type)) {
                return $upload['path_upload'];
            }
        }

        return false;
    }

    public function generateIndexData($categorySlug, $nav) 
    {
        $aboutCategory = AboutCategory::where('slug',$categorySlug)->firstOrFail();
        $abouts = About::where('about_category_id', $aboutCategory->id);

        if (request()->unit) {
            $abouts = $abouts->whereHas('unit', function ($query) {
                $query->byUserRole()->where('id', request()->unit);
            });
        }

        if (request()->search) {
            $abouts = $abouts->where('title','like', '%'.request()->search.'%');
        }

        $abouts = $abouts->get();

        return [            
            'data' => $abouts,
            'category' => $aboutCategory,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'nav' => $nav,
            'params' => request()->except(['page']),
        ];
    }

    public function generateAddingData($categorySlug, $nav)
    {
        $aboutCategory = AboutCategory::where('slug',$categorySlug)->firstOrFail();
        $about = new About();
        $about->category = $aboutCategory;

        return [
            'about' => $about,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'nav' => $nav
        ];
    }

    public function generateEditableData($categorySlug, $slug, $nav)
    {
        $aboutCategory = AboutCategory::where('slug',$categorySlug)->firstOrFail();
        $about = About::where('slug',$slug)->firstOrFail();
        $about->category = $aboutCategory;
        return [
            'status' => 'edit',
            'about' => $about,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'nav' => $nav
        ];
    }

    public function generateCategoryData($nav)
    {
        return [
            'nav' => $nav,
            'categories' => AboutCategory::active()
                    ->withCount('abouts')
                    ->orderBy('order')
                    ->get()
        ];
    }

    public function create($categorySlug, $params)
    {
        $aboutCategory = AboutCategory::where('slug',$categorySlug)->firstOrFail();
        $params = $this->params($params);
        $about = About::create($params);

        return $about;
    }

    public function update($categorySlug, $slug, $params)
    {
        $aboutCategory = AboutCategory::where('slug',$categorySlug)->firstOrFail();
        $about = About::where('slug',$slug)->firstOrFail();

        $params['current_featured_image'] = $about->featured_image;
        $params['current_content'] = $about->content;
        $params = $this->params($params);

        $about->fill($params);
        return $about->save();
    }

    public function delete($categorySlug, $slug)
    {
        $aboutCategory = AboutCategory::where('slug',$categorySlug)->firstOrFail();
        $about = About::where('slug',$slug)->firstOrFail();

        $imageService = new ImageService();
        $imageService->filterDeleteHTML($about->content, 'content_image');
        
        if ($this->imageExists($about->featured_image)){
            $this->deleteImage($about->featured_image);
        }
        return $about->delete();
    }
}
