<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogStoreRequest extends FormRequest
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
            'short_desc' => 'required|string|max:255',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'content' => 'string|required',
            'publish_date' => 'date|required',
            'user_id' => 'integer|nullable',
            'featured_image' => ['nullable', 'mimes:jpeg,jpg,png'],
            'published' => 'required'
        ];

        if ($this->has('is_unit') && $this->is_unit) {
            $arr['unit_id'] = 'required|exists:units,id';
        }
        
        return $arr;
    }
}
