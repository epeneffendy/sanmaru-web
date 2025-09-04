<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolLifeRequest extends FormRequest
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
        
        $featured_image = 'nullable|mimes:jpeg,jpg,png';

        return [
            'title' => 'required|string|max:255',
            'short_desc' => 'required|string|max:255',
            'category_id' => 'required|exists:school_life_categories,id',
            'content' => 'string|required',
            'publish_date' => 'date|required',
            'user_id' => 'integer|nullable',
            'featured_image' => $featured_image,
            'published' => 'required'
        ];
    }
}
