<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintOrderRequest extends FormRequest
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
            'user_id' => ['nullable'],
            'complaint_category_id' => ['nullable'],
            'product_order_id' => ['required'],
            'product_detail_id' => ['nullable'],
            'product_id' => ['nullable'],
            'phone' => ['required'],
            'email' => ['required'],
            'description' => ['nullable'],
            'status' => ['nullable'],
            'attachments' => ['nullable', 'mimes:jpeg,jpg,png'],
            'created_by' => ['nullable'],
            'updated_by' => ['nullable'],
            'created_at' => ['nullable'],
            'updated_at' => ['nullable'],
        ];
    }
}
