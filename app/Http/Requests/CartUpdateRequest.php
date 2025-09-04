<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartUpdateRequest extends FormRequest
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
            'details' => ['required', 'array'],
            'details.*' => ['array', 'required_with:details'],
            'details.*.slug' => ['required_with:details', 'exists:products,slug', 'string'],
            'details.*.quantity' => ['required_with:details', 'numeric', 'min:0'],
            'details.*.size' => ['required_with:details', 'string', 'exists:product_details,size'],
            'voucher' => ['nullable', 'string']
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
