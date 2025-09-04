<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
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
            'name' => ['required', 'string'],
            'email' => ['required', 'string'],
            'mobile_phone' => ['required', 'phone:ID'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'school_year' => ['nullable', 'numeric'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'payment_agreement_id' => ['nullable', 'exists:payment_agreements,id'],
            'nik' => ['nullable', "unique:teachers,nik,{$this->id}"],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'mobile_phone.required' => 'Nomor telepon harus diisi',
            'password.required' => 'Password baru harus diisi',
            'password.confirmed' => 'Konfirmasi password salah',
        ];
    }
}
