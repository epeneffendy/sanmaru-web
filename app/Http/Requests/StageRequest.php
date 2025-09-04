<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StageRequest extends FormRequest
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
        if ($this->has('opening_feature')) {
            $this->merge([
                'is_opening_shop_feature' => ($this->opening_feature == 'is_opening_shop_feature') ? true : false,
                'is_opening_development_feature' => ($this->opening_feature == 'is_opening_development_feature')  ? true : false,
            ]);
        }

        $this->merge([
            'active' => $this->active == 'true' ? true : false,
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
            'name' => 'required',
            'unit_id' => 'required|integer|exists:units,id',
            'information' => 'nullable',
            'periode' => 'nullable',
            'is_opening_shop_feature' => 'sometimes|boolean',
            'is_opening_development_feature' => 'sometimes|boolean',
            'active' => 'required|boolean'
        ];
    }
}
