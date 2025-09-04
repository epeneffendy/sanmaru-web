<?php

namespace App\Services;

use App\Models\Uniform;
use App\Models\Unit;

class UniformService
{
    public function generateAddingData($nav)
    {
        return [
            'uniform' => false,
            'unitList' => Unit::pluck('name', 'id'),
            'nav' => $nav
        ];
    }

    public function generateEditableData($id, $nav)
    {
        return [
            'method' => 'edit',
            'uniform' => Uniform::findOrFail($id),
            'unitList' => Unit::pluck('name', 'id'),
            'nav' => $nav
        ];
    }

    public function create($params)
    {
        return Uniform::create($params);
    }

    public function update($id, $params)
    {
        $uniform = Uniform::findOrFail($id);
        $uniform->fill($params);
        return $uniform->save();
    }

    public function delete($id)
    {
        $uniform = Uniform::findOrFail($id);
        return $uniform->delete();
    }

    public function toggleStatus($id)
    {
        $uniform = Uniform::findOrFail($id);
        $uniform->status = $uniform->isPublished() ? Uniform::STATUS_UNPUBLISHED : Uniform::STATUS_PUBLISHED;
        return $uniform->save();
    }
}
