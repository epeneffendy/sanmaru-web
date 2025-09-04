<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherStoreRequest extends FormRequest
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
            'nik' => ['required', "unique:teachers,nik,{$this->id}"],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255', "unique:teachers,email,{$this->id}"],
            'mobile_phone' => ['required', 'phone:ID', "unique:teachers,mobile_phone,{$this->id}"],
            'address' => ['required']
        ];
    }
}
