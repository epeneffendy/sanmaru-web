<?php
namespace App\Http\Controllers\WebUnit;

use App\Models\About;
use App\Models\CampusUnit;

class AboutController extends BaseController
{
    public function history()
    {
        if (request()->has('unit') && request()->input('unit')) {
            $unit = $this->units->filter(function ($unit) {
                return $unit->webunit_level == request()->input('unit');
            })->first();
        } else {
            $unit = $this->units->first();
        }

        if (! $unit) {
            abort(404);
        }

        $abouts = About::published()
                    ->select(['title', 'about_category_id', 'short_desc', 'featured_image', 'slug', 'content'])
                    ->where('unit_id', $unit->id)
                    ->whereHas('category', function ($query) {
                        $query->where('slug', 'like' , 'sejarah%');
                    })->get();

        $data = [
            'abouts' => $abouts,
            'webunit_level' => $unit->webunit_level
        ];

        return $this->view('about.history', $data);
    }

    public function about()
    {
        if (request()->has('unit') && request()->input('unit')) {
            $unit = $this->units->filter(function ($unit) {
                return $unit->webunit_level == request()->input('unit');
            })->first();
        } else {
            $unit = $this->units->first();
        }

        if (! $unit) {
            abort(404);
        }

        $campusUnit = CampusUnit::where('unit_id', $unit->id)->first();

        $data = [
            'campusUnit' => $campusUnit,
            'webunit_level' => $unit->webunit_level
        ];

        return $this->view('about.about', $data);
    }

    public function welcome()
    {
        if (request()->has('unit') && request()->input('unit')) {
            $unit = $this->units->filter(function ($unit) {
                return $unit->webunit_level == request()->input('unit');
            })->first();
        } else {
            $unit = $this->units->first();
        }

        if (! $unit) {
            abort(404);
        }

        $campusUnit = CampusUnit::where('unit_id', $unit->id)->first();

        $abouts = About::published()
                    ->select(['title', 'about_category_id', 'short_desc', 'featured_image', 'slug', 'content'])
                    ->where('unit_id', $unit->id)
                    ->whereHas('category', function ($query) {
                        $query->where('slug', 'like' , 'sambutan-ketua-yayasan%');
                    })->get();

        $data = [
            'campusUnit' => $campusUnit,
            'abouts' => $abouts,
            'webunit_level' => $unit->webunit_level
        ];

        return $this->view('about.welcome', $data);
    }

    public function coreValues() {
        if (request()->has('unit') && request()->input('unit')) {
            $unit = $this->units->filter(function ($unit) {
                return $unit->webunit_level == request()->input('unit');
            })->first();
        } else {
            $unit = $this->units->first();
        }

        if($unit->webunit_slug != 'sma-sby') {
            abort(404);
        }

        return $this->view('about.core-values');
    }
}
