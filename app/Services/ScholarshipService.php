<?php

namespace App\Services;

use App\Models\Scholarship;
use App\Helpers\Helper;
use App\Models\Unit;

class ScholarshipService
{
    private function params($params)
    {
        if (isset($params['description'])) {
            $imageService = new ImageService();
            $params['description'] = $imageService->filterUploadHTML($params['description'], 'content_image');
        }

        if (isset($params['current_description'])) {
            $imageService = new ImageService();
            $imageService->filterUpdateHTML($params['current_description'], $params['description']);
        }

        if (!Helper::canPublishArticle() && isset($params['published'])) {
            unset($params['published']);
        }

        return $params;
    }

    public function generateIndexData($request, $nav)
    {
        $scholarships = new Scholarship();
        if ($request->input('name')) {
            $scholarships = $scholarships->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('unit')) {
            $scholarships = $scholarships->where('unit_id', $request->input('unit'));
        }

        $scholarships = $scholarships->with([
                                'unit' => function ($query) {
                                    $query->select('id','name');
                                }])
                                ->orderBy('id', 'desc')
                                ->paginate(10);
                                
        $units = Unit::select('name', 'id')->orderBy('id')->get();

        return [
            'scholarships'  => $scholarships,
            'units'         => $units,
            'nav'           => $nav,
            'params'        => $request->only(['unit'])
        ];
    }

    public function generateAddingData($nav)
    {
        $unitOptions = Unit::get()->pluck('name', 'id');
        return [
            'nav' => $nav,
            'scholarship' => '',
            'unitOptions' => $unitOptions
        ];
    }

    public function generateEditableData($id, $nav)
    {
        $scholarship = Scholarship::where('id', $id)->firstOrFail();
        $unitOptions = Unit::get()->pluck('name', 'id');
        return [
            'nav' => $nav,
            'status' => 'edit',
            'unitOptions' => $unitOptions,
            'scholarship' => $scholarship
        ];
    }

    public function generateShowData($id, $nav)
    {
        $scholarship = Scholarship::where('id', $id)->firstOrFail();
        return [
            'status'        => 'show',
            'scholarship'   => $scholarship,
            'nav'           => $nav
        ];
    }

    public function create($params)
    {
        $params = $this->params($params);
        $scholarship = Scholarship::create($params);

        return $scholarship;
    }

    public function update($id, $params)
    {
        $scholarship = Scholarship::where('id', $id)->firstOrFail();

        $params = $this->params($params);
        $params['current_description'] = $scholarship->description;
        $scholarship->fill($params);
        return $scholarship->save();
    }

    public function toggleStatus($id)
    {
        $scholarship = Scholarship::where('id', $id)->firstOrFail();
        $scholarship->published = $scholarship->isPublished() ? 0 : 1;
        $scholarship->save();
        return $scholarship->status;
    }

    public function delete($id)
    {
        $scholarship = Scholarship::where('id', $id)->firstOrFail();

        $imageService = new ImageService();
        $imageService->filterDeleteHTML($scholarship->description, 'content_image');

        return $scholarship->delete();
    }
}