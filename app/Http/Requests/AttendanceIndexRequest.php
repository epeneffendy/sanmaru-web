<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceIndexRequest extends FormRequest
{
    const DEFAULT_LIMIT = 20;
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
            'limit' => ['nullable', 'numeric'],
            'offset' => ['nullable', 'numeric'],
            'start_date' => ['nullable', 'date', 'date_format:Y-m-d'],
        ];
    }

    public function validated()
    {
        $params = parent::validated();
        if (empty($params['limit'])) $params['limit'] = $this::DEFAULT_LIMIT;
        if (empty($params['offset'])) $params['offset'] = 0;
        return $params;
    }
}
