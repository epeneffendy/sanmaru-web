<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeriodRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $period = explode(' - ', $this->period);

        $this->merge([
            'active' => $this->active == 'true' ? true : false,
            'start_date' => $period[0],
            'end_date' => $period[1]
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $arr = [
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'unit_id' => 'required|integer|exists:units,id',
            'class_id' => 'nullable|integer|exists:classes,id',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y|after_or_equal:start_date',
            'quota' => 'nullable|integer|max:999',
            'start_register_number' => 'nullable|integer|max:999',
            'active' => 'required',
            'school_year' => 'required|date_format:Y',
            'show_registration_popup' => 'nullable',
            'popup_content' => 'nullable',
        ];

        if ($this->has('is_feeder_school') && $this->is_feeder_school) {
            $arr['origin_school_options'] = 'required_without:additional_origin_school|array';
            $arr['additional_origin_school'] = 'required_without:origin_school_options|nullable|string';
        }

        return $arr;
    }

    public function attributes()
    {
        return [
            'origin_school_options' => 'pilihan sekolah asal',
            'additional_origin_school' => 'sekolah asal tambahan',
            'school_year' => 'tahun mulai ajaran',
        ];
    }
}
