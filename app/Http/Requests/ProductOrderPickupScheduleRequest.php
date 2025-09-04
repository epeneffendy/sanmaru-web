<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductOrderPickupScheduleRequest extends FormRequest
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
            'unit' => ['integer', 'exists:units,id'],
            'year' => ['integer'],
            'period' => ['array'],
            'pickup_date_schedule' => ['required', 'date'],
            'alt_pickup_date_schedule' => ['nullable', 'date'],
            'pickup_start_time' => ['required', 'date_format:H:i'],
            'pickup_end_time' => ['required', 'date_format:H:i', 'after:pickup_start_time'],
            'pickup_location' => ['required', 'string'],
            'pickup_notes' => ['nullable', 'string'],
            'send_email' => ['nullable', 'boolean'],
        ];
    }
}
