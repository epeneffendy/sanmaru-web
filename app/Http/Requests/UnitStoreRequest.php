<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitStoreRequest extends FormRequest
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
        $rules = [
            'unit_code' => ['required', "unique:units,unit_code,{$this->id},id"],
            'name' => ['required', 'string'],
            'city' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'phone:ID'],
            'address' => ['nullable'],
            'about' => ['nullable'],
            'keunggulan' => ['nullable'],
            'image_path' => ['nullable', 'mimes:jpeg,jpg,png'],
            'banner_path' => ['nullable', 'mimes:jpeg,jpg,png'],
            'subjects' => ['nullable'],
            'jobs' => ['nullable'],
            'contents' => ['nullable'],
            'testimony_ids' => ['nullable'],
            'photo_paths' => ['nullable'],
            'photo_paths.*' => ['nullable', 'mimes:jpeg,jpg,png'],
            'procedure' => ['nullable'],
            'unit_cost_ids' => ['nullable'],
            'unit_cost_ids.*' => ['nullable'],
            'cost_titles' => ['nullable'],
            'cost_titles.*' => ['nullable'],
            'cost_descriptions' => ['nullable'],
            'cost_descriptions.*' => ['nullable'],
            'helpdesk' => ['nullable'],
            'payment_option' => ['required', 'in:CIMB Niaga,Mandiri'],
            'header_info' => ['nullable'],
            'keunggulan_path' => ['required', 'mimes:jpeg,jpg,png'],
            'present_color' => ['nullable'],
            'telp' => ['nullable', 'string'],
            'fax' => ['nullable', 'string'],
        ];

        if ($this->id) {
            $rules['keunggulan_path'] = ['nullable', 'mimes:jpeg,jpg,png'];
        }

        return $rules;
    }
}
