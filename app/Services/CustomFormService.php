<?php
namespace App\Services;

use App\Models\CustomForm;
use App\Models\CustomFormColumn;
use Illuminate\Support\Str;

class CustomFormService
{
    public function getById($id)
    {
        return CustomForm::with('unit', 'periods', 'columns')->find($id);
    }

    public function filter($params =[], $limit = 15)
    {
        $query = CustomForm::with('unit', 'periods')->orderBy('created_at', 'desc');

        if (isset($params['name']) && $params['name']) {
            $query->where('name', 'like', '%'. $params['name'] .'%');
        }

        if (isset($params['unit_id']) && $params['unit_id']) {
            $query->where('unit_id', $params['unit_id']);
        }

        if (isset($params['period_id']) && $params['period_id']) {
            $query->where('period_id', $params['period_id']);
        }

        return $query->paginate($limit)->appends($params);
    }

    public function create(array $input)
    {
        $customForm = new CustomForm();
        $customForm->fill([
            'name' => $input['name'],
            'unit_id' => $input['unit_id'],
        ]);
        $customForm->save();

        return $customForm;
    }

    public function update($id, array $input)
    {
        $customForm = CustomForm::find($id);
        $customForm->fill([
            'name' => $input['name'],
            'unit_id' => $input['unit_id'],
        ]);
        $customForm->save();

        return $customForm;
    }

    public function syncPeriod($customForm, array $input)
    {
        $customForm->periods()->sync($input['period_id'] ?? []);
    }

    public function syncCustomColumn($customForm, array $input)
    {
        $countColumn = count($input['column_ids']);

        $savedIds = collect();
        for ($i=0; $i < $countColumn; $i++) {
            $column = CustomFormColumn::updateOrCreate(
                [
                    'custom_form_id' => $customForm->id,
                    'id' => $input['column_ids'][$i]
                ], [
                    'label' => $input['label'][$i],
                    'type' => $input['type'][$i],
                    'order' => $input['order'][$i],
                ]
            );

            $savedIds->push($column->id);
        }

        return CustomFormColumn::where('custom_form_id', $customForm->id)->whereNotIn('id', $savedIds)->delete();
    }
}
