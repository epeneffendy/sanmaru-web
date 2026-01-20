<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UniformDeadlineRequest extends FormRequest
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
            'school_year' => 'required|integer',
            'uniform_payment_deadline' => 'required|string',
        ];
    }
}
