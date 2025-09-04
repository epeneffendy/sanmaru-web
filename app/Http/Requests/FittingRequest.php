<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FittingRequest extends FormRequest
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
        $this->merge([
            'hour_start' => $this->hour_start_hour.':'.$this->hour_start_minute,
            'hour_end' => $this->hour_end_hour.':'.$this->hour_end_minute,
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
            'unit_id' => 'required|integer|exists:units,id',
            'date' => 'required|date_format:Y-m-d',
            'hour_start' => 'required|date_format:H:i',
            'hour_end' => 'required|date_format:H:i|after_or_equal:hour_start',
            'quota' => 'required|integer|max:999',
            'note' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'hour_end.after_or_equal' => 'Jam awal harus kurang dari jam akhir'
        ];
    }
}
