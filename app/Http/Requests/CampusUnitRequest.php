<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampusUnitRequest extends FormRequest
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
            'campus_id' => 'required|exists:campuses,id',
            'unit_id' => "required|exists:units,id|unique:campus_units,unit_id,{$this->id},id",
            'permalink' => 'url|required',
            'image_path' => 'nullable|mimes:jpeg,jpg,png|dimensions:width=1080,height:1080',
            'image_potrait_path' => 'nullable|mimes:jpeg,jpg,png|dimensions:width=1080,height:1920',
            'image_landscape_path' => 'nullable|mimes:jpeg,jpg,png|dimensions:width=1920,height:1080',
            'about' => 'nullable',
            'keunggulan' => 'nullable',
            'sambutan' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'campus_id.required'  => 'Pilih kampus',
            'unit_id.required' => 'Pilih unit',
            'image_path.dimensions' => 'image 1:1 harus memiliki ukuran dimensi 1080px x 1080px',
            'image_landscape_path.dimensions' => 'image landscape harus memiliki ukuran dimensi 1920px x 1080px',
            'image_potrait_path.dimensions' => 'image potrait harus memiliki ukuran dimensi 1080px x 1920px',
        ];
    }
}
