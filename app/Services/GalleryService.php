<?php
namespace App\Services;

use App\Traits\ImageHandler;
use App\Helpers\Helper;
use App\Models\Gallery;
use App\Models\Unit;
use Auth;

class GalleryService
{
    use ImageHandler;

    private function params($params)
    {
        if (!isset($params['content_url']) && isset($params['current_content_url']) && $this->imageExists($params['current_content_url'])) {
            $params['content_url'] = $params['current_content_url'];
            return $params;
        }

        if (isset($params['content_url']) && $params['content_url'] && $image = $this->uploadImage(request(), $params)) {
            if (isset($params['current_content_url']) && $this->imageExists($params['current_content_url'])) {
                $this->deleteImage($params['current_content_url']);
            }
            $params['content_url'] = $image;
        }

        if (!Helper::canPublishArticle() && isset($params['published'])) {
            unset($params['published']);
        }

        return $params;
    }

    private function uploadImage($request, $params)
    {
        if ($request->hasFile('content_url')) {
            $type = 'gallery_image';
            if ($upload = $this->doUploadImage($request->file('content_url'), $type)) {
                return $upload['path_upload'];
            }
        }

        return false;
    }

    public function generateIndexData($nav)
    {
        $galleries = Gallery::orderBy('created_at', 'desc');

        if (request()->title) {
            $galleries = $galleries->where('title', 'like', '%' . request()->title . '%');
        }

        if (request()->unit) {
            $galleries = $galleries->whereHas('unit', function ($query) {
                $query->byUserRole()->where('id', request()->unit);
            });
        }

        $galleries = $galleries->paginate(5);

        return [
            'nav'       => $nav,
            'galleries' => $galleries,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'params'    => request()->except(['page']),
        ];
    }

    public function generateAddingData($nav)
    {   
        return [
            'gallery' => '',
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'nav' => $nav,
        ];
    }

    public function generateShowingData($id, $nav)
    {
        $gallery = Gallery::where('id', $id)->firstOrFail();
        return [
            'status'    => 'show',
            'gallery'   => $gallery,
            'nav'       => $nav
        ];
    }

    public function generateEditableData($id, $nav)
    {
        $gallery = Gallery::where('id', $id)->firstOrFail();
        return [
            'status'    => 'edit',
            'gallery'   => $gallery,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'nav'       => $nav
        ];
    }

    public function create($params)
    {
        $user = Auth::user();
        $params = $this->params($params);
        $params['user_id'] = $user->id;
        $gallery = Gallery::create($params);

        return $gallery;
    }

    public function update($id, $params)
    {
        $gallery = Gallery::where('id', $id)->firstOrFail();

        $params['current_content_url'] = $gallery->content_url;
        $params = $this->params($params);

        $gallery->fill($params);
        return $gallery->save();
    }

    public function delete($id)
    {
        $gallery = Gallery::where('id', $id)->firstOrFail();
        if ($this->imageExists($gallery->content_url)){
            $this->deleteImage($gallery->content_url);
        }
        return $gallery->delete();
    }

    public function toggleStatus($id)
    {
        $gallery = Gallery::where('id', $id)->firstOrFail();
        $gallery->published = $gallery->isPublished() ? 0 : 1;
        $gallery->save();
        return $gallery->status;
    }
}
