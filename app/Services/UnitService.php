<?php

namespace App\Services;

use App\Traits\ImageHandler;
use App\Models\Unit;
use DB;

class UnitService
{
    use ImageHandler;

    public function create($params)
    {
        DB::transaction(function() use ($params) {
            $params = $this->params($params);
            $unit = Unit::create($params);

            $unit->syncCosts($params);
            $unit->syncTestimonies($params['detail']);

            return $unit;
        });
    }

    public function update($id, $params)
    {
        DB::transaction(function() use ($id, $params) {
            $unit = Unit::findOrFail($id);
            $params = $this->params($params);

            $unit->syncCosts($params);
            $unit->syncTestimonies($params['detail']);
            $unit->fill($params);

            return $unit->save();
        });
    }

    public function updateByUnitCode($params)
    {
        $unit = Unit::where('unit_code', $params['unit_code'])->firstOrFail();
        return $unit->update($params);
    }

    private function params($params)
    {
        if (isset($params['image_path']) && $params['image_path'] && $image = $this->uploadImage(request(), $params)) {
            $params['image_path'] = $image;
        }

        if (isset($params['banner_path']) && $params['banner_path'] && $banner = $this->uploadBanner(request(), $params)) {
            $params['banner_path'] = $banner;
        }

        if (isset($params['keunggulan_path']) && $params['keunggulan_path'] && $keunggulan = $this->uploadKeunggulan(request(), $params)) {
            $params['keunggulan_path'] = $keunggulan;
        }

        $params['detail'] = [];
        if (isset($params['subjects']) && count($params['subjects'])) {
            $i=0;
            foreach ($params['subjects'] as $key => $val) {
                if (isset($val)) {
                    $params['detail'][$i] = [
                        'subject' => $val,
                        'job' => isset($params['jobs'][$key]) ? $params['jobs'][$key] : '',
                        'content' => isset($params['contents'][$key]) ? $params['contents'][$key] : '',
                        'id' => isset($params['testimony_ids'][$key]) ? $params['testimony_ids'][$key] : '',
                    ];

                    if (isset($params['photo_paths'][$i])) {
                        $params['detail'][$i]['photo_path'] = $this->uploadphoto(request(), $params, $i);
                    }
                    $i++;
                }
            }
        }

        if (isset($params['phone'])) {
            $params['phone'] = app('phoneNormalizerService')->normalize($params['phone']);
        }
        return $params;
    }

    private function uploadImage($request, $params)
    {
        if ($request->hasFile('image_path')) {
            $type = 'unit';
            if ($upload = $this->doUploadImage($request->file('image_path'), $type)) {
                return $upload['path_upload'];
            }
        }

        return false;
    }

    private function uploadBanner($request, $params)
    {
        if ($request->hasFile('banner_path')) {
            $type = 'banner';
            if ($upload = $this->doUploadImage($request->file('banner_path'), $type)) {
                return $upload['path_upload'];
            }
        }

        return false;
    }

    private function uploadKeunggulan($request, $params)
    {
        if ($request->hasFile('keunggulan_path')) {
            $type = 'keunggulan';
            if ($upload = $this->doUploadImage($request->file('keunggulan_path'), $type)) {
                return $upload['path_upload'];
            }
        }

        return false;
    }

    private function uploadPhoto($request, $params, $key)
    {
        if ($request->hasFile('photo_paths')) {
            $type = 'photo';
            $files = $request->file('photo_paths');
            //foreach($files as $file){
                if (isset($files[$key])) {
                    if ($upload = $this->doUploadImage($files[$key], $type)) {
                        return $upload['path_upload'];
                    }
                }
            //}
        }

        return null;
    }
}
