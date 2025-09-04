<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgeLimitRequest extends FormRequest
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
        $this->merge([
            'active' => $this->has('active')
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
            'name' => 'required|max:255',
            'description' => 'nullable',
            'year' => 'required|numeric|min:1|max:60',
            'month' => 'required|numeric|min:0|max:12',
            'max_year' => 'required|numeric|min:1|max:60|gte:year',
            'max_month' => 'required|numeric|min:0|max:12|',
            'active' => 'required',
        ];

        if ($this->year && $this->month && $this->max_year && $this->max_month) {
            if ($this->year == $this->max_year) {
                $arr['max_month'] = 'required|numeric|min:0|max:12|gte:month';
            }
        }

        return $arr;
    }
}
