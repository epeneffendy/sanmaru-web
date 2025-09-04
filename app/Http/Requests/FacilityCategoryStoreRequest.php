<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacilityCategoryStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'section' => 'required|string|in:main,sub'
        ];
    }
}
