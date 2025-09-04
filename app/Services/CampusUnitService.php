<?php
namespace App\Services;

use App\Models\Campus;
use App\Models\CampusUnit;
use App\Models\Unit;
use App\Traits\ImageHandler;

class CampusUnitService
{
    use ImageHandler;

    public function generateIndexData($campusId, $nav) {
        $campus = Campus::with([
                        'campusUnits.unit' => function ($query) {
                            $query->select('id','name');
                        }])
                    ->where('id',$campusId)
                    ->firstOrFail();                    
        return [
            'nav' => $nav,
            'campus' => $campus,
        ];
    }

    public function generateAddingData($campusId, $nav)
    {
        $campus = Campus::where('id', $campusId)->firstOrFail();
        $units = Unit::orderBy('id')->get();
        $campusUnit = new CampusUnit();
        $campusUnit->campus = $campus;
        return [
            'nav' => $nav,
            'campus' => $campus,
            'units' => $units,
            'campusUnit' => $campusUnit
        ];
    }

    public function generateEditableData($campusId, $id, $nav)
    {
        $campus = Campus::where('id', $campusId)->firstOrFail();
        $campusUnit = CampusUnit::where('id', $id)->firstOrFail();
        $units = Unit::orderBy('id')->get();
        return [
            'nav' => $nav,
            'campus' => $campus,
            'units' => $units,
            'campusUnit' => $campusUnit,
            'status' => 'edit'
        ];
    }

    private function params($params)
    {
        if (isset($params['image_path']) && request()->hasFile('image_path')) {
            if ($params['image_path'] && $image = $this->uploadImage(request()->file('image_path'))) {
                if (isset($params['current_image_path']) && $this->imageExists($params['current_image_path'])) {
                    $this->deleteImage($params['current_image_path']);
                }
                $params['image_path'] = $image;
            }
        } else {
            if (isset($params['current_image_path']) && $this->imageExists($params['current_image_path'])) {
                $params['image_path'] = $params['current_image_path'];
            }
        }

        if (isset($params['image_potrait_path']) && request()->hasFile('image_potrait_path')) {
            if ($params['image_potrait_path'] && $image = $this->uploadImage(request()->file('image_potrait_path'))) {
                if (isset($params['current_image_potrait_path']) && $this->imageExists($params['current_image_potrait_path'])) {
                    $this->deleteImage($params['current_image_potrait_path']);
                }
                $params['image_potrait_path'] = $image;
            }
        } else {
            if (isset($params['current_image_potrait_path']) && $this->imageExists($params['current_image_potrait_path'])) {
                $params['image_potrait_path'] = $params['current_image_potrait_path'];
            }
        }

        if (isset($params['image_landscape_path']) && request()->hasFile('image_path')) {
            if ($params['image_landscape_path'] && $image = $this->uploadImage(request()->file('image_landscape_path'))) {
                if (isset($params['current_image_landscape_path']) && $this->imageExists($params['current_image_landscape_path'])) {
                    $this->deleteImage($params['current_image_landscape_path']);
                }
                $params['image_landscape_path'] = $image;
            }
        } else {
            if (isset($params['current_image_landscape_path']) && $this->imageExists($params['current_image_landscape_path'])) {
                $params['image_landscape_path'] = $params['image_landscape_path'];
            }
        }

        $imageService = new ImageService();

        if (isset($params['about'])) {
            $params['about'] = $imageService->filterUploadHTML($params['about'], 'content_image');
        }

        if (isset($params['current_about'])) {
            $imageService->filterUpdateHTML($params['current_about'], $params['about']);
        }

        if (isset($params['keunggulan'])) {
            $params['keunggulan'] = $imageService->filterUploadHTML($params['keunggulan'], 'content_image');
        }

        if (isset($params['current_keunggulan'])) {
            $imageService->filterUpdateHTML($params['current_keunggulan'], $params['keunggulan']);
        }

        return $params;
    }

    private function uploadImage($file)
    {
        $type = 'featured_image';
        if ($upload = $this->doUploadImage($file, $type)) {
            return $upload['path_upload'];
        }

        return false;
    }

    public function create($campusId, $params)
    {
        $campus = Campus::where('id', $campusId)->firstOrFail();
        $params = $this->params($params);
        $campusUnit = CampusUnit::create($params);

        return $campusUnit;
    }

    public function update($campusId, $id, $params)
    {
        $campus = Campus::where('id', $campusId)->firstOrFail();
        $campusUnit = CampusUnit::where('id', $id)->firstOrFail();

        $params['current_image_path'] = $campusUnit->image_path;
        $params['current_image_landscape_path'] = $campusUnit->image_landscape_path;
        $params['current_image_potrait_path'] = $campusUnit->image_potrait_path;
        $params['current_about'] = $campusUnit->about;
        $params['current_keunggulan'] = $campusUnit->keunggulan;
        $params = $this->params($params);

        $campusUnit->fill($params);
        return $campusUnit->save();
    }

    public function delete($campusId, $id)
    {
        $campus = Campus::where('id', $campusId)->firstOrFail();
        $campusUnit = CampusUnit::where('id', $id)->firstOrFail();

        $imageService = new ImageService();
        $imageService->filterDeleteHTML($campusUnit->about, 'content_image');
        $imageService->filterDeleteHTML($campusUnit->keunggulan, 'content_image');

        if ($this->imageExists($campusUnit->image_path)) {
            $this->deleteImage($campusUnit->image_path);
        }

        return $campusUnit->delete();
    }
}