<?php
namespace App\Services;

use App\Traits\ImageHandler;
use Illuminate\Support\Str;
use App\Helpers\Helper;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Unit;

class BlogService
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

    public function generateIndexData($nav)
    {
        $blogs = Blog::with('category')->orderBy('publish_date', 'DESC');

        if (request()->unit) {
            $blogs = $blogs->whereHas('unit', function ($query) {
                $query->byUserRole()->where('id', request()->unit);
            });
        }

        if (request()->search) {
            $blogs = $blogs->where('title', 'like', '%'.request()->search.'%');
        }

        $blogs = $blogs->paginate(15);

        return [
            'nav' => $nav,
            'blogs' => $blogs,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'params' => request()->except(['page']),
        ];
    }

    public function generateAddingData($nav)
    {
        return [
            'blogCategories' => BlogCategory::active()->get(),
            'blog' => '',
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'nav' => $nav,
            'params' => request()->except(['page']),
        ];
    }

    public function generateEditableData(Blog $blog, $nav)
    {
        return [
            'status' => 'edit',
            'blog' => $blog,
            'blogCategories' => BlogCategory::active()->get(),
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'nav' => $nav
        ];
    }

    public function create($params)
    {
        $params['slug'] = Str::slug($params['title']);
        $params = $this->params($params);
        $blog = Blog::create($params);

        return $blog;
    }

    public function update($id, $params)
    {
        $blog = Blog::findOrFail($id);

        $params['current_featured_image'] = $blog->featured_image;
        $params['current_content'] = $blog->content;
        $params = $this->params($params);

        $blog->fill($params);
        return $blog->save();
    }

    public function delete(Blog $blog)
    {
        $imageService = new ImageService();
        $imageService->filterDeleteHTML($blog->content, 'content_image');

        if ($this->imageExists($blog->featured_image)){
            $this->deleteImage($blog->featured_image);
        }
        return $blog->delete();
    }

    public function countBlogs()
    {
        return Blog::count('id');
    }

    public function listBlogs($offset, $limit, $category, $published)
    {
        $datas = new Blog();
        if (isset($offset)) {
            $datas = $datas->offset($offset);
        }
        if (isset($limit)) {
            $datas = $datas->limit($limit);
        }
        if (isset($category)) {
            $datas = $datas->whereHas('category', function($query) use ($category) {
                return $query->where('slug', $category);
            });
        }
        if (isset($published)) {
            $datas = $datas->where('published', $published ? 1 : 0);
        }

        $collect = collect();
        foreach ($datas->with('category')->orderBy('publish_date', 'DESC')->get() as $data) {
            $collect->push($this->_blog($data));
        }

        return $collect;
    }

    public function getBlog($slug)
    {
        $data = Blog::where('slug', $slug)->with('category')->first();
        return $this->_blog($data);
    }

    private function _blog($data)
    {
        if (!$data) {
            return false;
        }

        return [
            'title' => $data->title,
            'short_desc' => $data->short_desc,
            'slug' => $data->slug,
            'category' => $data->category->name,
            'category_slug' => $data->category->slug,
            'content' => $data->html_content,
            'publish_date' => $data->publish_date,
            'published' => $data->published,
            'featured_image' => $data->getFeaturedImageUrl(),
        ];
    }
}
