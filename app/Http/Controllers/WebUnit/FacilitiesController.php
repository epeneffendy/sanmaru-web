<?php

namespace App\Http\Controllers\WebUnit;

use App\Models\Facility;
use App\Models\FacilityCategory;

class FacilitiesController extends BaseController
{
    public function index()
    {
        $unitIds = $this->units->pluck('id')->all();

        $categories = FacilityCategory::with([
                    'facilities',
                    'facilities.galleries'
                ])
                ->whereHas('facilities', function ($query) use ($unitIds) {
                    return $query->whereIn('unit_id', $unitIds);
                })
                ->get();
        $categories = $categories->each(function($category, $key) use($unitIds) {
            $category->facilities = $category->facilities->whereIn('unit_id', $unitIds);
        });
        $data = [
            'categories' => $categories,
        ];

        return $this->view('facilities', $data);
    }
}
