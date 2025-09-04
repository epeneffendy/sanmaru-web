<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestNewPasswordRequest extends FormRequest
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
            'id' => ['required', 'string', 'exists:users,remember_token'],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Token harus diisi!',
            'id.exists' => 'Token tidak terdaftar!'
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['token'] = $this->route('token');
        return $data;
    }
}
