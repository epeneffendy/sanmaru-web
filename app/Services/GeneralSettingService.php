<?php

namespace App\Services;

use App\Models\GeneralSettings;

class GeneralSettingService {

    public function getBySlug($slug){
        $data = GeneralSettings::where('slug', $slug)->first();
        return $data;
    }
}
