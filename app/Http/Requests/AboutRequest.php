<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
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
            'title' => 'required|string|max:191',
            'short_desc' => 'nullable|string|max:191',
            'about_category_id' => 'required|exists:about_categories,id',
            'content' => 'required|string',
            'publish_date' => 'required|date',
            'user_id' => 'integer|nullable',
            'featured_image' => 'nullable|mimes:jpeg,jpg,png',
            'published' => 'required'
        ];

        if ($this->has('is_unit') && $this->is_unit) {
            $arr['unit_id'] = 'required|exists:units,id';
        }

        return $arr;
    }

    public function attributes()
    {
        return [
            'about_category_id' => 'kategori'
        ];
    }
}
