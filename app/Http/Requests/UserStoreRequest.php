<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        if (!$this->has('role_units')) {
            $this->merge([
                'role_units' => []
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required', 'string', 'max:255', "unique:users,username,{$this->id}"],
            'user_account' => ['required', 'user_account', 'string', 'max:255'],
            'type' => ['required', 'in:admin,guru,siswa,vendor,admin_ppdb,ppdb,author,editor,shop,super_admin,ksp,pegawai'],
            'status' => ['required', 'in:active,inactive'],
            'email' => ['required', 'email', 'string', 'max:255'],
            'mobile_phone' => ['required', 'phone:ID'],
            'role_units' => ['nullable', 'array'],
            'password' => ['required_without:id', 'nullable', 'string', 'min:8', 'confirmed']
        ];
    }

    public function messages()
    {
        return [
            'password.required_without' => 'Password harus diisi',
            'mobile_phone.phone' => 'Nomor telephone harus berawalan 62',
        ];
    }
}
