<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentAcceptanceRequest extends FormRequest
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
            'nis'=> $this->nis != "" ? $this->nis : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:ppdb_users,id',
            'nis' => 'nullable|unique:students,nis',
            'class_id' => 'required|exists:classes,id',
            'unit_id' => 'required|exists:units,id',
            'periode' => 'required|exists:periods,id',
        ];
    }

    public function attributes()
    {
        return [
            'class_id' => 'Kelas'
        ];
    }
}
