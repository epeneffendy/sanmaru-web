<?php

namespace App\Http\Requests;

use App\Models\CustomFormColumn;
use App\Models\Period;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CustomFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'unit_id' => ['required', 'exists:units,id'],
            'slug' => [
                "unique:custom_forms,slug,{$this->id},id"
            ],
            'period_id' => ['required', 'array', 'min:1'],
            'label' => ['required', 'array', 'min:1'],
            'label.*' => ['required', 'string'],
            'type.*' => [
                'required',
                'in:'. CustomFormColumn::TYPE_TEXT .','. CustomFormColumn::TYPE_NUMBER
            ],
            'order.*' => ['nullable', 'numeric'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->get('name'))
        ]);
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'unit_id' => 'Unit',
            'period_id' => 'Periode',
            'label.*' => 'Label',
            'type.*' => 'Type',
            'order.*' => 'Order',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $period_ids = $this->get('period_id', []);
            $countPeriod = Period::whereIn('id', $period_ids)->count();

            if (count($period_ids) != $countPeriod) {
                $validator->errors()->add('period_id', 'Period not found');
            }
        });
    }
}
