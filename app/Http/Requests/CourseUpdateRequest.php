<?php

namespace App\Http\Requests;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class CourseUpdateRequest extends FormRequest
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
            'unit_id' => ['required', 'exists:units,id'],
            'code' => ['required', 'string', 'max:255', "unique:courses,code,{$this->course->id},id"],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['in:'. Course::STATUS_INACTIVE .','. Course::STATUS_ACTIVE]
        ];
    }
}
