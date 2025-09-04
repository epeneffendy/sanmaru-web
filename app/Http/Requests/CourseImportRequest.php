<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Course;

class CourseImportRequest extends FormRequest
{
    public function __construct($code = null)
    {
        parent::__construct();
        $this->code = $code;
    }

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
            'code' => ['required', 'string', 'max:255', "unique:courses,code,{$this->code},code"],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['in:'. Course::STATUS_INACTIVE .','. Course::STATUS_ACTIVE]
        ];
    }
}
