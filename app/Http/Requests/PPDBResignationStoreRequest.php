<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PPDBResignationStoreRequest extends FormRequest
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
            'unit_id' => 'required|exists:units,id',
            'register_number' => 'required|exists:ppdb_users,register_number'
        ];
    }

    public function attributes()
    {
        return [
            'unit_id' => 'unit',
            'register_number' => 'nomor registrasi'
        ];
    }
}
