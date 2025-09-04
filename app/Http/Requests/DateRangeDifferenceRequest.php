<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\Models\ProductOrder;
use Illuminate\Foundation\Http\FormRequest;

class DateRangeDifferenceRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $startDate = trim(@explode('-', $this->date_range)[0]);
        $endDate = trim(@explode('-', $this->date_range)[1]);

        $this->merge([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days_difference' => Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)->endOfDay())
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'start_date' => 'required|date|date_format:m/d/Y',
            'end_date' => 'required|date|after:start_date|date_format:m/d/Y',
            'days_difference' => 'required|numeric|max:62'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'days_difference.max' => 'Rentang waktu tidak boleh lebih dari 62 hari'
        ];
    }
}
