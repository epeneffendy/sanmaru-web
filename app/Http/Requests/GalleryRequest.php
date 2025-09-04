<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
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
            'title' => 'required|max:255|min:3',
            'published' => 'nullable',
            'description' => 'nullable',
            'content_url' => 'required|mimes:jpeg,jpg,png',
        ];

        if ($this->method == "PUT" || $this->method == "PATCH") {
            $content_url = 'nullable|mimes:jpeg,jpg,png';
        }

        if ($this->has('is_unit') && $this->is_unit) {
            $arr['unit_id'] = 'required|exists:units,id';
        }

        return $arr;
    }

    public function attributes()
    {
        return [
            'content_url'  => 'upload image',
            'published' => 'status publish'
        ];
    }
}
