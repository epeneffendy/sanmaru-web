<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PopupRequest extends FormRequest
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
        $arr = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published' => 'required',
            'publish_date' => 'required|date',
            'short_desc' => 'nullable'
        ];

        if ($this->has('is_unit') && $this->is_unit) {
            $arr['unit_id'] = 'required|exists:units,id';
        }

        return $arr;
    }
}
