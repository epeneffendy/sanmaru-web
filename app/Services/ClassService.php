<?php

namespace App\Services;

use App\Models\Classes;
use App\Models\Unit;

class ClassService
{
    public function create($params)
    {
        return Classes::create($params);
    }

    public function delete($id)
    {
        $class = Classes::findOrFail($id);
        return $class->delete();
    }

    public function createOrThrowByUnitClass($unitClass, $params)
    {
        $class = Classes::where('unit_class', $unitClass)->first();
        throw_unless(!$class, new \Exception('Class sudah ada'));
        return $this->create($params);
    }

    public function update($id, $params)
    {
        $class = Classes::findOrFail($id);
        $class->fill($params);
        return $class->save();
    }

    public function updateByUnitClass($unitClass, $params)
    {
        $class = Classes::where('unit_class', $unitClass)->firstOrFail();
        $class->fill($params);
        return $class->save();
    }

    public function generateAddingData($nav)
    {
        return [
            'class' => false,
            'unitList' => Unit::pluck('name', 'id'),
            'nav' => $nav
        ];
    }

    public function generateEditableData($id, $nav)
    {
        return [
            'method' => 'edit',
            'class' => Classes::findOrFail($id),
            'unitList' => Unit::pluck('name', 'id'),
            'nav' => $nav
        ];
    }
}
