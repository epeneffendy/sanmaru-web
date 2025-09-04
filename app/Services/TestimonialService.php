<?php
namespace App\Services;

use App\Traits\ImageHandler;
use App\Models\Testimonial;
use App\Models\Unit;
use App\Helpers\Helper;

class TestimonialService
{
    use ImageHandler;

    private function params($params)
    {
        if (!isset($params['photo_path']) && isset($params['current_photo_path']) && $this->imageExists($params['current_photo_path'])) {
            $params['photo_path'] = $params['current_photo_path'];
            return $params;
        }

        if (isset($params['photo_path']) && $params['photo_path'] && $image = $this->uploadImage(request(), $params)) {
            if (isset($params['current_photo_path']) && $this->imageExists($params['current_photo_path'])) {
                $this->deleteImage($params['current_photo_path']);
            }
            $params['photo_path'] = $image;
        }

        if (!Helper::canPublishArticle() && isset($params['published'])) {
            unset($params['published']);
        }

        return $params;
    }

    private function uploadImage($request, $params)
    {
        if ($request->hasFile('photo_path')) {
            $type = 'testimonial_image';
            if ($upload = $this->doUploadImage($request->file('photo_path'), $type)) {
                return $upload['path_upload'];
            }
        }

        return false;
    }

    public function generateIndexData($nav)
    {
        $testimonials = Testimonial::orderBy('updated_at', 'desc');

        if (request()->unit) {
            $testimonials = $testimonials->whereHas('unit', function ($query) {
                $query->byUserRole()->where('id', request()->unit);
            });
        }

        if (request()->search) {
            $testimonials = $testimonials->where('subject', 'like' , '%'.request()->search.'%');
        }

        $testimonials = $testimonials->paginate();

        return [
            'nav' => $nav,
            'testimonials' => $testimonials,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'params' => request()->except(['page']),
        ];
    }

    public function generateAddingData($nav)
    {
        $testimonial = new Testimonial();
        return [
            'testimonial' => $testimonial,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'nav' => $nav,
        ];
    }

    public function generateEditableData($id, $nav)
    {
        $testimonial = Testimonial::where('id', $id)->firstOrFail();
        return [
            'status' => 'edit',
            'testimonial' => $testimonial,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'nav' => $nav
        ];
    }

    public function create($params)
    {
        $params = $this->params($params);
        $testimonial = Testimonial::create($params);

        return $testimonial;
    }

    public function update($id, $params)
    {
        $testimonial = Testimonial::where('id', $id)->firstOrFail();

        $params['current_photo_path'] = $testimonial->photo_path;

        $params = $this->params($params);

        $testimonial->fill($params);
        return $testimonial->save();
    }

    public function delete($id)
    {
        $testimonial = Testimonial::where('id', $id)->firstOrFail();
        if ($this->imageExists($testimonial->photo_path)){
            $this->deleteImage($testimonial->photo_path);
        }
        return $testimonial->delete();
    }

    public function toggleStatus($id)
    {
        $testimonial = Testimonial::where('id', $id)->firstOrFail();
        $testimonial->published = $testimonial->isPublished() ? 0 : 1;
        $testimonial->save();
        return $testimonial->status;
    }
}
