<?php

namespace App\Services;

use App\Models\AboutCategory;
use App\Models\SchoolLifeCategory;

class WebviewService 
{
    public function getNavigations($nav)
    {
        $aboutCategories = AboutCategory::active()->orderBy('order')->get();
        $schoolLifeCategories = SchoolLifeCategory::active()->orderBy('order')->get();
        return [
            'parent'    => $nav['parent'],
            'child'     => $nav['child'],
            'aboutCategories'      => $aboutCategories,
            'schoolLifeCategories' => $schoolLifeCategories,
        ];
    }
}