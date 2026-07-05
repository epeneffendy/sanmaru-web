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
            'unit_id' => 'required|integer|exists:units,id',
            'ppdb_user_id' => 'required|integer|exists:ppdb_users,id',
            'school_year' => 'required|integer',
            'status'=>'nullable|string',
            'reason'=>'nullable|string',
            'attachment'=>'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx'
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
