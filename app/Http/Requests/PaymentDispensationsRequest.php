<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentDispensationsRequest extends FormRequest
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
            'dispensation_type' => 'required|string',
            'dispensation_mode' => 'required|string',
            'ppdb_user_id' => 'required|integer|exists:ppdb_users,id',
            'school_year' => 'required|integer',
            'actual_cost' => 'required|numeric',
            'remaining_balance' => 'nullable|numeric',
            'total_final_fee' => 'required|numeric',
            'down_payment' => 'nullable|numeric',
            'tenor' => 'nullable|numeric',
        ];
    }
}
