<?php

namespace App\Services;

use App\Models\Extracurricular;
use App\Models\Classes;

class ExtracurricularService
{
    public function create($params)
    {
        return Extracurricular::create($params);
    }

    public function delete($id)
    {
        $extracurricular = Extracurricular::findOrFail($id);
        return $extracurricular->delete();
    }

    public function createOrThrowByCode($code, $params)
    {
        $extracurricular = Extracurricular::where('code', $code)->first();
        throw_unless(!$extracurricular, new \Exception('Extracurricular sudah ada'));
        return $this->create($params);
    }

    public function update($id, $params)
    {
        $extracurricular = Extracurricular::findOrFail($id);
        $extracurricular->fill($params);
        return $extracurricular->save();
    }

    public function updateByCode($code, $params)
    {
        $extracurricular = Extracurricular::where('code', $code)->firstOrFail();
        $extracurricular->fill($params);
        return $extracurricular->save();
    }

    public function generateAddingData($nav)
    {
        return [
            'extracurricular' => false,
            'classList' => Classes::pluck('name', 'id'),
            'nav' => $nav
        ];
    }

    public function generateEditableData($id, $nav)
    {
        return [
            'method' => 'edit',
            'extracurricular' => Extracurricular::findOrFail($id),
            'classList' => Classes::pluck('name', 'id'),
            'nav' => $nav
        ];
    }
}
