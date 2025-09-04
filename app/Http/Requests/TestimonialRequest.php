<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
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
        $arr = [
            'photo_path' => 'required|mimes:jpeg,jpg,png',
            'subject' => 'required|max:255|min:3',
            'content' => 'required',
            'published' => 'nullable',
        ];

        if ($this->method == "PUT" || $this->method == "PATCH") {
            $arr['photo_path'] = 'nullable|mimes:jpeg,jpg,png';
        }

        if ($this->has('is_unit') && $this->is_unit) {
            $arr['unit_id'] = 'required|exists:units,id';
        }

        return $arr;
    }
}
