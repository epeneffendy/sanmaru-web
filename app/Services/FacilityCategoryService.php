<?php 

namespace App\Services;

use App\Models\FacilityCategory;

class FacilityCategoryService 
{
    public function generateIndexData($nav)
    {
        $datas = FacilityCategory::all();
        return [
            'datas' => $datas,
            'nav' => $nav,
        ];
    }

    public function generateAddingData($nav)
    {
        $data = new FacilityCategory;
        return [
            'data' => $data,
            'nav' => $nav,
        ];
    }

    public function generateEditableData($id, $nav)
    {
        $data = FacilityCategory::where('id', $id)->firstOrFail();
        return [
            'status' => 'edit',
            'data' => $data,
            'nav' => $nav,
        ];
    }

    public function create($params)
    {
        $data = FacilityCategory::create($params);
        return $data;
    }

    public function update($id, $params)
    {
        $data = FacilityCategory::where('id', $id)->firstOrFail();
        $data->fill($params);
        $data->save();
        return $data;
    }

    public function delete($id)
    {
        $data = FacilityCategory::where('id', $id)->firstOrFail();
        return $data->delete();
    }
}