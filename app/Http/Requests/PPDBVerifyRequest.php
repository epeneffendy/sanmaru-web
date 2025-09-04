<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PPDBVerifyRequest extends FormRequest
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
            'v' => ['required', 'exists:users,register_token']
        ];
    }

    public function messages()
    {
        return [
            'v.exists' => 'Register token tidak ditemukan'
        ];
    }
}
