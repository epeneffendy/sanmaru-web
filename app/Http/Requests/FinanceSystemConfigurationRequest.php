<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinanceSystemConfigurationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'min_down_payment' => 'required|numeric',
            'down_payment_multiple' => 'required|numeric',
            'recommended_down_payment' => 'required|numeric',
            'max_absolute_installment' => 'required|numeric',
            'effective_date' => 'required|date',
        ];
    }
}
