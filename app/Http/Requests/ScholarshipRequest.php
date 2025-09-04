<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScholarshipRequest extends FormRequest
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

    public function prepareForValidation()
    {
        $this->merge([
            'published' => $this->has('published')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $unit_id = request()->is_unit == true ? 'required|integer|exists:units,id' : 'nullable';
        return [
            'name' => 'required|string|max:255|min:10',
            'description' => 'required',
            'publish_date' => 'date|required',
            'unit_id' => $unit_id,
            'is_unit' => 'required',
            'published' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'is_unit.required'  => 'Pilih sebagai unit atau kampus',
            'published.required' => 'Pilih status publish'
        ];
    }
}
