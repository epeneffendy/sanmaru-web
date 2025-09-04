<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewPasswordRequest extends FormRequest
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
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => 'Password lama harus diisi',
            'password.required' => 'Password baru harus diisi',
            'password.confirmed' => 'Konfirmasi password salah',
        ];
    }
}
