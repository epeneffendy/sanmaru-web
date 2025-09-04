<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Event;

class EventStoreRequest extends FormRequest
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
                    'title' => ['required', 'string'],
                    'description' => ['required', 'string'],
                    'event_time' => ['required', 'date_format:Y-m-d H:i:s'],
                    'location' => ['required', 'string'],
                    'status' => ['required', 'in:'. Event::STATUS_PUBLISHED .','. Event::STATUS_UNPUBLISHED],
                    'image' => ['nullable', 'mimes:jpeg,jpg,png'],
                ];
    }
}
