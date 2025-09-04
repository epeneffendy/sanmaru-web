<?php

namespace App\Services;

use App\Traits\ImageHandler;
use Illuminate\Support\Str;
use App\Models\SchoolLife;
use App\Helpers\Helper;

class SchoolLifeService
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

    public function create($params)
    {
        $params['slug'] = Str::slug($params['title']);
        $params = $this->params($params);
        $schoolLife = SchoolLife::create($params);

        return $schoolLife;
    }

    public function update($id, $params)
    {
        $schoolLife = SchoolLife::findOrFail($id);

        $params['current_featured_image'] = $schoolLife->featured_image;
        $params['current_content'] = $schoolLife->content;
        $params = $this->params($params);

        $schoolLife->fill($params);
        return $schoolLife->save();
    }

    public function delete(SchoolLife $schoolLife)
    {
        $imageService = new ImageService();
        $imageService->filterDeleteHTML($schoolLife->content, 'content_image');

        if ($this->imageExists($schoolLife->featured_image)){
            $this->deleteImage($schoolLife->featured_image);
        }
        return $schoolLife->delete();
    }
}
