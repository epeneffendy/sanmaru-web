<?php
namespace App\Services;

use App\Traits\ImageHandler;
use App\Models\Headline;
use App\Helpers\Helper;
use App\Models\Unit;

class HeadlineService
{
    use ImageHandler;

    private function params($params)
    {
        if ($params['type'] === "image"){
            if (!isset($params['content_img']) && isset($params['current_content_img']) && $this->imageExists($params['current_content_img'])) {
                $params['content_url'] = $params['current_content_img'];
                return $params;
            }

            if (isset($params['content_img']) && $params['content_img'] && $image = $this->uploadImage(request(), $params)) {
                if (isset($params['current_content_img']) && $this->imageExists($params['current_content_img'])) {
                    $this->deleteImage($params['current_content_img']);
                }
                $params['content_url'] = $image;
            }
        } else {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $params['content_url'], $match)) {
                $video_id = $match[1];
                $params['content_url'] = $video_id;
            }
        }

        if (!Helper::canPublishArticle() && isset($params['published'])) {
            unset($params['published']);
        }

        return $params;
    }

    private function uploadImage($request, $params)
    {
        if ($request->hasFile('content_img')) {
            $type = 'headline_image';
            if ($upload = $this->doUploadImage($request->file('content_img'), $type)) {
                return $upload['path_upload'];
            }
        }

        return false;
    }

    public function generateIndexData($nav)
    {
        $headlines = Headline::with('unit')->orderBy('updated_at','desc')->get();

        return [
            'nav' => $nav,
            'headlines' => $headlines,
        ];
    }

    public function generateAddingData($nav)
    {
        $units = Unit::orderBy('id')->get()->pluck('name', 'id');
        return [
            'unitOption' => $units,
            'headline' => '',
            'nav' => $nav,
        ];
    }

    public function generateEditableData($id, $nav)
    {
        $headline = Headline::where('id', $id)->firstOrFail();
        $units = Unit::orderBy('id')->get()->pluck('name','id');
        return [
            'status' => 'edit',
            'headline' => $headline,
            'unitOption' => $units,
            'nav' => $nav
        ];
    }

    public function create($params)
    {
        $params = $this->params($params);
        $headline = Headline::create($params);

        return $headline;
    }

    public function update($id, $params)
    {
        $headline = Headline::where('id',$id)->firstOrFail();

        if ($headline->type == "image") {
            $params['current_content_img'] = $headline->content_url;
        }

        $params = $this->params($params);

        $headline->fill($params);
        return $headline->save();
    }

    public function delete($id)
    {
        $headline = Headline::where('id',$id)->firstOrFail();
        if ($this->imageExists($headline->content_url)){
            $this->deleteImage($headline->content_url);
        }
        return $headline->delete();
    }

    public function toggleStatus($id)
    {
        $headline = Headline::where('id',$id)->firstOrFail();
        $headline->published = $headline->isPublished() ? 0 : 1;
        $headline->save();

        return $headline->status;
    }
}
