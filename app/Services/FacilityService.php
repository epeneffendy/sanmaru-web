<?php 

namespace App\Services;

use App\Models\Facility;
use App\Models\FacilityCategory;
use App\Models\Unit;
use App\Models\Gallery;

class FacilityService 
{
    public function generateIndexData($nav)
    {
        $datas = Facility::orderBy('unit_id', 'asc')
                        ->orderBy('id', 'asc');

        if (request()->unit) {
            $datas = $datas->whereHas('unit', function ($query) {
                $query->byUserRole()->where('id', request()->unit);
            });
        }
        if (request()->category) {
            $datas = $datas->where('facility_category_id', request()->category);
        }
        if (request()->search) {
            $datas = $datas->where('name', 'like', '%'.request()->search.'%');
        }

        $datas = $datas->paginate();

        return [
            'datas' => $datas,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'categories' => FacilityCategory::pluck('name', 'id')->all(),
            'nav' => $nav,
            'params' => request()->except(['page']),
        ];
    }

    public function generateAddingData($nav)
    {
        $data = new FacilityCategory;
        return [
            'data' => $data,
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'categories' => FacilityCategory::pluck('name', 'id')->all(),
            'nav' => $nav,
        ];
    }

    public function generateEditableData($id, $nav)
    {
        $data = Facility::with(['category', 'galleries'])->where('id', $id)->firstOrFail();
        return [
            'status' => 'edit',
            'units' => Unit::byUserRole()->pluck('name', 'id')->all(),
            'categories' => FacilityCategory::pluck('name', 'id')->all(),
            'data' => $data,
            'nav' => $nav,
        ];
    }

    public function create($params)
    {
        $data = Facility::create($params);
        if (isset($params['gallery_ids']) && $params['gallery_ids']) {
            $data->galleries()->sync($params['gallery_ids']);
        }
        return $data;
    }

    public function update($id, $params)
    {
        $data = Facility::where('id', $id)->firstOrFail();
        $data->fill($params);
        $data->save();

        if (isset($params['gallery_ids']) && $params['gallery_ids']) {
            $data->galleries()->sync($params['gallery_ids']);
        }

        return $data;
    }

    public function delete($id)
    {
        $data = Facility::where('id', $id)->firstOrFail();
        $data->galleries()->detach();
        return $data->delete();
    }

    public function getGalleries()
    {
        $datas = Gallery::latest()->paginate(3);
        return [
            'datas' => $datas,
        ];
    }
}